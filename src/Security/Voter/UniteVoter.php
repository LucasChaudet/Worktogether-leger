<?php

namespace App\Security\Voter;

use App\Entity\Client;
use App\Entity\Unite;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Vote;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

final class UniteVoter extends Voter
{
    public const VIEW = 'UNITE_VIEW';
    public const EDIT = 'UNITE_EDIT';

    protected function supports(string $attribute, mixed $subject): bool
    {
        return in_array($attribute, [self::VIEW, self::EDIT])
            && $subject instanceof Unite;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token, ?Vote $vote = null): bool
    {
        $user = $token->getUser();

        // if the user is anonymous or is not a client, do not grant access
        if (!$user instanceof Client) {
            return false;
        }

        // un client peut voir et modifier une unité seulement s'il la loue en ce moment
        switch ($attribute) {
            case self::VIEW:
            case self::EDIT:
                return $subject->getCommandeEnCours()?->getClient() === $user;
        }

        return false;
    }
}
