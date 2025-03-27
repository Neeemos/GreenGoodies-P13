<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Entity\Order;
use App\Entity\OrderProduct;
use App\Repository\ProductRepository;
use DateTime;
use Symfony\Component\Security\Http\Attribute\IsGranted;
final class OrderController extends AbstractController
{

    #[Route('/cart', name: 'app_cart_show')]
    #[IsGranted('ROLE_USER')]
    public function showOrder(SessionInterface $session): Response
    {
        $cart = $session->get('cart', []);
        return $this->render('order/index.html.twig', [
            'cart' => $cart
        ]);
    }
    #[Route('/cart/clear', name: 'app_cart_clear')]
    public function clearCart(SessionInterface $session): Response
    {
        $session->remove('cart');
        return $this->redirectToRoute('app_cart_show');
    }
    #[Route('/order/add/', name: 'app_order_add', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function addOrder(SessionInterface $session, EntityManagerInterface $entityManager, ProductRepository $productRepository): Response
    {
        $cart = $session->get('cart', []);
    
        if (empty($cart)) {
            $this->addFlash('error', 'Votre panier est vide.');
            return $this->redirectToRoute('app_cart_show');
        }
    
        $totalPrice = 0;
        foreach ($cart as $productId => $product) {
            $totalPrice += $product['price'] * $product['quantity'];
        }
    
        $user = $this->getUser();
        if (!$user) {
            $this->addFlash('error', 'Vous devez être connecté pour passer une commande.');
            return $this->redirectToRoute('app_login');
        }
    
        $order = new Order();
        $order->setUserId($user);
        $order->setPrice($totalPrice);
        $order->setDate(new DateTime());
    
        $entityManager->persist($order);
        $entityManager->flush();
    
        foreach ($cart as $productId => $product) {
            $productEntity = $productRepository->find($productId);
            if (!$productEntity) {
                $this->addFlash('error', 'Un produit dans votre panier n\'existe plus.');
                continue;
            }
    
            $orderProduct = new OrderProduct();
            $orderProduct->setOrderId($order); 
            $orderProduct->setProduct($productEntity); 
            $orderProduct->setQuantity($product['quantity']); 

            $entityManager->persist($orderProduct);
        }
    
    
        $entityManager->flush();
    

        $session->remove('cart');
    
    
        $this->addFlash('success', 'Votre commande a été enregistrée avec succès.');
        return $this->redirectToRoute('app_profile');
    }
}