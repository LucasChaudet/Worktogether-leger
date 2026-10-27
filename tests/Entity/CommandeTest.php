<?php

namespace App\Tests\Entity;

use App\Entity\Client;
use App\Entity\Commande;
use App\Entity\Offre;
use App\Entity\Unite;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class CommandeTest extends TestCase
{
    public function testValeursParDefaut(): void
    {
        $avant = new \DateTimeImmutable();
        $commande = new Commande();

        $this->assertNull($commande->getId());
        $this->assertGreaterThanOrEqual($avant, $commande->getDateCommande());
        $this->assertFalse($commande->isAnnuel());
        $this->assertNull($commande->getPrix());
        $this->assertNull($commande->getClient());
        $this->assertNull($commande->getOffre());
        $this->assertCount(0, $commande->getUnites());
    }

    public function testGettersEtSetters(): void
    {
        $dateCommande = new \DateTimeImmutable('2026-01-01 10:00');
        $dateDebut = new \DateTimeImmutable('2026-01-02');
        $dateFin = new \DateTimeImmutable('2026-02-01');
        $client = new Client();
        $offre = new Offre();

        $commande = (new Commande())
            ->setDateCommande($dateCommande)
            ->setDateDebut($dateDebut)
            ->setDateFin($dateFin)
            ->setAnnuel(true)
            ->setPrix(99.9)
            ->setClient($client)
            ->setOffre($offre);

        $this->assertSame($dateCommande, $commande->getDateCommande());
        $this->assertSame($dateDebut, $commande->getDateDebut());
        $this->assertSame($dateFin, $commande->getDateFin());
        $this->assertTrue($commande->isAnnuel());
        $this->assertSame(99.9, $commande->getPrix());
        $this->assertSame($client, $commande->getClient());
        $this->assertSame($offre, $commande->getOffre());
    }

    public function testAddEtRemoveUnite(): void
    {
        $commande = new Commande();
        $unite = new Unite();

        $commande->addUnite($unite)->addUnite($unite);
        $this->assertCount(1, $commande->getUnites());

        $commande->removeUnite($unite);
        $this->assertCount(0, $commande->getUnites());
    }

    public static function datesProvider(): iterable
    {
        yield 'avant le début' => ['2026-01-31', false];
        yield 'premier jour' => ['2026-02-01', true];
        yield 'pendant' => ['2026-02-15', true];
        yield 'dernier jour' => ['2026-02-28', true];
        yield 'après la fin' => ['2026-03-01', false];
    }

    #[DataProvider('datesProvider')]
    public function testIsEnCours(string $date, bool $attendu): void
    {
        $commande = (new Commande())
            ->setDateDebut(new \DateTimeImmutable('2026-02-01'))
            ->setDateFin(new \DateTimeImmutable('2026-02-28'));

        $this->assertSame($attendu, $commande->isEnCours(new \DateTimeImmutable($date)));
    }

    public function testIsEnCoursUtiliseAujourdhuiParDefaut(): void
    {
        $enCours = (new Commande())
            ->setDateDebut(new \DateTimeImmutable('-1 day'))
            ->setDateFin(new \DateTimeImmutable('+1 day'));
        $terminee = (new Commande())
            ->setDateDebut(new \DateTimeImmutable('-1 month'))
            ->setDateFin(new \DateTimeImmutable('-1 day'));

        $this->assertTrue($enCours->isEnCours());
        $this->assertFalse($terminee->isEnCours());
    }
}
