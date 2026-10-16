<?php

namespace App\DataFixtures;

use App\Entity\Client;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * Un client de démonstration (client@worktogether.fr) et des clients générés.
 * Tous les clients ont le même mot de passe de test.
 */
class ClientFixtures extends Fixture
{
    public const NB_CLIENTS = 8;
    public const DEMO = 'client_demo';
    public const MOT_DE_PASSE = 'Client1234!';

    public function __construct(private readonly UserPasswordHasherInterface $hasher)
    {
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        $faker->seed(2026);

        $this->addReference(self::DEMO, $this->creerClient($manager, 'Jean', 'Dupont', 'client@worktogether.fr'));

        for ($i = 0; $i < self::NB_CLIENTS; ++$i) {
            $client = $this->creerClient($manager, $faker->firstName(), $faker->lastName(), $faker->unique()->safeEmail());
            $this->addReference(self::reference($i), $client);
        }

        $manager->flush();
    }

    public static function reference(int $i): string
    {
        return 'client_'.$i;
    }

    private function creerClient(ObjectManager $manager, string $prenom, string $nom, string $email): Client
    {
        $client = (new Client())
            ->setPrenom($prenom)
            ->setNom($nom)
            ->setEmail($email);
        $client->setMotDePasse($this->hasher->hashPassword($client, self::MOT_DE_PASSE));
        $manager->persist($client);

        return $client;
    }
}
