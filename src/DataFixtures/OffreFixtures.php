<?php

namespace App\DataFixtures;

use App\Entity\Offre;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

/**
 * Offres commerciales du sujet.
 */
class OffreFixtures extends Fixture
{
    /** libellé => [nombre d'unités, prix mensuel, remise %] */
    public const OFFRES = [
        'Base' => [1, 100, 0],
        'Start-up' => [10, 900, 10],
        'PME' => [21, 1680, 20],
        'Entreprise' => [42, 2940, 30],
    ];

    public function load(ObjectManager $manager): void
    {
        foreach (self::OFFRES as $libelle => [$nbUnites, $prix, $remise]) {
            $offre = (new Offre())
                ->setLibelle($libelle)
                ->setNbUnites($nbUnites)
                ->setPrixMensuel($prix)
                ->setRemise($remise);
            $manager->persist($offre);
            $this->addReference(self::reference($libelle), $offre);
        }

        $manager->flush();
    }

    public static function reference(string $libelle): string
    {
        return 'offre_'.$libelle;
    }
}
