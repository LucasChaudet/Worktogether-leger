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
        // Incident en cours sur l'unité bdd-principale
        $incident = new Intervention();
        $incident->setType(Intervention::TYPE_INCIDENT);
        $incident->setDescription('Perte d\'alimentation électrique sur le bandeau B.');
        $incident->setDateHeureDebut(new \DateTimeImmutable('-2 hours'));
        $incident->addUnite($this->getUniteDemo(3));
        $manager->persist($incident);

        // Maintenance en cours sur l'unité stockage-02
        $maintenance = new Intervention();
        $maintenance->setType(Intervention::TYPE_MAINTENANCE);
        $maintenance->setDescription('Remplacement préventif des ventilateurs.');
        $maintenance->setDateHeureDebut(new \DateTimeImmutable('-1 hour'));
        $maintenance->addUnite($this->getUniteDemo(6));
        $manager->persist($maintenance);

        // Maintenance terminée sur les 4 premières unités
        $miseAJour = new Intervention();
        $miseAJour->setType(Intervention::TYPE_MAINTENANCE);
        $miseAJour->setDescription('Mise à jour du firmware des commutateurs.');
        $miseAJour->setDateHeureDebut(new \DateTimeImmutable('-10 days 22:00'));
        $miseAJour->setDateHeureFin(new \DateTimeImmutable('-10 days 23:30'));
        for ($i = 0; $i < 4; ++$i) {
            $miseAJour->addUnite($this->getUniteDemo($i));
        }
        $manager->persist($miseAJour);

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [CommandeFixtures::class];
    }

    private function getUniteDemo(int $i): Unite
    {
        return $this->getReference(CommandeFixtures::referenceUniteDemo($i), Unite::class);
    }
}
