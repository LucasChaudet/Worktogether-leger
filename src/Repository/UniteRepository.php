<?php

namespace App\Repository;

use App\Entity\Client;
use App\Entity\Unite;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Unite>
 */
class UniteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Unite::class);
    }

    /**
     * Unités louées actuellement par un client (commande en cours), triées par emplacement.
     *
     * @return list<Unite>
     */
    public function findLoueesParClient(Client $client): array
    {
        return $this->createQueryBuilder('u')
            ->addSelect('b', 't')
            ->innerJoin('u.baie', 'b')
            ->leftJoin('u.typeUnite', 't')
            ->innerJoin('u.commandes', 'c')
            ->andWhere('c.client = :client')
            ->andWhere(':aujourdhui BETWEEN c.dateDebut AND c.dateFin')
            ->setParameter('client', $client)
            ->setParameter('aujourdhui', new \DateTimeImmutable('today'), 'date_immutable')
            ->orderBy('b.numero', 'ASC')
            ->addOrderBy('u.numero', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Unités libres (aucune commande en cours) dans l'ordre des baies, pour attribuer
     * des unités contiguës et éviter la fragmentation des baies.
     *
     * @return list<Unite>
     */
    public function findLibres(): array
    {
        $occupees = $this->getEntityManager()->createQueryBuilder()
            ->select('u2.id')
            ->from(Unite::class, 'u2')
            ->innerJoin('u2.commandes', 'c2')
            ->andWhere(':aujourdhui BETWEEN c2.dateDebut AND c2.dateFin');

        return $this->createQueryBuilder('u')
            ->addSelect('b')
            ->innerJoin('u.baie', 'b')
            ->andWhere('u.id NOT IN ('.$occupees->getDQL().')')
            ->setParameter('aujourdhui', new \DateTimeImmutable('today'), 'date_immutable')
            ->orderBy('b.numero', 'ASC')
            ->addOrderBy('u.numero', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
