<?php

namespace App\DataFixtures;

use App\Entity\Offre;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class OffreFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $offres = [
            ['libelle' => 'Base', 'nbUnites' => 1, 'prixMensuel' => 100, 'remise' => 0],
            ['libelle' => 'Start-up', 'nbUnites' => 10, 'prixMensuel' => 900, 'remise' => 10],
            ['libelle' => 'PME', 'nbUnites' => 21, 'prixMensuel' => 1680, 'remise' => 20],
            ['libelle' => 'Entreprise', 'nbUnites' => 42, 'prixMensuel' => 2940, 'remise' => 30],
        ];

        foreach ($offres as $data) {
            $offre = new Offre();
            $offre->setLibelle($data['libelle']);
            $offre->setNbUnites($data['nbUnites']);
            $offre->setPrixMensuel($data['prixMensuel']);
            $offre->setRemise($data['remise']);
            $manager->persist($offre);
            $this->addReference('offre_' . $data['libelle'], $offre);
        }

        $manager->flush();
    }
}
