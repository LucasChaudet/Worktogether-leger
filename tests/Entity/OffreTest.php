<?php

namespace App\Tests\Entity;

use App\Entity\Offre;
use PHPUnit\Framework\TestCase;

class OffreTest extends TestCase
{
    public function testValeursParDefaut(): void
    {
        $offre = new Offre();

        $this->assertNull($offre->getId());
        $this->assertNull($offre->getLibelle());
        $this->assertNull($offre->getNbUnites());
        $this->assertNull($offre->getPrixMensuel());
        $this->assertEquals(0, $offre->getRemise());
    }

    public function testGettersEtSetters(): void
    {
        $offre = (new Offre())
            ->setLibelle('Base')
            ->setNbUnites(10)
            ->setPrixMensuel(100.0)
            ->setRemise(5.0);

        $this->assertSame('Base', $offre->getLibelle());
        $this->assertSame(10, $offre->getNbUnites());
        $this->assertSame(100.0, $offre->getPrixMensuel());
        $this->assertSame(5.0, $offre->getRemise());
    }

    public function testCalculerPrixMensuel(): void
    {
        $offre = (new Offre())->setPrixMensuel(100.0);

        $this->assertSame(100.0, $offre->calculerPrix(false));
    }

    public function testCalculerPrixAnnuelAppliqueLaRemiseAnnuelle(): void
    {
        $offre = (new Offre())->setPrixMensuel(100.0);

        // 100 x 12 - 10 %
        $this->assertSame(1080.0, $offre->calculerPrix(true));
    }

    public function testCalculerPrixAnnuelEstArrondiAuCentime(): void
    {
        $offre = (new Offre())->setPrixMensuel(33.33);

        // 33.33 x 12 x 0.9 = 359.964
        $this->assertSame(359.96, $offre->calculerPrix(true));
    }

    public function testToString(): void
    {
        $this->assertSame('', (string) new Offre());
        $this->assertSame('Base', (string) (new Offre())->setLibelle('Base'));
    }
}
