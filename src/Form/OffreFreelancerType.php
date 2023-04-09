<?php

namespace App\Form;

use App\Entity\Offre;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OffreFreelancerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre')
            ->add('dureestage',null,['label'=>'Durée'])
            ->add('description')
            ->add('salaire',null,['label'=>'Rénumeration'])
            ->add('dateexpiration',null, [
                'placeholder' => [
                    'year' => 'Année',
                    'month' => 'Mois',
                    'day' => 'Jour',
                ],
                'label'=>"Date d'expiration"

            ])            ->add('modetravail',ChoiceType::class,
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
