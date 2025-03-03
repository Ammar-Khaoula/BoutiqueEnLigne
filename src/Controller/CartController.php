<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Message;
use Symfony\Component\Routing\Attribute\Route;

use function PHPSTORM_META\type;

class CartController extends AbstractController
{
    //#[Route('/panier/{motif}', name: 'app_cart', defaults: [ 'motif' => null ])]
    #[Route('/panier', name: 'app_cart')]
    public function index(Cart $cart): Response
    {
        /*if($motif == "annulation"){
            $this->addFlash(
                type: 'info',
                message: 'paiement annulé : vous pouvez mettre à jour votre panier et votre commande.'
            );
        }*/
        return $this->render('cart/index.html.twig', [
            'cart' => $cart->getCart(),
            'TotalWt'=> $cart->getTotalWt()
        ]);
    }

    #[Route('/panier/{id}', name: 'app_cart_add')]
    public function add($id, Cart $cart, ProductRepository $productRepository, Request $request): Response
    {
        $product = $productRepository->findOneById($id);
        
        $cart->add($product);
        $this->addFlash(
            type: 'success',
            message: "produit corectement ajouté à votre panier."
        );
        return $this->redirect($request->headers->get('referer'));
    }

    #[Route('/cart/decrease/{id}', name: 'app_cart_decrease')]
    public function decrease($id, Cart $cart): Response
    {        
        $cart->decrease($id);

        $this->addFlash(
            type: 'success',
            message: "produit corectement suprimer à votre panier."
        );
        return $this->redirectToRoute('app_cart');
    }

    #[Route('/cart/remove', name: 'app_cart_remove')]
    public function remove(Cart $cart): Response
    {
        $cart->remove();
        return $this->redirectToRoute('app_home');
    }
}