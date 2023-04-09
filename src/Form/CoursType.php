<?php

namespace App\Form;

use App\Entity\Cours;
use ContainerFlHVtxg\getCategorieTypeService;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Validator\Constraints\File;


class CoursType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre')
            ->add('matiere')
            ->add('domaine',ChoiceType::class, [
                'choices' => [
                    'Reseau  ' => 'Reseau',
                    'Informatique  ' => 'Informatique',
                    'Electronique  ' => 'Electronique',
                    'Mecanique  ' => 'Mecanique',
                ],
                'expanded' => true,])
            ->add('categorie', ChoiceType::class, array(
                    'choices' => array(
                    'Appliquée' => 'Appliquée',
                    'Fondamontale' => 'Fondamentale',
                ),
            ))
            ->add('chemin',FileType::class,[
                'label' => 'adset html (Des fichiers html uniquement)',
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
                            'text/html',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid html',
                    ])
                ],
            ])
            ->add('logo',FileType::class,[
                'label' => 'adset html (Des fichiers png uniquement)',
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
                            'image/jpeg',
                            'image/png',
                            'image/jpg',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid png',
                    ])
                ],
            ])
            ->add('Valider',SubmitType::class);

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Cours::class,
        ]);
    }
}
