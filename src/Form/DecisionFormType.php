<?php

namespace App\Form;

use App\Entity\Decision;
use App\Entity\Request as RequestEntity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class CombinedDecisionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // Champs de la Décision
        $builder
            ->add('request', EntityType::class, [
                'class' => RequestEntity::class,
                'choice_label' => 'ref', // Utilisons 'ref' pour un affichage plus parlant
                'label' => 'Demande associée :',
                'placeholder' => 'Sélectionner une demande',
            ])
            ->add('resultat', TextType::class, [
                'label' => 'Résultat :',
            ])
            ->add('motif', TextType::class, [
                'label' => 'Motif :',
            ])
            ->add('validePar', TextType::class, [ // Changement de 'valide_par' à 'validePar'
                'label' => 'Validé par :',
            ])
            ->add('valideLe', DateType::class, [ // Changement de 'valide_le' à 'valideLe'
                'label' => 'Validé le :',
                'widget' => 'single_text',
            ])
            // Imbrication du formulaire de signature
            ->add('signature', SignatureType::class, [
                'label' => 'Signature',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Decision::class,
        ]);
    }
}
