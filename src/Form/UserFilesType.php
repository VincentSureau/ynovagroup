<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Files;
use App\Repository\UserRepository;
use Symfony\Component\Form\AbstractType;
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

class UserFilesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $file = $builder->getData();

        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom du document',
            ])
            ->add('documentFile', VichFileType::class, [
                'required' => false,
                'label' => 'Choisir un fichier',
                'allow_delete' => false,
                'download_label' => 'Télécharger le fichier',
                'download_uri' => true,
                'download_link' => true,
                'constraints' => [
                    new File(
                        ['mimeTypes' => [
                            "application/pdf",
                            "application/x-pdf",
                            "application/msword",
                            "application/vnd.openxmlformats-officedocument.wordprocessingml.document",
                            "image/jpeg",
                            "image/jpg",
                            "application/vnd.ms-powerpoint",
                            "application/vnd.openxmlformats-officedocument.presentationml.presentation",
                            "application/zip",
                            "application/vnd.ms-excel",
                            "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"
                        ],
                        'mimeTypesMessage' => "Le format du fichier est invalide ({{ type }})"
                    ])
                ]
                
            ])
            // ->add('description',  TextareaType::class, [
            //     'label' => 'Description',
            //     'required' => false
            // ])
            // ->add('isActive', ChoiceType::class, [
            //     'choices' => [
            //         'Oui' => true,
            //         'Non' => false
            //     ],
            //     'placeholder' => false,
            // ])
            // ->add('deletedAt', DateType::class, [
            //     'label' => 'Disponible jusqu\'au',
            //     'widget' => 'single_text',
            //     'required' => false,
            // ])
            // ->add('selectAll', CheckboxType::Class, [
            //     'label' => 'Envoyer à toutes les pharmacies',
            //     'mapped' => false,
            //     'required' => false
            // ])
        ;        
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Files::class,
        ]);
    }
}
