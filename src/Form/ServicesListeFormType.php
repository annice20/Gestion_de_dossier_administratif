<?php

namespace App\Form;

use App\Entity\Attachment;
use App\Entity\Decision;
use App\Entity\Document;
use App\Entity\Payment;
use App\Entity\Request;
use App\Entity\Task;
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
                'label' => 'Référence'
            ])
            ->add('demandeurs', TextType::class, [ // Add this field
                'label' => 'Demandeur',
                'required' => false,
            ])
            ->add('statut', ChoiceType::class, [
                'label' => 'Statut',
                'choices' => [
                    'Nouveau' => 'nouveau',
                    'En cours' => 'en_cours',
                    'Traité' => 'traite',
                ],
            ])
            ->add('centre', TextType::class, [
                'label' => 'Centre'
            ])
            ->add('priorite', ChoiceType::class, [
                'label' => 'Priorité',
                'choices' => [
                    'Normale' => 'normale',
                    'Urgente' => 'urgente',
                ],
            ])
            ->add('montant', MoneyType::class, [
                'label' => 'Montant',
                'currency' => 'MGA',
                'required' => false,
            ])
            ->add('canal', ChoiceType::class, [
                'label' => 'Canal',
                'choices' => [
                    'En ligne' => 'en_ligne',
                    'Guichet' => 'guichet',
                    'Téléphone' => 'telephone',
                ],
            ])
         //   ->add('attachment', EntityType::class, [
         //       'class' => Attachment::class,
         //       'choice_label' => 'name', // Changed to display a meaningful field
         //       'required' => false,
         //   ])
         //   ->add('document', EntityType::class, [
         //       'class' => Document::class,
         //       'choice_label' => 'name', // Changed to display a meaningful field
         //       'required' => false,
         //   ])
         //   ->add('task', EntityType::class, [
         //       'class' => Task::class,
         //       'choice_label' => 'name', // Changed to display a meaningful field
         //       'required' => false,
         //   ])
         //   ->add('payment', EntityType::class, [
         //       'class' => Payment::class,
         //       'choice_label' => 'type', // Changed to display a meaningful field
         //       'required' => false,
         //   ])
         //   ->add('decision', EntityType::class, [
         //       'class' => Decision::class,
         //       'choice_label' => 'type', // Changed to display a meaningful field
         //       'required' => false,
         //   ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Request::class,
        ]);
    }
}