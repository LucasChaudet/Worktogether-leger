<?php

namespace App\Entity;

use App\Repository\UniteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UniteRepository::class)]
#[ORM\UniqueConstraint(columns: ['baie_id', 'numero'])]
class Unite
{
    public const ETAT_OK = 'OK';
    public const ETAT_INCIDENT = 'Incident';
    public const ETAT_MAINTENANCE = 'Maintenance';
    public const ETATS = [self::ETAT_OK, self::ETAT_INCIDENT, self::ETAT_MAINTENANCE];

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /** De 1 (U01, en bas de la baie) à 42 (U42). */
    #[ORM\Column]
    #[Assert\Range(min: 1, max: Baie::NB_UNITES)]
    private ?int $numero = null;

    /** Nom donné par le client. */
    #[ORM\Column(length: 100, nullable: true)]
    #[Assert\Length(max: 100)]
    private ?string $nom = null;

    #[ORM\Column(length: 20)]
    #[Assert\Choice(choices: self::ETATS)]
    private string $etat = self::ETAT_OK;

    #[ORM\ManyToOne(inversedBy: 'unites')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Baie $baie = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(onDelete: 'SET NULL')]
    private ?TypeUnite $typeUnite = null;

    /** @var Collection<int, Commande> */
    #[ORM\ManyToMany(targetEntity: Commande::class, mappedBy: 'unites')]
    private Collection $commandes;

    /** @var Collection<int, Intervention> */
    #[ORM\ManyToMany(targetEntity: Intervention::class, mappedBy: 'unites')]
    #[ORM\OrderBy(['dateHeureDebut' => 'DESC'])]
    private Collection $interventions;

    public function __construct()
    {
        $this->commandes = new ArrayCollection();
        $this->interventions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumero(): ?int
    {
        return $this->numero;
    }

    public function setNumero(int $numero): static
    {
        $this->numero = $numero;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(?string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getEtat(): string
    {
        return $this->etat;
    }

    public function setEtat(string $etat): static
    {
        $this->etat = $etat;

        return $this;
    }

    public function getBaie(): ?Baie
    {
        return $this->baie;
    }

    public function setBaie(?Baie $baie): static
    {
        $this->baie = $baie;

        return $this;
    }

    public function getTypeUnite(): ?TypeUnite
    {
        return $this->typeUnite;
    }

    public function setTypeUnite(?TypeUnite $typeUnite): static
    {
        $this->typeUnite = $typeUnite;

        return $this;
    }

    /** @return Collection<int, Commande> */
    public function getCommandes(): Collection
    {
        return $this->commandes;
    }

    /** @return Collection<int, Intervention> */
    public function getInterventions(): Collection
    {
        return $this->interventions;
    }

    /** Emplacement dans le datacenter, par exemple B014-U06. */
    public function getEmplacement(): string
    {
        return sprintf('%s-U%02d', $this->baie?->getNumero(), $this->numero);
    }

    /** Commande en cours qui réserve cette unité, s'il y en a une. */
    public function getCommandeEnCours(): ?Commande
    {
        foreach ($this->commandes as $commande) {
            if ($commande->isEnCours()) {
                return $commande;
            }
        }

        return null;
    }

    public function __toString(): string
    {
        return $this->getEmplacement();
    }
}
