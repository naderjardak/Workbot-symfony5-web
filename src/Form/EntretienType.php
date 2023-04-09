<?php

namespace App\Form;

use App\Entity\Entretien;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EntretienType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('date',null, [
                'placeholder' => [
                    'year' => 'Année',
                    'month' => 'Mois',
                    'day' => 'Jour',
                ],

            ])
            ->add('lienmeet')
            ->add('heure',null, [
                'placeholder' => [
                    'hour' => 'Heure', 'minute' => 'Minute'                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Entretien::class,
        ]);
    }
}
