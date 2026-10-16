<?php

namespace App\DataFixtures;

use App\Entity\TypeUnite;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

/**
 * Types d'unité et leur couleur (serveur web en bleu, base de données en vert...).
 */
class TypeUniteFixtures extends Fixture
{
    public const TYPES = [
        'Serveur web' => '#1E88E5',
        'Base de données' => '#43A047',
        'Stockage' => '#757575',
        'Réseau' => '#FB8C00',
    ];

    public function load(ObjectManager $manager): void
    {
        foreach (self::TYPES as $libelle => $couleur) {
            $type = (new TypeUnite())->setLibelle($libelle)->setCouleur($couleur);
            $manager->persist($type);
            $this->addReference(self::reference($libelle), $type);
        }

        $manager->flush();
    }

    public static function reference(string $libelle): string
    {
        return 'type_unite_'.$libelle;
    }
}
