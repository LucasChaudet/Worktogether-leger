<?php

namespace App\DataFixtures;

use App\Entity\Role;
use App\Entity\Utilisateur;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UtilisateurFixtures extends Fixture implements DependentFixtureInterface
{
    public function __construct(private readonly UserPasswordHasherInterface $hasher)
    {
    }

    public function load(ObjectManager $manager): void
    {
        $utilisateurs = [
            ['prenom' => 'Alice', 'nom' => 'Martin', 'email' => 'admin@worktogether.fr', 'role' => 'ADMINISTRATEUR'],
            ['prenom' => 'Bruno', 'nom' => 'Petit', 'email' => 'comptable@worktogether.fr', 'role' => 'COMPTABLE'],
        ];

        foreach ($utilisateurs as $data) {
            $utilisateur = new Utilisateur();
            $utilisateur->setPrenom($data['prenom']);
            $utilisateur->setNom($data['nom']);
            $utilisateur->setEmail($data['email']);
            $utilisateur->setIsVerified(true);
            $utilisateur->setRole($this->getReference('role_' . $data['role'], Role::class));
            $utilisateur->setMotDePasse($this->hasher->hashPassword($utilisateur, '1234'));
            $manager->persist($utilisateur);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [RoleFixtures::class];
    }
}
