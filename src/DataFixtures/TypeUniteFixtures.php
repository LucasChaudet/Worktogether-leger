<?php

namespace App\DataFixtures;

use App\Entity\TypeUnite;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TypeUniteFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $types = [
            ['libelle' => 'Serveur web', 'couleur' => '#1E88E5'],
            ['libelle' => 'Base de données', 'couleur' => '#43A047'],
            ['libelle' => 'Stockage', 'couleur' => '#757575'],
            ['libelle' => 'Réseau', 'couleur' => '#FB8C00'],
        ];

        foreach ($types as $data) {
            $type = new TypeUnite();
            $type->setLibelle($data['libelle']);
            $type->setCouleur($data['couleur']);
            $manager->persist($type);
            $this->addReference('type_' . $data['libelle'], $type);
        }

        $manager->flush();
    }
}
