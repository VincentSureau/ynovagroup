<?php

namespace App\Form;

use App\Entity\Post;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class PostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
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
            //->add('picture')
            ->add('isActive', ChoiceType::class, [
                'label' => 'Publié sur le blog',
                'choices' => [
                    'Oui' => true,
                    'Non' => false
                ],
                'placeholder' => false,
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
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
        ]);
    }
}
