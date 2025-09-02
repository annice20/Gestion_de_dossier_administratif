<?php

namespace App\Form;

use App\Entity\Signature;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class SignatureType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('type', TextType::class, [
                'label' => 'Type de signature :',
            ])
            ->add('certificat', TextType::class, [
                'label' => 'Certificat :',
            ])
            ->add('scelle', TextType::class, [
                'label' => 'Scellé :',
            ])
            ->add('horodatage', DateType::class, [
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
