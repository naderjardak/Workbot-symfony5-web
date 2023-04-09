<?php

namespace App\Form;

use App\Entity\Offre;
use phpDocumentor\Reflection\Types\Null_;
use phpDocumentor\Reflection\Types\True_;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\RadioType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OffreStageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre',null,
                [   'required'=>false]
            )
            ->add('dureestage',NULL,['label'=>'Durée', 'required'=>false,'attr'=>['novalidate']])
            ->add('description',TextareaType::class,
                [   'required'=>false,
                    'attr'=>['rows'=>5]])
            ->add('dateexpiration',null, [
                'placeholder' => [
                    'year' => 'Année',
                    'month' => 'Mois',
                    'day' => 'Jour',
                ],
                'label'=>"Date d'expiration"

            ])            ->add('typestage',
                ChoiceType::class,
                ['choices'=>[
                    'PFE'=>'PFE',
                    "Stage d'été"=>"Stage d'été",
                    'Alternance'=>'Alternance'
                ]])
            ->add('modetravail',ChoiceType::class,
                ['choices'=>[
                    'Présentiel'=>'Présentiel',
                    'Hybrid'=>'Hybrid',
                    'Teletravail'=>'Teletravail'
                ]

            ])
            ->add('lieu',Null,
                [   'required'=>false])
            ->add('test',CollectionType::class,[
                'entry_type'=>TestType::class,
                'label'=>'test',
                'entry_options'=>['label'=>false],
                'allow_add'=>true,
                'allow_delete'=>true,
                'by_reference'=>false,
                'mapped'=>false

            ]);
          //  ->add('Ajouter', SubmitType::class) ;


    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Offre::class,
        ]);
    }
}
