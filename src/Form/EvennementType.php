<?php

namespace App\Form;

use App\Entity\Evennement;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class EvennementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('datedebut')
            ->add('datefin')
            ->add('libelle')
            ->add('heuredebut')
            ->add('heurefin')
            ->add('nbplaces')
            ->add('prix')
            ->add('flyer', FileType::class, [
                'label' => 'adset image (Des fichiers images uniquement)',
                // unmapped means that this field is not associated to any entity property
                'mapped' => false,
                // make it optional so you don't have to re-upload the PDF file
                // every time you edit the Product details
                'required' => false,
                // unmapped fields can't define their validation using annotations
                // in the associated entity, so you can use the PHP constraint classes
                'constraints' => [
                    new File([
                        'maxSize' => '3000000k',
                        'mimeTypes' => [
                            'image/gif',
                            'image/jpeg',
                            'image/png',
                            'image/jpg',
                            'video/mp4',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid Image',
                    ])
                ],

            ])
            ->add('video', FileType::class, [
                'label' => 'adset image (Des fichiers images uniquement)',
                // unmapped means that this field is not associated to any entity property
                'mapped' => false,
                // make it optional so you don't have to re-upload the PDF file
                // every time you edit the Product details
                'required' => false,
                // unmapped fields can't define their validation using annotations
                // in the associated entity, so you can use the PHP constraint classes
                'constraints' => [
                    new File([
                        'maxSize' => '3000000k',
                        'mimeTypes' => [
                            'image/gif',
                            'image/jpeg',
                            'image/png',
                            'image/jpg',
                            'video/mp4',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid Image',
                    ])
                ],

            ])
            ->add('Valider', SubmitType::class);


    }


    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Evennement::class,
        ]);
    }
}
