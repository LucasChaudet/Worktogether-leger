<?php

namespace App\DataFixtures;

use App\Entity\Role;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class RoleFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $comptable = new Role();
        $comptable->setLibelle('COMPTABLE');
        $manager->persist($comptable);
        $this->addReference('role_COMPTABLE', $comptable);

        // L'administrateur a aussi les droits du comptable
        $administrateur = new Role();
        $administrateur->setLibelle('ADMINISTRATEUR');
        $administrateur->setInclut($comptable);
        $manager->persist($administrateur);
        $this->addReference('role_ADMINISTRATEUR', $administrateur);

        $manager->flush();
    }
}
