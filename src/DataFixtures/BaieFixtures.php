<?php

namespace App\DataFixtures;

use App\Entity\Baie;
use App\Entity\Unite;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class BaieFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // 30 baies : B001, B002, ... B030
        for ($b = 1; $b <= 30; $b++) {
            $baie = new Baie();
            $baie->setNumero('B' . str_pad($b, 3, '0', STR_PAD_LEFT));
            $manager->persist($baie);

            // 42 unités par baie : U01, U02, ... U42
            for ($u = 1; $u <= 42; $u++) {
                $unite = new Unite();
                $unite->setNumero($u);
                $baie->addUnite($unite);
                $manager->persist($unite);

                // exemple de référence : unite_B001_1
                $this->addReference('unite_' . $baie->getNumero() . '_' . $u, $unite);
            }
        }

        $manager->flush();
    }
}
