<?php
// src/Security/CustomAuthenticationSuccessHandler.php

namespace App\Security;

use Lexik\Bundle\JWTAuthenticationBundle\Security\Http\Authentication\AuthenticationSuccessHandler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use App\Entity\User;

class CustomAuthenticationSuccessHandler extends AuthenticationSuccessHandler
{
    public function onAuthenticationSuccess(Request $request, TokenInterface $token): Response
    {
        $user = $token->getUser();
        if (!$user instanceof User || !$user->isApiActive()) {
            return new Response(
                json_encode(['message' => 'Accès API non autorisé']),
                Response::HTTP_FORBIDDEN,
                ['Content-Type' => 'application/json']
            );
          
        }

        $response = parent::onAuthenticationSuccess($request, $token);
        
        // Optionnel : Ajouter des données supplémentaires
        $data = json_decode($response->getContent(), true);
        $data['user'] = [
            'email' => $user->getEmail(),
            'roles' => $user->getRoles()
        ];
        
        $response->setContent(json_encode($data));
        
        return $response;
    }
}