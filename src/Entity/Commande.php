<?php

namespace App\Entity;

use App\Repository\CommandeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommandeRepository::class)]
class Commande
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $dateCommande = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE)]
    private ?\DateTimeImmutable $dateDebut = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE)]
    private ?\DateTimeImmutable $dateFin = null;

    /** true : tarification annuelle (-10 %), false : tarification mensuelle. */
    #[ORM\Column]
    private bool $annuel = false;

    /** Prix payé au moment de la commande (les offres peuvent évoluer). */
    #[ORM\Column]
    private ?float $prix = null;

    #[ORM\ManyToOne(inversedBy: 'commandes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Client $client = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Offre $offre = null;

    /** @var Collection<int, Unite> */
    #[ORM\ManyToMany(targetEntity: Unite::class, inversedBy: 'commandes')]
    private Collection $unites;

    public function __construct()
    {
        $this->unites = new ArrayCollection();
        $this->dateCommande = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateCommande(): ?\DateTimeImmutable
    {
        return $this->dateCommande;
    }

    public function setDateCommande(\DateTimeImmutable $dateCommande): static
    {
        $this->dateCommande = $dateCommande;

        return $this;
    }

    public function getDateDebut(): ?\DateTimeImmutable
    {
        return $this->dateDebut;
    }

    public function setDateDebut(\DateTimeImmutable $dateDebut): static
    {
        $this->dateDebut = $dateDebut;

        return $this;
    }

    public function getDateFin(): ?\DateTimeImmutable
    {
        return $this->dateFin;
    }

    public function setDateFin(\DateTimeImmutable $dateFin): static
    {
        $this->dateFin = $dateFin;

        return $this;
    }

    public function isAnnuel(): bool
    {
        return $this->annuel;
    }

    public function setAnnuel(bool $annuel): static
    {
        $this->annuel = $annuel;

        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): static
    {
        $this->prix = $prix;

        return $this;
    }

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(?Client $client): static
    {
        $this->client = $client;

        return $this;
    }

    public function getOffre(): ?Offre
    {
        return $this->offre;
    }

    public function setOffre(Offre $offre): static
    {
        $this->offre = $offre;

        return $this;
    }

    /** @return Collection<int, Unite> */
    public function getUnites(): Collection
    {
        return $this->unites;
    }

    public function addUnite(Unite $unite): static
    {
        if (!$this->unites->contains($unite)) {
            $this->unites->add($unite);
        }

        return $this;
    }

    public function removeUnite(Unite $unite): static
    {
        $this->unites->removeElement($unite);

        return $this;
    }

    public function isEnCours(?\DateTimeImmutable $date = null): bool
    {
        $date ??= new \DateTimeImmutable('today');

        return $this->dateDebut <= $date && $this->dateFin >= $date;
    }
}
