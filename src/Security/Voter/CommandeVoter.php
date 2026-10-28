<?php

namespace App\Security\Voter;

use App\Entity\Client;
use App\Entity\Commande;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Vote;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

final class CommandeVoter extends Voter
{
    public const VIEW = 'COMMANDE_VIEW';

    protected function supports(string $attribute, mixed $subject): bool
    {
        return $attribute === self::VIEW && $subject instanceof Commande;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token, ?Vote $vote = null): bool
    {
        $user = $token->getUser();

        if (!$user instanceof Client) {
            return false;
        }

        // un client peut voir une commande seulement si c'est la sienne
        return $subject->getClient()->getId() === $user->getId();
    }
}
