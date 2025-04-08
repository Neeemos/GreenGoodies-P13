<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Product;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

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
    public function showProduct(Product $product, SessionInterface $session): Response
    {
        $cart = $session->get('cart', []);

        $quantityInCart = 0;
        $isInCart = false;

        if (isset($cart[$product->getId()])) {
            $quantityInCart = $cart[$product->getId()]['quantity']; // Accès à la quantité via la structure de votre panier
            $isInCart = true;
        }

        return $this->render('product/product.html.twig', [
            'product' => $product,
            'quantityInCart' => $quantityInCart,
            'isInCart' => $isInCart,
        ]);
    }

    #[Route('/product/add/{id}', name: 'app_product_add', methods: ['POST'])]
    public function addToCart(
        $id,
        Request $request, // Ajoutez ce paramètre
        SessionInterface $session,
        EntityManagerInterface $entityManager
    ): Response {
        $product = $entityManager->getRepository(Product::class)->find($id);

        if (!$product) {
            throw $this->createNotFoundException('Produit introuvable');
        }

        $quantity = $request->request->getInt('quantity', 1);
        $cart = $session->get('cart', []);
        $isInCart = isset($cart[$id]);

        if ($quantity <= 0) {
            if ($isInCart) {
                unset($cart[$id]);
                $this->addFlash('success', 'Produit retiré du panier');
            }
        } else {
            if ($isInCart) {
                $cart[$id]['quantity'] = $quantity;
                $message = 'Quantité mise à jour';
            } else {
                $cart[$id] = [
                    'name' => $product->getName(),
                    'price' => $product->getPrice(),
                    'quantity' => $quantity,
                    'image' => $product->getImage(),
                ];
                $message = 'Produit ajouté au panier';
            }
            $this->addFlash('success', $message);
        }

        $session->set('cart', $cart);
        $this->addFlash('success', $isInCart ? 'Quantité mise à jour' : 'Produit ajouté au panier');

        return $this->redirectToRoute('app_cart_show');
    }
}
