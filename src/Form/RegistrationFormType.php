<?php

namespace App\Form;

use App\Entity\Utilisateur;
use Karser\Recaptcha3Bundle\Form\Recaptcha3Type;
use Karser\Recaptcha3Bundle\Validator\Constraints\Recaptcha3;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email',EmailType::class,[
        'attr' => [
        'class' => 'form-control'
    ],
                'label' => 'E-mail'
            ])
            ->add('nom',TextType::class, [
                'attr' => [
                    'class' => 'form-control'
                ],
                'label' => 'Nom'
            ])
            ->add('prenom',TextType::class, [
                'attr' => [
                    'class' => 'form-control'
                ],
                'label' => 'Prénom'
            ])
            ->add('roles', CollectionType::class, [
                'entry_type'   => ChoiceType::class,
                'attr' => ['required' => true],
                'entry_options'  => [
                    'label' => false,

                    'choices' => [
                        'candidat' => 'ROLE_c',
                        'sociéte' => 'ROLE_s',

                    ],
                    'expanded' => false,
                    'multiple' => false,

                ],


            ])
        /*    ->add('roles',ChoiceType::class,[
                'choices' => [
                    'candidat' => array('ROLE_c'),
                    'sociéte' => array('ROLE_s'),
                ],
                'expanded' => true,
                'multiple' => true,
                'label' => 'Rôles'
            ])*/
            ->add('questionSecu', ChoiceType::class, array(
                'choices' => array(
                    'Quel est votre animal préféré ?
' => 'questionSecu
',
                    'Quel est votre meilleur joueur ?
' => 'questionSecu
',                    '
Où était votre première voiture ?
' => '
questionSecu
',
                )))
            ->add('reponseSecu',TextType::class)
            ->add('domaine',TextType::class)
            ->add('adresse',TextType::class, [
                'attr' => [
                    'class' => 'form-control'
                ],
                'label' => 'Adresse'
            ])
            ->add('tel',TextType::class)
            ->add('password', PasswordType::class)
            ->add('captcha', Recaptcha3Type::class, [
                'constraints' => new Recaptcha3(),
                'action_name' => 'homepage',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Utilisateur::class,
        ]);
    }
}
