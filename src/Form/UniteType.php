<?php

namespace App\Form;

use App\Entity\TypeUnite;
use App\Entity\Unite;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UniteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // Le client peut seulement donner un nom et un type à son unité
        $builder
            ->add('nom', null, [
                'required' => false,
            ])
            ->add('typeUnite', EntityType::class, [
                'class' => TypeUnite::class,
                'choice_label' => 'libelle',
                'label' => 'Type',
                'required' => false,
                'placeholder' => 'Aucun type',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Unite::class,
        ]);
    }
}
