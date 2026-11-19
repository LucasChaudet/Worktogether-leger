<?php

namespace App\Form;

use App\Entity\Commande;
use App\Entity\Offre;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommandeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // Le client choisit seulement son offre et son type de paiement
        $builder
            ->add('offre', EntityType::class, [
                'class' => Offre::class,
                'choice_label' => 'libelle',
                'placeholder' => 'Choisir une offre',
            ])
            ->add('annuel', CheckboxType::class, [
                'label' => 'Paiement annuel (10 % de remise en plus)',
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Commande::class,
        ]);
    }
}
