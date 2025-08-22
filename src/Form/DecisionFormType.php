<?php
// src/Form/DecisionFormType.php

namespace App\Form;

use App\Entity\Decision;
use App\Entity\Request;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DecisionFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('request', EntityType::class, [
                'class' => Request::class,
                'choice_label' => 'id', // ou un autre champ de l'entité Request
                'label' => 'Demande(s) associée(s) :',
            ])
            ->add('resultat', TextType::class, [
                'label' => 'Résultat :',
            ])
            ->add('motif', TextType::class, [
                'label' => 'Motif :',
            ])
            ->add('valide_par', TextType::class, [
                'label' => 'Validé par :',
            ])
            ->add('valide_le', DateTimeType::class, [
                'label' => 'Validé le :',
                'widget' => 'single_text',
            ])
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Decision::class,
        ]);
    }
}
