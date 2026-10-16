<?php

namespace App\DataFixtures;

use App\Entity\Intervention;
use App\Entity\Unite;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

/**
 * Interventions (incidents et maintenances) sur les unités du client de démonstration.
 */
class InterventionFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $unite = fn (int $i): Unite => $this->getReference(CommandeFixtures::referenceUniteDemo($i), Unite::class);

        foreach ([
            [Intervention::TYPE_INCIDENT, 'Perte d\'alimentation électrique sur le bandeau B.', '-2 hours', null, [3]],
            [Intervention::TYPE_MAINTENANCE, 'Remplacement préventif des ventilateurs.', '-1 hour', null, [6]],
            [Intervention::TYPE_MAINTENANCE, 'Mise à jour du firmware des commutateurs.', '-10 days 22:00', '-10 days 23:30', [0, 1, 2, 3]],
        ] as [$type, $description, $debut, $fin, $unites]) {
            $intervention = (new Intervention())
                ->setType($type)
                ->setDescription($description)
                ->setDateHeureDebut(new \DateTimeImmutable($debut))
                ->setDateHeureFin(null !== $fin ? new \DateTimeImmutable($fin) : null);
            foreach ($unites as $i) {
                $intervention->addUnite($unite($i));
            }
            $manager->persist($intervention);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [CommandeFixtures::class];
    }
}
