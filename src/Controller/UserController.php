<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use App\Entity\Order;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
final class UserController extends AbstractController
{
    private $security;


    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    #[Route('/profile', name: 'app_profile')]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function index(EntityManagerInterface $entityManager, Security $security)
    {
        $user = $this->security->getUser();

        if (!$user) {
            throw $this->createAccessDeniedException("Vous devez être connecté pour voir vos commandes.");
        }

        $orders = $entityManager->getRepository(Order::class)->findBy([
            'user_id' => $user
        ]);

        return $this->render('profile/index.html.twig', [
            'orders' => $orders,
        ]);
    }
    #[Route('/profile/api/', name: 'app_profile_api', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function toggleApiAccess(EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();

        if (!$user) {
            $this->addFlash('error', 'Vous devez être connecté.');
            return $this->redirectToRoute('app_login');
        }

        $apiActive = $user->isApiActive();
        $user->setApiActive(!$apiActive); 

        if ($user->isApiActive()) {
            $this->addFlash('success', 'Accès API activé.');
        } else {
            $this->addFlash('success', 'Accès API désactivé.');
        }

        $entityManager->persist($user);
        $entityManager->flush();

        return $this->redirectToRoute('app_profile');
    }

    #[Route('/profile/delete', name: 'app_profile_delete', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function deleteProfile(EntityManagerInterface $entityManager, Request $request): Response
    {
        $user = $this->getUser();

        if (!$user) {
            $this->addFlash('error', 'Vous devez être connecté pour supprimer votre compte.');
            return $this->redirectToRoute('app_login');
        }

        $user->setEmail('anonymized_' . uniqid() . '@example.com');
        $user->setPassword(uniqid()); 
        $user->setName('Anonyme');
        $user->setSurname('Anonyme');

        $entityManager->persist($user);
        $entityManager->flush();

        $request->getSession()->invalidate();
        $this->container->get('security.token_storage')->setToken(null);

        $this->addFlash('success', 'Votre compte a été supprimer avec succès.');
        return $this->redirectToRoute('app_products');
    }

}
