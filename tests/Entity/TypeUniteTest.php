<?php

namespace App\Tests\Entity;

use App\Entity\TypeUnite;
use PHPUnit\Framework\TestCase;

class TypeUniteTest extends TestCase
{
    public function testGettersEtSetters(): void
    {
        $typeUnite = (new TypeUnite())
            ->setLibelle('Serveur')
            ->setCouleur('#FF0000');

        $this->assertNull($typeUnite->getId());
        $this->assertSame('Serveur', $typeUnite->getLibelle());
        $this->assertSame('#FF0000', $typeUnite->getCouleur());
    }

    public function testToString(): void
    {
        $this->assertSame('', (string) new TypeUnite());
        $this->assertSame('Serveur', (string) (new TypeUnite())->setLibelle('Serveur'));
    }
}
