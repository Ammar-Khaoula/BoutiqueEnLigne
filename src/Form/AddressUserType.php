<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Address;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class AddressUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstName', TextType::class, [
                'label' => false,             
                'attr' => [
                    'placeholder' => "indiquez votre Prenom"
                ]
            ])
            ->add('lastName', TextType::class, [
                'label' => false,             
                'attr' => [
                    'placeholder' => "indiquez votre Nom"
                ]
            ])
            ->add('address', TextType::class, [
                'label' => false,             
                'attr' => [
                    'placeholder' => "indiquez votre adresse"
                ]
            ])
            ->add('postal', TextType::class, [
                'label' => false,             
                'attr' => [
                    'placeholder' => "indiquez votre code postal"
                ]
            ])
            ->add('city', TextType::class, [
                'label' => false,             
                'attr' => [
                    'placeholder' => "indiquez votre ville"
                ]
            ])
            ->add('country', TextType::class, [
                'label' => false,             
                'attr' => [
                    'placeholder' => "indiquez votre pays"
                ]
            ])
            ->add('phone', TextType::class, [
                'label' => false,             
                'attr' => [
                    'placeholder' => "indiquez votre pnuméro de téléphone"
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => "Enregistrer",
                'attr' => [
                    'class' => "btn btn-success"
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Address::class,
        ]);
    }
}
