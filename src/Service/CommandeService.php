<?php

namespace App\Service;

use App\Entity\Client;
use App\Entity\Commande;
use App\Entity\Unite;
use App\Repository\UniteRepository;
use Doctrine\ORM\EntityManagerInterface;

class CommandeService
{
    public function __construct(
        private readonly UniteRepository $uniteRepository,
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    /**
     * Enregistre la commande d'un client.
     * Renvoie false s'il n'y a pas assez d'unités libres pour l'offre choisie.
     */
    public function passerCommande(Commande $commande, Client $client): bool
    {
        $offre = $commande->getOffre();

        $unites = $this->trouverUnitesLibres($offre->getNbUnites());
        if (!$unites) {
            return false;
        }

        foreach ($unites as $unite) {
            // l'unité repart sans le nom ni le type de son ancien locataire
            $unite->setNom(null);
            $unite->setTypeUnite(null);
            $commande->addUnite($unite);
        }

        // une commande dure 1 an si elle est annuelle, sinon 1 mois
        $dateDebut = new \DateTimeImmutable('today');
        if ($commande->isAnnuel()) {
            $dateFin = $dateDebut->modify('+1 year');
        } else {
            $dateFin = $dateDebut->modify('+1 month');
        }

        $commande->setClient($client);
        $commande->setDateDebut($dateDebut);
        $commande->setDateFin($dateFin);
        $commande->setPrix($offre->calculerPrix($commande->isAnnuel()));

        $this->entityManager->persist($commande);
        $this->entityManager->flush();

        return true;
    }

    /**
     * Cherche des unités libres qui se suivent dans une même baie.
     * Renvoie un tableau vide s'il n'y a pas assez de place.
     *
     * @return Unite[]
     */
    private function trouverUnitesLibres(int $nbUnites): array
    {
        $unites = [];
        $precedente = null;

        foreach ($this->uniteRepository->findLibres() as $unite) {
            $suitLaPrecedente = null !== $precedente
                && $unite->getBaie() === $precedente->getBaie()
                && $unite->getNumero() === $precedente->getNumero() + 1;

            // si l'unité ne suit pas la précédente, on recommence à compter à partir d'elle
            if (!$suitLaPrecedente) {
                $unites = [];
            }

            $unites[] = $unite;
            $precedente = $unite;

            if (count($unites) === $nbUnites) {
                return $unites;
            }
        }

        return [];
    }
}
