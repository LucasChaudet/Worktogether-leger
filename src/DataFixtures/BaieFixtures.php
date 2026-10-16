<?php

namespace App\DataFixtures;

use App\Entity\Baie;
use App\Entity\Unite;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

/**
 * 30 baies (B001 à B030) de 42 unités chacune (U01 à U42).
 */
class BaieFixtures extends Fixture
{
    private const NB_BAIES = 30;

    public function load(ObjectManager $manager): void
    {
        for ($b = 1; $b <= self::NB_BAIES; ++$b) {
            $baie = (new Baie())->setNumero(sprintf('B%03d', $b));
            for ($u = 1; $u <= Baie::NB_UNITES; ++$u) {
                $baie->addUnite((new Unite())->setNumero($u));
            }
            $manager->persist($baie);
        }

        $manager->flush();
    }
}
