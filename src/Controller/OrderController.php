<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class OrderController extends AbstractController
{
    #[Route('/cart', name: 'app_cart_show')]
    public function showOrder(): Response
    {
        return $this->render('order/index.html.twig', [
            'controller_name' => 'OrderController'
        ]);
    }

    #[Route('/order/add', name: 'app_orders_add')]
    public function add(): Response {
        return $this->redirectToRoute('app_cart_show');
    }
    
    #[Route('/profile', name: 'app_profile')]
    public function showProfile(): Response {
        return $this->render('profile/index.html.twig', [
            'controller_name' => 'Profile',
        ]);
    }

}
