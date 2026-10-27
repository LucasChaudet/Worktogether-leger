<?php

namespace App\Tests\Controller\Client;

use App\Entity\Baie;
use App\Entity\Unite;
use App\Repository\ClientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Nécessite la base de test remplie avec les fixtures :
 * php bin/console doctrine:fixtures:load --env=test -n
 */
class UniteControllerTest extends WebTestCase
{
    private KernelBrowser $client;

    protected function setUp(): void
    {
        $this->client = static::createClient();

        // Jean Dupont loue les unités 1 à 11 de la baie B001
        $clientConnecte = static::getContainer()->get(ClientRepository::class)->findOneBy(['email' => 'client@worktogether.fr']);
        $this->client->loginUser($clientConnecte);
    }

    public function testClientPeutVoirSonUnite(): void
    {
        $this->client->request('GET', '/client/unite/'.$this->getUnite('B001', 1)->getId());

        $this->assertResponseIsSuccessful();
    }

    public function testClientPeutModifierSonUnite(): void
    {
        $this->client->request('GET', '/client/unite/'.$this->getUnite('B001', 1)->getId().'/edit');

        $this->assertResponseIsSuccessful();
    }

    public function testClientNePeutPasVoirLUniteDUnAutreClient(): void
    {
        // L'unité 12 de la baie B001 est louée par Marie Durand
        $this->client->request('GET', '/client/unite/'.$this->getUnite('B001', 12)->getId());

        $this->assertResponseStatusCodeSame(403);
    }

    public function testClientNePeutPasModifierLUniteDUnAutreClient(): void
    {
        $this->client->request('GET', '/client/unite/'.$this->getUnite('B001', 12)->getId().'/edit');

        $this->assertResponseStatusCodeSame(403);
    }

    public function testClientNePeutPasVoirUneUniteLibre(): void
    {
        // Personne ne loue l'unité 20 de la baie B001
        $this->client->request('GET', '/client/unite/'.$this->getUnite('B001', 20)->getId());

        $this->assertResponseStatusCodeSame(403);
    }

    private function getUnite(string $numeroBaie, int $numero): Unite
    {
        $entityManager = static::getContainer()->get(EntityManagerInterface::class);
        $baie = $entityManager->getRepository(Baie::class)->findOneBy(['numero' => $numeroBaie]);

        return $entityManager->getRepository(Unite::class)->findOneBy(['baie' => $baie, 'numero' => $numero]);
    }
}
