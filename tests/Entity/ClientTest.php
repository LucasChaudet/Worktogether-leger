<?php

namespace App\Tests\Entity;

use App\Entity\Client;
use App\Entity\Commande;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

class ClientTest extends TestCase
{
    public function testEstUnUser(): void
    {
        $client = new Client();

        $this->assertInstanceOf(User::class, $client);
        $this->assertSame(['ROLE_USER'], $client->getRoles());
        $this->assertCount(0, $client->getCommandes());
    }

    public function testAddCommandeLieLaCommandeAuClient(): void
    {
        $client = new Client();
        $commande = new Commande();

        $client->addCommande($commande)->addCommande($commande);

        $this->assertCount(1, $client->getCommandes());
        $this->assertSame($client, $commande->getClient());
    }

    public function testRemoveCommandeDelieLaCommande(): void
    {
        $client = new Client();
        $commande = new Commande();
        $client->addCommande($commande);

        $client->removeCommande($commande);

        $this->assertCount(0, $client->getCommandes());
        $this->assertNull($commande->getClient());
    }

    public function testRemoveCommandeNeDeliePasUneCommandeDejaReattribuee(): void
    {
        $client = new Client();
        $autreClient = new Client();
        $commande = new Commande();
        $client->addCommande($commande);
        $commande->setClient($autreClient);

        $client->removeCommande($commande);

        $this->assertSame($autreClient, $commande->getClient());
    }
}
