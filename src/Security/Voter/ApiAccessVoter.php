<?php

namespace App\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use App\Entity\User;

class ApiAccessVoter extends Voter
{
    public const API_ACCESS = 'API_ACCESS';

    protected function supports(string $attribute, mixed $subject): bool
    {
        return $attribute === self::API_ACCESS;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();


        if (!$user instanceof User) {
            return false;
        }

        // Vérifier si api_active est vrai
        return $user->isApiActive();
    }
}
