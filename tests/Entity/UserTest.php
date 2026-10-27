<?php

namespace App\Tests\Entity;

use App\Entity\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function testGettersEtSetters(): void
    {
        $user = (new User())
            ->setEmail('jean.dupont@example.com')
            ->setNom('Dupont')
            ->setPrenom('Jean')
            ->setMotDePasse('hash')
            ->setIsVerified(true);

        $this->assertNull($user->getId());
        $this->assertSame('jean.dupont@example.com', $user->getEmail());
        $this->assertSame('jean.dupont@example.com', $user->getUserIdentifier());
        $this->assertSame('Dupont', $user->getNom());
        $this->assertSame('Jean', $user->getPrenom());
        $this->assertSame('hash', $user->getMotDePasse());
        $this->assertSame('hash', $user->getPassword());
        $this->assertTrue($user->isVerified());
    }

    public function testNonVerifieParDefaut(): void
    {
        $this->assertFalse((new User())->isVerified());
    }

    public function testGetRolesAjouteToujoursRoleUser(): void
    {
        $user = new User();
        $this->assertSame(['ROLE_USER'], $user->getRoles());

        $user->setRoles(['ROLE_ADMIN', 'ROLE_USER']);
        $this->assertSame(['ROLE_ADMIN', 'ROLE_USER'], $user->getRoles());
    }

    public function testSerializeNeContientPasLeHashDuMotDePasse(): void
    {
        $user = (new User())->setMotDePasse('hash-secret');

        $data = $user->__serialize();

        $this->assertSame(hash('crc32c', 'hash-secret'), $data["\0".User::class."\0motDePasse"]);
        $this->assertStringNotContainsString('hash-secret', serialize($user));
    }

    public function testToString(): void
    {
        $user = (new User())->setPrenom('Jean')->setNom('Dupont');

        $this->assertSame('Jean Dupont', (string) $user);
    }
}
