<?php

namespace App\Repository;

use App\Entity\Baie;
use App\Entity\Client;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Baie>
 */
class BaieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Baie::class);
    }

    /**
     * Baies où le client loue des unités, avec le nombre d'unités louées dans chacune.
     *
     * @return list<array{baie: Baie, nbUnites: int}>
     */
    public function findLoueesParClient(Client $client): array
    {
        return $this->createQueryBuilder('b')
            ->select('b AS baie', 'COUNT(u.id) AS nbUnites')
            ->innerJoin('b.unites', 'u')
            ->innerJoin('u.commandes', 'c')
            ->andWhere('c.client = :client')
            ->andWhere(':aujourdhui BETWEEN c.dateDebut AND c.dateFin')
            ->setParameter('client', $client)
            ->setParameter('aujourdhui', new \DateTimeImmutable('today'), 'date_immutable')
            ->groupBy('b.id')
            ->orderBy('b.numero', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
