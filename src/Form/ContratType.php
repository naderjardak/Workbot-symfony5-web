<?php

namespace App\Form;

use App\Entity\Contrat;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;


class ContratType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('typecontrat', ChoiceType::class, [
                'choices'  => [
                    'CDI'=> 'CDI' ,
                    'CDD' => 'CDD',
                    'CDI(senior)'=>'CDI(senior)',
                ],
            ])
            ->add('datedebut')
            ->add('salaire')
            ->add('datefin')
            ->add('lien',null,array('label' => 'Email'))


        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Contrat::class,
        ]);
    }
}
