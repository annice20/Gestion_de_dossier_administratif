<?php
// src/Form/SignatureFormType.php

namespace App\Form;

use App\Entity\Signature;
use App\Entity\Decision;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SignatureFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('decision', EntityType::class, [
                'class' => Decision::class,
                'choice_label' => 'id', // Vous pouvez choisir un autre champ, comme 'resultat' si vous le souhaitez
                'label' => 'Décision liée :',
            ])
            ->add('type', TextType::class, [
                'label' => 'Type de signature :',
            ])
            ->add('scelle', TextType::class, [
                'label' => 'Scellé :',
            ])
            ->add('horodatage', DateTimeType::class, [
                'label' => 'Horodatage :',
                'widget' => 'single_text',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Signature::class,
        ]);
    }
}