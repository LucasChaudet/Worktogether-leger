<?php

namespace App\DataFixtures;

use App\Entity\Role;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

/**
 * Hiérarchie des rôles du personnel : Administrateur > Comptable.
 */
class RoleFixtures extends Fixture
{
    public const COMPTABLE = 'role_comptable';
    public const ADMINISTRATEUR = 'role_administrateur';

    public function load(ObjectManager $manager): void
    {
        $comptable = (new Role())->setLibelle('COMPTABLE');
        $administrateur = (new Role())->setLibelle('ADMINISTRATEUR')->setInclut($comptable);

        $manager->persist($comptable);
        $manager->persist($administrateur);
        $manager->flush();

        $this->addReference(self::COMPTABLE, $comptable);
        $this->addReference(self::ADMINISTRATEUR, $administrateur);
    }
}
