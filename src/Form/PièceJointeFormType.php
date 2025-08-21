<?php

namespace App\Form;

use App\Entity\Attachment;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;  
use Symfony\Component\Form\Extension\Core\Type\IntegerType; 

class AttachmentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('type_piece', TextType::class, ['label' => 'Type de pièce'])
            ->add('nom_fichier', TextType::class, ['label' => 'Nom du fichier'])
            ->add('hash', TextType::class, ['label' => 'Hash'])
            ->add('url', TextType::class, ['label' => 'URL'])
            ->add('taille', IntegerType::class, ['label' => 'Taille'])
            ->add('verif_statut', TextType::class, ['label' => 'Statut de vérification']);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Attachment::class,
        ]);
    }
}