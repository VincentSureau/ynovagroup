<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Company;
use App\Repository\CompanyRepository;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class UserType extends AbstractType
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

                $form->add('company', EntityType::class, [
                    'label' => 'Pharmacie',
                    'class' => Company::class,
                    'query_builder' => function (CompanyRepository $er) use ($user) {
                        if ($user->getId()) {
                            return $er
                                ->createQueryBuilder('c')
                                ->leftJoin('c.user', 'u')
                                ->andWhere('u.id IS NULL')
                                ->orWhere('u = :user')
                                ->setParameter('user', $user)
                            ;
                        }
                        return $er
                            ->createQueryBuilder('c')
                            ->leftJoin('c.user', 'u')
                            ->andWhere('u.id IS NULL')
                        ;
                    },
                    'required' => false
                ]);
        });
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
