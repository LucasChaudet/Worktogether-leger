<?php

namespace App\DataFixtures;

use App\Entity\Client;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class ClientFixtures extends Fixture
{
    public function __construct(private readonly UserPasswordHasherInterface $hasher)
    {
    }

    public function load(ObjectManager $manager): void
    {
        $clients = [
            ['prenom' => 'Jean', 'nom' => 'Dupont', 'email' => 'client@worktogether.fr'],
            ['prenom' => 'Marie', 'nom' => 'Durand', 'email' => 'marie.durand@gmail.com'],
            ['prenom' => 'Paul', 'nom' => 'Lefebvre', 'email' => 'paul.lefebvre@gmail.com'],
            ['prenom' => 'Sophie', 'nom' => 'Bernard', 'email' => 'sophie.bernard@gmail.com'],
            ['prenom' => 'Karim', 'nom' => 'Benali', 'email' => 'karim.benali@gmail.com'],
        ];

        foreach ($clients as $data) {
            $client = new Client();
            $client->setPrenom($data['prenom']);
            $client->setNom($data['nom']);
            $client->setEmail($data['email']);
            $client->setRoles(['ROLE_CLIENT']);
            $client->setIsVerified(true);
            $client->setMotDePasse($this->hasher->hashPassword($client, '1234'));
            $manager->persist($client);
            $this->addReference('client_' . $data['email'], $client);
        }

        $manager->flush();
    }
}
