<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Files;
use App\Entity\Company;
use App\Repository\UserRepository;
use App\Repository\CompanyRepository;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Security\Core\Security;
use Vich\UploaderBundle\Form\Type\VichFileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class FilesType extends AbstractType
{
    private $security;

    public function __construct(Security $security) {
        $this->security = $security;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $file = $builder->getData();

        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom du document',
            ])
            ->add('description',  TextareaType::class, [
                'label' => 'Description',
                'required' => false
            ])
            ->add('isActive', ChoiceType::class, [
                'label' => 'Actif',
                'choices' => [
                    'Oui' => true,
                    'Non' => false
                ],
                'placeholder' => false,
            ])
            ->add('deletedAt', DateType::class, [
                'label' => 'Disponible jusqu\'au',
                'widget' => 'single_text',
                'required' => false,
            ])

        ;        

        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function(FormEvent $event) {
                $form = $event->getForm();
                $file = $event->getData();

                if ($this->security->isGranted('ROLE_ADMIN')) {
                    $form
                    ->add('documentFile', VichFileType::class, [
                        'required' => false,
                        'label' => 'Importer le fichier',
                        'allow_delete' => false,
                        'download_label' => 'Télécharger le fichier',
                        'download_uri' => true,
                        'download_link' => true
                    ])
                    ->add('pharmacies', EntityType::Class, [
                            'label' => 'Pharmacies',
                            'class' => Company::class,
                            'multiple' => true,
                            'attr' => ['class' => 'select2 d-none'],
                            'choice_attr' => function($choice, $key, $value) use ($file) {
                                if($file->getReadBy()->contains($choice->getUser())) {
                                    return ['data-read' => 'true'];
                                };
                                return ['data-read' => 'false'];
                            }
                        ]);
                } else {
                    $form
                    ->add('documentFile', VichFileType::class, [
                        'required' => false,
                        'label' => 'Importer le fichier',
                        'allow_delete' => false,
                        'download_label' => 'Télécharger le fichier',
                        'download_uri' => true,
                        'download_link' => true,
                        'constraints' => [
                            new File(
                                ['mimeTypes' => ["application/pdf", "application/x-pdf"]]
                            )
                        ]
                    ])
                    ->add('pharmacies', EntityType::Class, [
                            'label' => 'Pharmacies',
                            'class' => Company::class,
                            'multiple' => true,
                            'attr' => ['class' => 'select2 d-none'],
                            'choice_attr' => function($choice, $key, $value) use ($file) {
                                if($file->getReadBy()->contains($choice->getUser())) {
                                    return ['data-read' => 'true'];
                                };
                                return ['data-read' => 'false'];
                            },
                            'query_builder' => function (CompanyRepository $er) {
                                return $er
                                    ->createQueryBuilder('c')
                                    ->join('c.commercial', 'co')
                                    ->andWhere('c.commercial = :commercial')
                                    ->setParameter('commercial', $this->security->getUser())
                                ;
                            },
                        ]);
                }
                    
                $form->add('selectAll', CheckboxType::Class, [
                    'label' => 'Envoyer à toutes les pharmacies',
                    'mapped' => false,
                    'required' => false
                ]);
            }
        );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Files::class,
        ]);
    }
}
