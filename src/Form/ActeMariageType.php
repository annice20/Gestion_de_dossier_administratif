<?php

namespace App\Form;

use App\Entity\ActeMariage;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ActeMariageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('commune')
            ->add('dateMariage')
            ->add('heureMariage')
            ->add('numeroActe')
            ->add('officierNom')
            ->add('officierTitre')
            ->add('epouxNom')
            ->add('epouxPrenom')
            ->add('epouxDateNaissance')
            ->add('epouxLieuNaissance')
            ->add('epouxPereNom')
            ->add('epouxPerePrenom')
            ->add('epouxMereNom')
            ->add('epouxMerePrenom')
            ->add('epouxProfession')
            ->add('epouxDomicile')
            ->add('epouseNom')
            ->add('epousePrenom')
            ->add('epouseDateNaissance')
            ->add('epouseLieuNaissance')
            ->add('epousePereNom')
            ->add('epousePerePrenom')
            ->add('epouseMereNom')
            ->add('epouseMerePrenom')
            ->add('epouseProfession')
            ->add('epouseDomicile')
            ->add('temoins')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ActeMariage::class,
        ]);
    }
}
