<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Entity\Order;
use App\Entity\OrderDetail;
use App\Form\OrderType;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class OrderController extends AbstractController
{
    /*
    *1ere etape du tenel d'achat
    * chois de adresse de livraison et du transporteur */
    #[Route('/commande/livraison', name: 'app_order')]
    public function index(): Response
    {
        $addresses = $this->getUser()->getAddresses();

        if(count($addresses) == 0) {
            return $this->redirectToRoute('app_account_address_form');
        }
        $form = $this->createForm(OrderType::class, null, [
            'addresses' => $addresses,
            'action' => $this->generateUrl('app_order_summary')
        ]);

        return $this->render('order/index.html.twig', [
            'deliverForm' =>$form->createView(),
        ]);
    }

    /*
    *2eme etape du tunnel d'achat
    *récap de la commende de l'utilisateur
    *insertion en base de donnée
    *préparation de paiment vers script*/
    #[Route('/commande/recapitulatif', name: 'app_order_summary')]
    public function add(Request $request, Cart $cart, EntityManagerInterface $entityManager): Response
    {

        if($request->getMethod() != 'POST'){
            return $this->redirectToRoute('app_cart');
        }
        $products = $cart->getCart();

        $form = $this->createForm(OrderType::class, null, [
            'addresses' => $this->getUser()->getAddresses()
        ]);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){            
                //stoquer les information dans BDD
                //creation de la chaine adresse
                $addressObject = $form->get('addresses')->getData();
                
                $address = $addressObject->getFirstName(). ' '.$addressObject->getlastName().'</br>';
                $address .= $addressObject->getAddress(). '</br>';
                $address .= $addressObject->getPostal(). ' '.$addressObject->getCity().'</br>';
                $address .= $addressObject->getCountry(). '</br>';
                $address .= $addressObject->getPhone();

                $order = new Order();
                $order->setUser($this->getUser());
                $order->setCreatedAt(new \DateTime());
                $order->setState(1);
                $order->setCarrierName($form->get('carriers')->getData()->getName());
                $order->setCarrierPrice($form->get('carriers')->getData()->getPrice());
                $order->setDelivery($address);

                foreach($products as $product){
                    
                    $orderDetail = new OrderDetail();
                    $orderDetail->setProductName($product['object']->getName());
                    $orderDetail->setProductIllustration($product['object']->getImages());
                    $orderDetail->setProductPrice($product['object']->getPrix());
                    $orderDetail->setProductTva($product['object']->getTva());
                    $orderDetail->setProductQuantity($product['qty']);
                    $order->addOrderDetail($orderDetail);
                }
                
                $entityManager->persist($order);
                $entityManager->flush();
        } 

        return $this->render('order/summary.html.twig', [
            'choices' => $form->getData(),
            'cart' => $products, 
            'order' => $order,
            'TotalWt'=> $cart->getTotalWt()
        ]);
    }
}
