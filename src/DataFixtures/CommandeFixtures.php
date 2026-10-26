<?php

namespace App\DataFixtures;

use App\Entity\Client;
use App\Entity\Commande;
use App\Entity\Offre;
use App\Entity\TypeUnite;
use App\Entity\Unite;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class CommandeFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $now = new \DateTimeImmutable('today');

        // Chaque commande réserve des unités qui se suivent dans une baie
        $commandes = [
            ['client' => 'client@worktogether.fr', 'offre' => 'Start-up', 'annuel' => false, 'dateDebut' => $now->modify('-10 days'), 'baie' => 'B001', 'unites' => [1, 2, 3, 4, 5, 6, 7, 8, 9, 10]],
            ['client' => 'client@worktogether.fr', 'offre' => 'Base', 'annuel' => true, 'dateDebut' => $now->modify('-2 months'), 'baie' => 'B001', 'unites' => [11]],
            ['client' => 'client@worktogether.fr', 'offre' => 'Base', 'annuel' => false, 'dateDebut' => $now->modify('-1 year'), 'baie' => 'B030', 'unites' => [42]],
            ['client' => 'marie.durand@gmail.com', 'offre' => 'Base', 'annuel' => false, 'dateDebut' => $now->modify('-5 days'), 'baie' => 'B001', 'unites' => [12]],
            ['client' => 'paul.lefebvre@gmail.com', 'offre' => 'PME', 'annuel' => true, 'dateDebut' => $now->modify('-3 months'), 'baie' => 'B002', 'unites' => [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21]],
            ['client' => 'sophie.bernard@gmail.com', 'offre' => 'Start-up', 'annuel' => false, 'dateDebut' => $now->modify('-15 days'), 'baie' => 'B003', 'unites' => [1, 2, 3, 4, 5, 6, 7, 8, 9, 10]],
            ['client' => 'karim.benali@gmail.com', 'offre' => 'Base', 'annuel' => true, 'dateDebut' => $now->modify('-1 month'), 'baie' => 'B001', 'unites' => [13]],
        ];

        foreach ($commandes as $data) {
            $offre = $this->getReference('offre_' . $data['offre'], Offre::class);

            // Une commande dure 1 an si elle est annuelle, sinon 1 mois
            if ($data['annuel']) {
                $dateFin = $data['dateDebut']->modify('+1 year');
            } else {
                $dateFin = $data['dateDebut']->modify('+1 month');
            }

            $commande = new Commande();
            $commande->setClient($this->getReference('client_' . $data['client'], Client::class));
            $commande->setOffre($offre);
            $commande->setAnnuel($data['annuel']);
            $commande->setDateCommande($data['dateDebut']);
            $commande->setDateDebut($data['dateDebut']);
            $commande->setDateFin($dateFin);
            $commande->setPrix($offre->calculerPrix($data['annuel']));
            foreach ($data['unites'] as $numero) {
                $commande->addUnite($this->getReference('unite_' . $data['baie'] . '_' . $numero, Unite::class));
            }
            $manager->persist($commande);
        }

        // Le client de démonstration a donné un nom et un type à certaines de ses unités
        $unitesClient = [
            ['unite' => 'unite_B001_1', 'nom' => 'web-front-01', 'type' => 'Serveur web', 'etat' => 'OK'],
            ['unite' => 'unite_B001_2', 'nom' => 'web-front-02', 'type' => 'Serveur web', 'etat' => 'OK'],
            ['unite' => 'unite_B001_3', 'nom' => 'api-01', 'type' => 'Serveur web', 'etat' => 'OK'],
            ['unite' => 'unite_B001_4', 'nom' => 'bdd-principale', 'type' => 'Base de données', 'etat' => 'Incident'],
            ['unite' => 'unite_B001_5', 'nom' => 'bdd-replica', 'type' => 'Base de données', 'etat' => 'OK'],
            ['unite' => 'unite_B001_6', 'nom' => 'stockage-01', 'type' => 'Stockage', 'etat' => 'OK'],
            ['unite' => 'unite_B001_7', 'nom' => 'stockage-02', 'type' => 'Stockage', 'etat' => 'Maintenance'],
            ['unite' => 'unite_B001_8', 'nom' => 'switch-01', 'type' => 'Réseau', 'etat' => 'OK'],
        ];

        foreach ($unitesClient as $data) {
            $unite = $this->getReference($data['unite'], Unite::class);
            $unite->setNom($data['nom']);
            $unite->setTypeUnite($this->getReference('type_' . $data['type'], TypeUnite::class));
            $unite->setEtat($data['etat']);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [ClientFixtures::class, OffreFixtures::class, BaieFixtures::class, TypeUniteFixtures::class];
    }
}
