<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Product;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Doctrine\ORM\EntityManagerInterface;
final class ProductController extends AbstractController
{
    #[Route('/', name: 'app_products')]
    public function index(ProductRepository $productRepository): Response
    {
        $products = $productRepository->findAll();
    
        return $this->render('product/index.html.twig', [
            'products' => $products,
        ]);
    }
    #[Route('/product/{id}', name: 'app_one_product')]
    public function showProduct(Product $product): Response
    {
        return $this->render('product/product.html.twig', [
            'product' => $product,
        ]);
    }

    #[Route('/product/add/{id}', name: 'app_product_add', methods: ['POST'])]
    public function addToCart($id, SessionInterface $session, EntityManagerInterface $entityManager): Response
    {
        $product = $entityManager->getRepository(Product::class)->find($id);

        if (!$product) {
            throw $this->createNotFoundException('Produit introuvable');
        }

        $cart = $session->get('cart', []);

        if (isset($cart[$id])) {
            $cart[$id]['quantity']++;
        } else {
            $cart[$id] = [
                'name' => $product->getName(),
                'price' => $product->getPrice(),
                'quantity' => 1,
                'image' => $product->getImage(),
            ];
        }

        $session->set('cart', $cart);

        return $this->redirectToRoute('app_cart_show');
    }
}
