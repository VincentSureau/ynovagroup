<?php

namespace App\Form;

use App\Entity\Rss;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class RssType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom du flux RSS'
            ])
            ->add('link', TextType::class, [
                'label' => 'Lien du flux RSS'
            ])
            ->add('isActive', ChoiceType::class,[
                'label' => 'Actif',
                'choices' => [
                    'Oui' => true,
                    'Non' => false
                ],
                'placeholder' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Rss::class,
        ]);
    }
}
