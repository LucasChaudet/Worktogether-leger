<?php

namespace App\Tests\Entity;

use App\Entity\Baie;
use App\Entity\Unite;
use PHPUnit\Framework\TestCase;

class BaieTest extends TestCase
{
    public function testValeursParDefaut(): void
    {
        $baie = new Baie();

        $this->assertNull($baie->getId());
        $this->assertNull($baie->getNumero());
        $this->assertSame(Baie::NB_UNITES, $baie->getNbUnites());
        $this->assertCount(0, $baie->getUnites());
    }

    public function testGettersEtSetters(): void
    {
        $baie = (new Baie())
            ->setNumero('B014')
            ->setNbUnites(20);

        $this->assertSame('B014', $baie->getNumero());
        $this->assertSame(20, $baie->getNbUnites());
    }

    public function testAddUniteLieLUniteALaBaie(): void
    {
        $baie = new Baie();
        $unite = new Unite();

        $baie->addUnite($unite);

        $this->assertTrue($baie->getUnites()->contains($unite));
        $this->assertSame($baie, $unite->getBaie());
    }

    public function testAddUniteNAjoutePasDeDoublon(): void
    {
        $baie = new Baie();
        $unite = new Unite();

        $baie->addUnite($unite)->addUnite($unite);

        $this->assertCount(1, $baie->getUnites());
    }

    public function testRemoveUnite(): void
    {
        $baie = new Baie();
        $unite = new Unite();
        $baie->addUnite($unite);

        $baie->removeUnite($unite);

        $this->assertCount(0, $baie->getUnites());
    }

    public function testToString(): void
    {
        $this->assertSame('', (string) new Baie());
        $this->assertSame('B001', (string) (new Baie())->setNumero('B001'));
    }
}
