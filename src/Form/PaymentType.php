<?php

namespace App\Form;

use App\Entity\Payment;
use App\Entity\Request;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;

class PaymentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('request', EntityType::class, [
                'class' => Request::class,
                'choice_label' => 'ref',
                'placeholder' => 'Choisir une demande',
                'label' => 'Demande',
            ])
            ->add('mode', ChoiceType::class, [
                'choices' => [
                    'Espèces' => 'Espèces',
                    'Mobile Money' => 'Mobile Money',
                    'Virement bancaire' => 'Virement bancaire',
                    'Carte bancaire' => 'Carte bancaire',
                ],
                'placeholder' => 'Choisir un mode de paiement',
                'label' => 'Mode de paiement',
            ])
            ->add('reference', TextType::class, [
                'label' => 'Référence',
            ])
            ->add('montant', MoneyType::class, [
                'currency' => 'MGA',
                'label' => 'Montant',
            ])
            ->add('statut', ChoiceType::class, [
                'choices' => [
                    'En attente' => 'En attente',
                    'Payé' => 'Payé',
                    'Annulé' => 'Annulé',
                ],
                'placeholder' => 'Choisir un statut',
                'label' => 'Statut',
            ])
            ->add('recu_url', UrlType::class, [
                'label' => 'URL du reçu',
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Payment::class,
        ]);
    }
}
