<?php

namespace App\Form;

use App\Entity\Company;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class CompanyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class)
            ->add('firstAdressField', TextType::class)
            ->add('secondAdressField', TextType::class)
            ->add('postalCode', IntegerType::class)
            ->add('city', TextType::class)
            ->add('country', TextType::class)
            ->add('description', TextType::class)
            ->add('picture')
            ->add('isActive')
            // ->add('slug')
            // ->add('createdAt')
            // ->add('updatedAt')
            // ->add('user')
            // ->add('commercial')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Company::class,
        ]);
    }
}
