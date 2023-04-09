<?php

namespace App\Form;

use App\Entity\Utilisateur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UtilisateurType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('prenom')
            ->add('tel')
            ->add('email')
            ->add('password')
            ->add('adresse')
            ->add('photo')
            ->add('questionsecu')
            ->add('reponsesecu')
            ->add('methode')
            ->add('formejuridique')
            ->add('raisonsociale')
            ->add('domaine')
            ->add('pattente')
            ->add('nomsociete')
            ->add('diplome')
            ->add('experience')
            ->add('niveaufr')
            ->add('niveauang')
            ->add('competance')
            ->add('cv')
            ->add('portfolio')
            ->add('bio')
            ->add('typecandidat')
            ->add('note')
            ->add('role')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Utilisateur::class,
        ]);
    }
}
