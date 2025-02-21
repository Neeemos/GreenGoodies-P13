<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ProductController extends AbstractController
{
    #[Route('/home', name: 'app_products')]
    public function index(): Response
    {
        return $this->render('product/index.html.twig', [
            'controller_name' => 'ProductController',
        ]);
    }
    #[Route('/product/{id}', name: 'app_one_product')]
    public function showProduct($id): Response
    {
        return $this->render('product/product.html.twig', [
            'controller_name' => 'OrderController',
        ]);
    }
}
