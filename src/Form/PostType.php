<?php

namespace App\Form;

use App\Entity\Post;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class PostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('pictureFile', VichImageType::class, [
                'required' => false,
                'label' => 'Image de l\'article',
                'allow_delete' => true,
                'download_label' => 'Télécharger l\'image',
                'download_uri' => true,
                'download_link' => false,
                'image_uri' => false,
            ])
            ->add('title', TextType::class, [
                'label' => 'Titre de l\'article',
            ])
            ->add('content', TextareaType::class, [
                'label' => 'Contenu de l\'article',
                'attr' => [
                    'id' => 'content',
                    'required' => false

                ]
            ])
            ->add('isActive', ChoiceType::class, [
                'label' => 'Publié sur le fil d\'actualités',
                'choices' => [
                    'Oui' => true,
                    'Non' => false
                ],
                'placeholder' => false,
            ])
            ->add('visibility', ChoiceType::class, [
                'label' => 'Visibilité',
                'choices' => [
                    'Public' => 'public',
                    'Privé' => 'private'
                ],
                'placeholder' => false,
            ])
            ->add('deletedAt', DateType::class, [
                'label' => 'Disponible jusqu\'au',
                'widget' => 'single_text',
                'required' => false,
            ])
            ->add('author', EntityType::class, [
                'label' => 'Auteur',
                'class' => User::class,
                'query_builder' => function (UserRepository $er) {
                    return $er
                        ->createQueryBuilder('u')
                        ->andWhere('u.roles NOT LIKE :role')
                        ->setParameter('role', '%"ROLE_MEMBER"%')
                    ;
                },
                'required' => false
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
        ]);
    }
}
