<?php

namespace App\Tests\Entity;

use App\Entity\Baie;
use App\Entity\Commande;
use App\Entity\TypeUnite;
use App\Entity\Unite;
use PHPUnit\Framework\TestCase;

class UniteTest extends TestCase
{
    public function testValeursParDefaut(): void
    {
        $unite = new Unite();

        $this->assertNull($unite->getId());
        $this->assertNull($unite->getNumero());
        $this->assertNull($unite->getNom());
        $this->assertSame(Unite::ETAT_OK, $unite->getEtat());
        $this->assertNull($unite->getBaie());
        $this->assertNull($unite->getTypeUnite());
        $this->assertCount(0, $unite->getCommandes());
        $this->assertCount(0, $unite->getInterventions());
    }

    public function testGettersEtSetters(): void
    {
        $baie = new Baie();
        $typeUnite = new TypeUnite();

        $unite = (new Unite())
            ->setNumero(6)
            ->setNom('Serveur web')
            ->setEtat(Unite::ETAT_INCIDENT)
            ->setBaie($baie)
            ->setTypeUnite($typeUnite);

        $this->assertSame(6, $unite->getNumero());
        $this->assertSame('Serveur web', $unite->getNom());
        $this->assertSame(Unite::ETAT_INCIDENT, $unite->getEtat());
        $this->assertSame($baie, $unite->getBaie());
        $this->assertSame($typeUnite, $unite->getTypeUnite());
    }

    public function testEmplacement(): void
    {
        $unite = (new Unite())
            ->setNumero(6)
            ->setBaie((new Baie())->setNumero('B014'));

        $this->assertSame('B014-U06', $unite->getEmplacement());
        $this->assertSame('B014-U06', (string) $unite);
    }

    public function testCommandeEnCoursSansCommande(): void
    {
        $this->assertNull((new Unite())->getCommandeEnCours());
    }

    public function testCommandeEnCours(): void
    {
        $unite = new Unite();
        $terminee = (new Commande())
            ->setDateDebut(new \DateTimeImmutable('-2 months'))
            ->setDateFin(new \DateTimeImmutable('-1 month'));
        $enCours = (new Commande())
            ->setDateDebut(new \DateTimeImmutable('-1 day'))
            ->setDateFin(new \DateTimeImmutable('+1 month'));

        // Côté inverse de la relation : on remplit la collection directement.
        $unite->getCommandes()->add($terminee);
        $this->assertNull($unite->getCommandeEnCours());

        $unite->getCommandes()->add($enCours);
        $this->assertSame($enCours, $unite->getCommandeEnCours());
    }
}
