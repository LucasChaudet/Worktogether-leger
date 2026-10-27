<?php

namespace App\Tests\Entity;

use App\Entity\Intervention;
use App\Entity\Unite;
use PHPUnit\Framework\TestCase;

class InterventionTest extends TestCase
{
    public function testValeursParDefaut(): void
    {
        $intervention = new Intervention();

        $this->assertNull($intervention->getId());
        $this->assertNull($intervention->getType());
        $this->assertNull($intervention->getDescription());
        $this->assertNull($intervention->getDateHeureDebut());
        $this->assertNull($intervention->getDateHeureFin());
        $this->assertCount(0, $intervention->getUnites());
    }

    public function testGettersEtSetters(): void
    {
        $debut = new \DateTimeImmutable('2026-03-01 08:00');
        $fin = new \DateTimeImmutable('2026-03-01 12:00');

        $intervention = (new Intervention())
            ->setType(Intervention::TYPE_MAINTENANCE)
            ->setDescription('Remplacement de l\'alimentation')
            ->setDateHeureDebut($debut)
            ->setDateHeureFin($fin);

        $this->assertSame(Intervention::TYPE_MAINTENANCE, $intervention->getType());
        $this->assertSame('Remplacement de l\'alimentation', $intervention->getDescription());
        $this->assertSame($debut, $intervention->getDateHeureDebut());
        $this->assertSame($fin, $intervention->getDateHeureFin());

        $intervention->setDateHeureFin(null);
        $this->assertNull($intervention->getDateHeureFin());
    }

    public function testTypes(): void
    {
        $this->assertSame([Intervention::TYPE_INCIDENT, Intervention::TYPE_MAINTENANCE], Intervention::TYPES);
    }

    public function testAddEtRemoveUnite(): void
    {
        $intervention = new Intervention();
        $unite = new Unite();

        $intervention->addUnite($unite)->addUnite($unite);
        $this->assertCount(1, $intervention->getUnites());

        $intervention->removeUnite($unite);
        $this->assertCount(0, $intervention->getUnites());
    }
}
