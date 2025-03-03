<?php

namespace App\Controller;

use App\Classe\Mail;
use App\Entity\User;
use App\Form\RegisterUserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RegisterController extends AbstractController
{
    #[Route('/inscription', name: 'app_register')]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(RegisterUserType::class, $user);

        $form->handleRequest($request);


        if ($form->isSubmitted()&& $form->isValid()){
            $entityManager->persist($user);
            $entityManager->flush();
            //pour le message de flash
            $this->addFlash(
                'success',
                'votre compte est correctement crée, veuillez vous connectez.'
            );

            //envoie un mail de comfirmation de connexion
            $mail = new Mail();
            $vars =[
                'firstName' => $user->getFirstName()
            ];
            $mail->send($user->getEmail(), $user->getFirstName(). ' '.$user->getLastName(), 'Bienvenue sur votre boutique', 'welcome.html', $vars);

            //retourne ver le page login
            return $this->redirectToRoute('app_login');
        }
        return $this->render('register/index.html.twig', [
            'registerForm'=> $form->createView()
        ]);
    }
}
