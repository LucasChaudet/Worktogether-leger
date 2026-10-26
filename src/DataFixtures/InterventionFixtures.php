<?php

namespace App\DataFixtures;

use App\Entity\Intervention;
use App\Entity\Unite;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class InterventionFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $now = new \DateTimeImmutable();

        $interventions = [
            ['type' => 'Incident', 'description' => 'Perte d\'alimentation électrique sur le bandeau B.', 'dateHeureDebut' => $now->modify('-2 hours'), 'dateHeureFin' => null, 'unites' => ['unite_B001_4']],
            ['type' => 'Maintenance', 'description' => 'Remplacement préventif des ventilateurs.', 'dateHeureDebut' => $now->modify('-1 hour'), 'dateHeureFin' => null, 'unites' => ['unite_B001_7']],
            ['type' => 'Maintenance', 'description' => 'Mise à jour du firmware des commutateurs.', 'dateHeureDebut' => $now->modify('-10 days'), 'dateHeureFin' => $now->modify('-10 days +2 hours'), 'unites' => ['unite_B001_1', 'unite_B001_2', 'unite_B001_3', 'unite_B001_4']],
        ];

        foreach ($interventions as $data) {
            $intervention = new Intervention();
            $intervention->setType($data['type']);
            $intervention->setDescription($data['description']);
            $intervention->setDateHeureDebut($data['dateHeureDebut']);
            $intervention->setDateHeureFin($data['dateHeureFin']);
            foreach ($data['unites'] as $reference) {
                $intervention->addUnite($this->getReference($reference, Unite::class));
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
