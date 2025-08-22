<?php

namespace App\Form;

use App\Entity\Payment;
use App\Entity\Request;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class PaymentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('request', EntityType::class, [
                'class' => Request::class,
                'choice_label' => 'ref',
                'placeholder' => 'Choisir une demande',
            ])
            ->add('mode')
            ->add('reference')
            ->add('montant')
            ->add('statut', ChoiceType::class, [
                'choices' => [
                    'En attente' => 'pending',
                    'Payé' => 'paid',
                    'Annulé' => 'cancelled',
                ],
                'placeholder' => 'Choisir un statut',
            ])
            ->add('recu_url')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Payment::class,
        ]);
    }
}
