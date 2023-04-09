<?php

namespace App\Form;

use App\Entity\Candidature;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use function Sodium\add;

class CandidatureType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder


            ->add('domaine',ChoiceType::class, [
                'choices'  => [
                    'Informatique'=> 'Informatique' ,
                    'Mecanique'=> 'Mecanique',
                    'Electronique'=>'Electronique',
                ],
            ])
            ->add('diplome',ChoiceType::class, [
                'choices'  => [
                    'Technicien superieur'=> 'Technicien superieur' ,
                    'ingenieur'=> 'ingenieur',
                    'Master'=>'Master'
                ],
            ])
            ->add('experience',ChoiceType::class, [
                'choices'  => [
                    '1-2'=> '1-2',
                    '2-5'=>'2-5',
                    '5-10'=>'5-10',
                    'plus de 10 ans'=>'plus de 10 ans'
                ],
            ])

            ->add('niveaufrancais', ChoiceType::class, [
                    'choices'  => [
                        'faible'=> 'faible' ,
                        'moyen'=> 'moyen',
                        'bien'=>'bien',
                    ],
                    'expanded'=> true
                ]
            )
            ->add('niveauanglais', ChoiceType::class, [
                'choices'  => [
                    'faible'=> 'faible' ,
                    'moyen'=> 'moyen',
                    'bien'=>'bien',
                ],'expanded'=> true
            ],)
            ->add('cv')
            ->add('lettremotivation')

            ->add('typecondidature', ChoiceType::class, [
                'choices'  => [
                    'Offre'=> 'Offre' ,
                    'stage'=> 'stage',
                    'freelancer'=>'freelancer'
                ],
            ])
            ->add('notetest', ChoiceType::class, [
                'choices'  => [
                    'pas encore'=> 'pas encore'
                ],
            ])
            ->add('statut', ChoiceType::class, [
                'choices'  => [
                    'non traité'=> 'non traité'
                ],
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Candidature::class,
        ]);
    }
}
