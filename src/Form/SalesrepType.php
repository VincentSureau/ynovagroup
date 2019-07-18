<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Company;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class SalesrepType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('lastname', TextType::class, [
                'label' => 'Nom'
            ])
            ->add('firstname', TextType::class, [
                'label' => 'Prénom'
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email'
            ])
            ->add('isActive', ChoiceType::class, [
                'label' => 'Actif',
                'choices' => [
                    'Oui' => true,
                    'Non' => false
                ],
                'placeholder' => false,
            ])
        ;

        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function(FormEvent $event) {
                $form = $event->getForm();
                $user = $event->getData();
                $roles = $user->getRoles();

                if(in_array('ROLE_ADMIN', $roles)) {
                    $form->add('role', ChoiceType::class, [
                    'label' => 'Rôle',
                    'choices' => [
                        'Administrateur' => 'ROLE_ADMIN',
                        'Commercial' => 'ROLE_BUSINESS'
                    ],
                    'placeholder' => false,
                    'required' => true,
                    'mapped' => false
                    ]);
                } else {
                    $form->add('role', ChoiceType::class, [
                    'label' => 'Rôle',
                    'choices' => [
                        'Commercial' => 'ROLE_BUSINESS',
                        'Administrateur' => 'ROLE_ADMIN'
                    ],
                    'placeholder' => false,
                    'required' => true,
                    'mapped' => false
                    ]);
                }
        });
    }


    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
