<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class RegisterUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => "indiquez votre adresse email"
                ]
            ])
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'constraints' => [
                    new Length([
                        'min' => 4,
                        'max' => 20
                        ])
                    ], 
                'first_options'  => [
                    'label' => false,
                    'attr' => [
                    'placeholder' => "Choisissez votre mot de passe"
                    ],
                    'hash_property_path' => 'password'],
                'second_options' => [
                    'label' => false,
                    'attr' => [
                    'placeholder' => "Choisissez votre mot de passe"
                    ],
                ],
                'mapped' => false,
            ])
            ->add('firstName', TextType::class, [
                'label' => false,
                'constraints' => [
                    new Length([
                        'min' => 2,
                        'max' => 30
                        ])
                    ],               
                'attr' => [
                    'placeholder' => "indiquez votre prenom"
                ]
            ])
            ->add('lastName', TextType::class, [
                'label' => false,
                'constraints' => [
                    new Length([
                        'min' => 2,
                        'max' => 30
                        ])
                    ], 
                'attr' => [
                    'placeholder' => "indiquez votre nom"
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => "Valider",
                'attr' => [
                    'class' => "btn btn-success"
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'constraints' => [
                    new UniqueEntity([
                        'entityClass' => User::class,
                        'fields' => 'email'
                        ])
                    ],
            'data_class' => User::class,
        ]);
    }
}
