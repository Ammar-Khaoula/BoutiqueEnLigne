<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class PasswordUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('actualPassword', PasswordType::class,[
                'label' => "votre mot de passe actuel",
                'attr' => [
                    'placeholder' => "indiquer votre mot de passe"
                ],
                'mapped' => false,
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
                    'label' => 'mot de passe',
                    'attr' => [
                    'placeholder' => "nouveau mot de passe"
                    ],
                    'hash_property_path' => 'password'],
                'second_options' => [
                    'label' => 'confirmer votre nouveau mot de passe',
                    'attr' => [
                    'placeholder' => "Choisissez votre mot de passe"
                    ],
                ],
                'mapped' => false,
            ])
            ->add('submit', SubmitType::class, [
                'label' => "mettre a jour votre mot de passe",
                'attr' => [
                    'class' => "btn btn-success"
                ]
            ])
            //a quel moment je veut ecouter et quesque je veux faire
            ->addEventListener(FormEvents::SUBMIT, function(FormEvent $event){
                //chercher formulaire
                $form = $event->getForm();
                //chercher notre user actuel
                $user = $form->getConfig()->getOptions()['data'];
                //chercher et verifier encodage de MP
                $passwordHasher = $form->getConfig()->getOptions()['passwordHasher'];

                //1-recuprer MP saisie par l'utilisateur
                $actualPwd = $form->get('actualPassword')->getData();

                //2- recuperer MP actuel en BDD et le comparee             
                $isValid= $passwordHasher->isPasswordValid(
                    $user,
                    $form->get('actualPassword')->getData()
                );

                //3- si c'est != envoyer une erreur
                if(!$isValid){
                    $form->get('actualPassword')->addError(new FormError("votre mot de passe actuel n'est pas conforme. veuillez vérifier votre saisie."));
                }
            })
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'passwordHasher' => null
        ]);
    }
}
