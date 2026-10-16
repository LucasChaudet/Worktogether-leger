<?php

namespace App\DataFixtures;

use App\Entity\Client;
use App\Entity\Commande;
use App\Entity\Offre;
use App\Entity\TypeUnite;
use App\Entity\Unite;
use App\Repository\UniteRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

/**
 * Commandes des clients. Les unités sont attribuées de façon contiguë
 * (premières unités libres dans l'ordre des baies) pour éviter la fragmentation.
 */
class CommandeFixtures extends Fixture implements DependentFixtureInterface
{
    /** Unités du client de démonstration : nom => type. */
    private const UNITES_DEMO = [
        'web-front-01' => 'Serveur web',
        'web-front-02' => 'Serveur web',
        'api-01' => 'Serveur web',
        'bdd-principale' => 'Base de données',
        'bdd-replica' => 'Base de données',
        'stockage-01' => 'Stockage',
        'stockage-02' => 'Stockage',
        'switch-01' => 'Réseau',
    ];

    public function load(ObjectManager $manager): void
    {
        /** @var UniteRepository $uniteRepository */
        $uniteRepository = $manager->getRepository(Unite::class);

        // Client de démonstration : une commande Start-up en cours, une Base annuelle, une Base terminée
        $demo = $this->getReference(ClientFixtures::DEMO, Client::class);
        $startup = $this->creerCommande($manager, $uniteRepository, $demo, 'Start-up', false, new \DateTimeImmutable('first day of this month'));
        $base = $this->creerCommande($manager, $uniteRepository, $demo, 'Base', true, new \DateTimeImmutable('-2 months'));
        $this->creerCommande($manager, $uniteRepository, $demo, 'Base', false, new \DateTimeImmutable('-1 year'));

        $unitesDemo = [...$startup->getUnites(), ...$base->getUnites()];
        foreach (array_keys(self::UNITES_DEMO) as $i => $nom) {
            $type = $this->getReference(TypeUniteFixtures::reference(self::UNITES_DEMO[$nom]), TypeUnite::class);
            $unitesDemo[$i]->setNom($nom)->setTypeUnite($type);
            $this->addReference(self::referenceUniteDemo($i), $unitesDemo[$i]);
        }
        $unitesDemo[3]->setEtat(Unite::ETAT_INCIDENT);
        $unitesDemo[6]->setEtat(Unite::ETAT_MAINTENANCE);

        // Autres clients : une commande en cours chacun
        $faker = Factory::create('fr_FR');
        $faker->seed(2026);
        for ($i = 0; $i < ClientFixtures::NB_CLIENTS; ++$i) {
            $client = $this->getReference(ClientFixtures::reference($i), Client::class);
            $debut = new \DateTimeImmutable('-'.$faker->numberBetween(0, 20).' days');
            $this->creerCommande($manager, $uniteRepository, $client, $faker->randomElement(['Base', 'Start-up', 'PME']), $faker->boolean(), $debut);
        }

        $manager->flush();
    }

    public static function referenceUniteDemo(int $i): string
    {
        return 'unite_demo_'.$i;
    }

    public function getDependencies(): array
    {
        return [ClientFixtures::class, OffreFixtures::class, BaieFixtures::class, TypeUniteFixtures::class];
    }

    private function creerCommande(ObjectManager $manager, UniteRepository $uniteRepository, Client $client, string $libelleOffre, bool $annuel, \DateTimeImmutable $debut): Commande
    {
        $offre = $this->getReference(OffreFixtures::reference($libelleOffre), Offre::class);
        $debut = $debut->setTime(0, 0);
        $fin = $debut->modify($annuel ? '+1 year -1 day' : '+1 month -1 day');

        $commande = (new Commande())
            ->setDateCommande($debut->modify('-1 day')->setTime(10, 0))
            ->setDateDebut($debut)
            ->setDateFin($fin)
            ->setAnnuel($annuel)
            ->setPrix($offre->calculerPrix($annuel))
            ->setOffre($offre);
        $client->addCommande($commande);

        // Une commande terminée reprend les dernières unités libres, une commande en cours les premières
        $libres = $uniteRepository->findLibres();
        $terminee = $fin < new \DateTimeImmutable('today');
        $unites = $terminee
            ? array_slice($libres, -$offre->getNbUnites())
            : array_slice($libres, 0, $offre->getNbUnites());
        foreach ($unites as $unite) {
            $commande->addUnite($unite);
        }

        $manager->persist($commande);
        $manager->flush();

        return $commande;
    }
}
