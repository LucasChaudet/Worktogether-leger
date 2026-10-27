<?php

namespace App\Tests\Entity;

use App\Entity\Role;
use App\Entity\User;
use App\Entity\Utilisateur;
use PHPUnit\Framework\TestCase;

class UtilisateurTest extends TestCase
{
    public function testSansRole(): void
    {
        $utilisateur = new Utilisateur();

        $this->assertInstanceOf(User::class, $utilisateur);
        $this->assertNull($utilisateur->getRole());
        $this->assertSame(['ROLE_USER'], $utilisateur->getRoles());
    }

    public function testGetRolesAjouteLesRolesDuRole(): void
    {
        $comptable = (new Role())->setLibelle('COMPTABLE');
        $admin = (new Role())->setLibelle('ADMINISTRATEUR')->setInclut($comptable);

        $utilisateur = (new Utilisateur())->setRole($admin);

        $this->assertSame($admin, $utilisateur->getRole());
        $this->assertSame(['ROLE_USER', 'ROLE_ADMINISTRATEUR', 'ROLE_COMPTABLE'], $utilisateur->getRoles());
    }

    public function testGetRolesSansDoublon(): void
    {
        $utilisateur = (new Utilisateur())
            ->setRoles(['ROLE_COMPTABLE'])
            ->setRole((new Role())->setLibelle('COMPTABLE'));

        $this->assertSame(['ROLE_COMPTABLE', 'ROLE_USER'], $utilisateur->getRoles());
    }
}
