<?php

namespace App\DataFixtures;

use App\Entity\Role;
use App\Entity\Utilisateur;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * Personnel de WorkTogether (comptes de test).
 */
class UtilisateurFixtures extends Fixture implements DependentFixtureInterface
{
    public function __construct(private readonly UserPasswordHasherInterface $hasher)
    {
    }

    public function load(ObjectManager $manager): void
    {
        foreach ([
            ['Alice', 'Martin', 'admin@worktogether.fr', 'Admin1234!', RoleFixtures::ADMINISTRATEUR],
            ['Bruno', 'Petit', 'comptable@worktogether.fr', 'Comptable1234!', RoleFixtures::COMPTABLE],
        ] as [$prenom, $nom, $email, $motDePasse, $role]) {
            $utilisateur = (new Utilisateur())
                ->setPrenom($prenom)
                ->setNom($nom)
                ->setEmail($email)
                ->setRole($this->getReference($role, Role::class));
            $utilisateur->setMotDePasse($this->hasher->hashPassword($utilisateur, $motDePasse));
            $manager->persist($utilisateur);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [RoleFixtures::class];
    }
}
