<?php

namespace App\Form;

use App\Entity\Role;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;


class RegistrationProfilType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // ==== User ====
            ->add('email', EmailType::class, [
                'label' => 'Entrer votre e-mail'
            ])
            ->add('telephone', TelType::class, [
                'label' => 'Entrer votre numéro de téléphone'
            ])
            ->add('password', PasswordType::class, [
                'label' => 'Entrer le mot de passe'
            ])
            ->add('langue', ChoiceType::class, [
                'choices' => [
                    'MLG' => 'MLG',
                    'FR' => 'FR',
                ],
                'placeholder' => 'Choisir une langue',
            ])
            ->add('roles', EntityType::class, [
                'class' => Role::class,
                'choice_label' => 'libelle',
                'multiple' => false,
                'mapped' => false,
                'label' => 'Rôle de l’utilisateur'
            ])

            // ==== CitizenProfile ====
            ->add('nom', TextType::class, [
                'label' => 'Nom'
            ])
            ->add('prenoms', TextType::class, [
                'label' => 'Prénom'
            ])
            ->add('dateDeNaissance', DateType::class, [
                'label' => 'Date de naissance',
                'widget' => 'single_text'
            ])
            ->add('nin', TextType::class, [
                'label' => 'Numéro CIN'
            ])
            ->add('adresse', TextType::class, [
                'label' => 'Adresse'
            ])
            
            ->add('commune', TextType::class, [
                'label' => 'Commune'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
