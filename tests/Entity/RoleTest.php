<?php

namespace App\Tests\Entity;

use App\Entity\Role;
use PHPUnit\Framework\TestCase;

class RoleTest extends TestCase
{
    public function testSetLibelleMetEnMajuscules(): void
    {
        $role = (new Role())->setLibelle('comptable');

        $this->assertNull($role->getId());
        $this->assertSame('COMPTABLE', $role->getLibelle());
        $this->assertSame('COMPTABLE', (string) $role);
    }

    public function testRolesSecuriteSansInclusion(): void
    {
        $role = (new Role())->setLibelle('COMPTABLE');

        $this->assertNull($role->getInclut());
        $this->assertSame(['ROLE_COMPTABLE'], $role->getRolesSecurite());
    }

    public function testRolesSecuriteAvecInclusion(): void
    {
        $comptable = (new Role())->setLibelle('COMPTABLE');
        $admin = (new Role())->setLibelle('ADMINISTRATEUR')->setInclut($comptable);

        $this->assertSame($comptable, $admin->getInclut());
        $this->assertSame(['ROLE_ADMINISTRATEUR', 'ROLE_COMPTABLE'], $admin->getRolesSecurite());
    }

    public function testRolesSecuriteSArreteSurUnCycle(): void
    {
        $a = (new Role())->setLibelle('A');
        $b = (new Role())->setLibelle('B')->setInclut($a);
        $a->setInclut($b);

        $this->assertSame(['ROLE_A', 'ROLE_B'], $a->getRolesSecurite());
    }
}
