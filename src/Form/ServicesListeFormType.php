<?php

namespace App\Form;

use App\Entity\CitizenProfile;
use App\Entity\Request;
use App\Entity\RequestType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ServicesListeFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('ref', TextType::class, [
                'label' => 'Référence',
                'attr' => [
                    'placeholder' => 'Ex: REF-2023-001',
                    'class' => 'form-control',
                ],
            ])
            ->add('demandeur', EntityType::class, [
                'class' => CitizenProfile::class,
                'choice_label' => function (CitizenProfile $citizen) {
                    return $citizen->getPrenoms() . ' ' . $citizen->getNom() . ' (' . $citizen->getNin() . ')';
                },
                'label' => 'Demandeur',
                'required' => false,
                'placeholder' => 'Sélectionnez un demandeur',
                'attr' => [
                    'class' => 'form-control select2',
                ],
            ])
            ->add('type', EntityType::class, [
                'class' => RequestType::class,
                'choice_label' => 'libelle',
                'label' => 'Type de demande',
                'placeholder' => 'Sélectionnez un type de demande',
                'attr' => [
                    'class' => 'form-control select2',
                ],
            ])
            ->add('centre', TextType::class, [
                'label' => 'Centre',
                'attr' => [
                    'placeholder' => 'Ex: Centre Principal',
                    'class' => 'form-control',
                ],
            ])
            ->add('priorite', ChoiceType::class, [
                'label' => 'Priorité',
                'choices' => [
                    'Normale' => 0,
                    'Urgente' => 1,
                ],
                'placeholder' => 'Sélectionnez une priorité',
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('montant', MoneyType::class, [
                'label' => 'Montant',
                'currency' => 'MGA',
                'required' => false,
                'attr' => [
                    'min' => 0,
                    'placeholder' => '0,00',
                    'class' => 'form-control',
                ],
            ])
            ->add('canal', ChoiceType::class, [
                'label' => 'Canal',
                'choices' => [
                    'En ligne' => 'en_ligne',
                    'Guichet' => 'guichet',
                    'Téléphone' => 'telephone',
                ],
                'placeholder' => 'Sélectionnez un canal',
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Request::class,
        ]);
    }
}
