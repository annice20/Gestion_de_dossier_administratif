<?php

namespace App\Form;

use App\Entity\Attachment;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;  
use Symfony\Component\Form\Extension\Core\Type\IntegerType; 
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;  
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class AttachmentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('type_piece', ChoiceType::class, [
                'label' => 'Type de pièce',
                'choices' => [
                    'Carte d\'identité (CIN)' => 'CIN',
                    'Passeport' => 'Passeport',
                    'Permis de conduire' => 'Permis',
                    'Acte de naissance' => 'Naissance',
                    'Certificat de résidence' => 'Residence',
                    'Carte électorale' => 'Electorale',
                    'Carte professionnelle' => 'Professionnelle',
                    'Carte d\'étudiant' => 'Etudiant',
                    'Facture d\'électricité' => 'Facture_Electricite',
                    'Facture d\'eau' => 'Facture_Eau',
                    'Facture téléphonique' => 'Facture_Telephone',
                    'Relevé bancaire' => 'Releve_Bancaire',
                    'Bulletin de salaire' => 'Bulletin_Salaire',
                    'Diplôme' => 'Diplome',
                    'Attestation de travail' => 'Attestation_Travail',
                    'Contrat de bail' => 'Contrat_Bail',
                    'Quittance de loyer' => 'Quittance_Loyer',
                    'Autre document' => 'Autre',
                ],
                'placeholder' => '--- Sélectionnez un type de pièce ---',
            ])
            ->add('file', FileType::class, [
                'label' => 'Fichier',
                'required' => true,
                'mapped' => false,
            ])
            ->add('nom_fichier', TextType::class, [
                'label' => 'Nom du fichier',
                'required' => false,
                'attr' => ['readonly' => true],
            ])
            ->add('hash', TextType::class, ['label' => 'Hash', 'required' => false])
            ->add('url', TextType::class, ['label' => 'URL', 'required' => false])
            ->add('taille', IntegerType::class, [
                'label' => 'Taille (en octets)',
                'required' => false,
                'attr' => ['readonly' => true],
            ])
            ->add('verif_statut', CheckboxType::class, [
                'label' => 'Statut de vérification',
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Attachment::class,
        ]);
    }
}
