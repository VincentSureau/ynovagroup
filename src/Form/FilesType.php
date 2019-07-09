<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Files;
use App\Entity\Company;
use App\Repository\UserRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class FilesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom du document'
            ])
            ->add('type', TextType::class, [
                'label' => 'Type de document'
            ])
            ->add('description',  TextareaType::class, [
                'label' => 'Description'
            ])
            //->add('path')
            ->add('isActive', ChoiceType::class, [
                'choices' => [
                    'Oui' => true,
                    'Non' => false
                ],
                'placeholder' => false,
            ])
            ->add('deletedAt', DateType::class, [
                'label' => 'Disponible jusqu\'au',
                'widget' => 'single_text'
            ])
            ->add('commercial', EntityType::Class, [
                'label' => 'Commercial',
                'class' => User::class,
                'query_builder' => function (UserRepository $er) {
                    return $er
                        ->createQueryBuilder('u')
                        ->andWhere('u.roles NOT LIKE :role')
                        ->setParameter('role', '%"ROLE_MEMBER"%')
                    ;
                },
            ])
            ->add('pharmacies', EntityType::Class, [
                'label' => 'Pharmacies',
                'class' => Company::class,
                'multiple' => true,
                'attr' => ['class' => 'select2']
            ])
            ->add('selectAll', CheckboxType::Class, [
                'label' => 'Envoyer à toutes les pharmacies',
                'mapped' => false,
                'required' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Files::class,
        ]);
    }
}
