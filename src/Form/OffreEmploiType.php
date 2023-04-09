<?php

namespace App\Form;

use App\Entity\Offre;
use phpDocumentor\Reflection\Types\True_;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\RadioType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OffreEmploiType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre')
            ->add('salaire')
            ->add('description')

             ->add('dateexpiration',null, [
                'placeholder' => [
                    'year' => 'Année',
                    'month' => 'Mois',
                    'day' => 'Jour',
                ],
                 'label'=>"Date d'expiration"

             ])
            ->add('typecontrat',
                ChoiceType::class,
                ['choices'=>[
                    'CDI'=>'CDI',
                    "CDD"=>"CDD",
                    'CIVP'=>'CIVP'
                ]])
            ->add('modetravail',ChoiceType::class,
                ['choices'=>[
                    'Présentiel'=>'Présentiel',
                    'Hybrid'=>'Hybrid',
                    'Teletravail'=>'Teletravail'
                ]

            ])
            ->add('lieu')
            ->add('test',CollectionType::class,[
                'entry_type'=>TestType::class,
                'label'=>'test',
                'entry_options'=>['label'=>false],
                'allow_add'=>true,
                'allow_delete'=>true,
                'by_reference'=>false,
                'mapped'=>false

            ]);

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Offre::class,
        ]);
    }
}
