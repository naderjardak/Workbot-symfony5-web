<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class UserType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $builder
            ->add('nom')
            ->add('prenom')
            ->add('email')
            ->add('password',RepeatedType::class,[
                'type'=>PasswordType::class,
                'first_options'=>['label'=>'Password'],
                'second_options'=>['label'=>'Password']

            ])
            ->add('role', ChoiceType::class, array(
                'choices' => array(
                    'sociéte' => 'sociéte',
                    'candidat' => 'candidat',
                )))
            ->add('questionSecu', ChoiceType::class, array(
                'choices' => array(
                    'Quel est votre animal préféré ?
' => 'Quel est votre animal préféré ?
',
                    'Quel est votre meilleur joueur ?
' => 'Quel est votre meilleur joueur ?
',                    '
Où était votre première voiture ?
' => '
Où était votre première voiture ?
',
                )))
            ->add('reponseSecu')
            ->add('domaine')
            ->add('adresse')
            ->add('tel')
            ->add('envoyer',SubmitType::class)

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
