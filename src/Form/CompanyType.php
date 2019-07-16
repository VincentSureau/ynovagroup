<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Company;
use App\Repository\UserRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class CompanyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('pictureFile', VichImageType::class, [
                'required' => false,
                'label' => 'Image de la pharmacie',
                'allow_delete' => true,
                'download_label' => 'Télécharger l\'image',
                'download_uri' => true,
                'download_link' => false,
                'image_uri' => false,
            ])
            ->add('name', TextType::class, [
                'label' => 'Nom de la pharmacie'
            ])
            ->add('contractType', TextType::class, [
                'label' => 'Type de contrat',
                'required' => false
            ])
            ->add('cip', TextType::class, [
                'label' => 'Code CIP de la pharmacie',
                'required' => false
            ])
            ->add('firstAdressField', TextType::class, [
                'label' => 'Adresse'
            ])
            ->add('secondAdressField', TextType::class, [
                'label' => 'Complément d\'adresse',
                'required' => false
            ])
            ->add('postalCode', TextType::class, [
                'label' => 'Code postal'
            ])
            ->add('city', TextType::class, [
                'label' => 'Ville'
            ])
            ->add('country', TextType::class, [
                'label' => 'Pays',
                'required' => false
            ])
            ->add('phone', TelType::class, [
                'label' => 'Numéro de téléphone de la pharmacie',
                'required' => false
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'required' => false
            ])
            // ->add('isActive', ChoiceType::class, [
            //     'label' => 'Actif',
            //     'choices' => [
            //         'Oui' => true,
            //         'Non' => false
            //     ],
            //     'placeholder' => false,
            // ])
            // ->add('user', EntityType::class, [
            //     'label' => 'Gestionnaire',
            //     'class' => User::class,
            //     'query_builder' => function (UserRepository $er) {
            //         return $er
            //             ->createQueryBuilder('u')
            //             ->andWhere('u.roles LIKE :role')
            //             ->setParameter('role', '%"ROLE_MEMBER"%')
            //         ;
            //     },
            //     'required' => false
            // ])
            ->add('commercial', EntityType::class, [
                'label' => 'Commercial',
                'class' => User::class,
                'query_builder' => function (UserRepository $er) {
                    return $er
                        ->createQueryBuilder('u')
                        ->andWhere('u.roles LIKE :role')
                        ->setParameter('role', '%"ROLE_BUSINESS"%')
                    ;
                },
                'required' => false,
                'placeholder' => 'Choisir un commercial'
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Company::class,
        ]);
    }
}
