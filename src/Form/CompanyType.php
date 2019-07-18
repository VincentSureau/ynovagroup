<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Company;
use App\Repository\UserRepository;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Security\Core\Security;
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
    private $security;

    public function __construct(Security $security) {
        $this->security = $security;
    }

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
        ;

        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function(FormEvent $event) {
                $form = $event->getForm();
                $company = $event->getData();

                if ($this->security->isGranted('ROLE_ADMIN')) {
                    $form->add('commercial', EntityType::class, [
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
                } else {
                    $form->add('commercial', EntityType::class, [
                        'label' => 'Commercial',
                        'class' => User::class,
                        'choices' => [
                            $this->security->getUser()
                        ],
                        'placeholder' => false
                    ]);
                }
            }
        );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Company::class,
        ]);
    }
}
