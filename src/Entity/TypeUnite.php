<?php

namespace App\Entity;

use App\Repository\TypeUniteRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: TypeUniteRepository::class)]
class TypeUnite
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /** Exemple : Serveur web, Base de données, Stockage. */
    #[ORM\Column(length: 50)]
    #[Assert\NotBlank]
    private ?string $libelle = null;

    /** Couleur hexadécimale, par exemple #1E88E5. */
    #[ORM\Column(length: 7)]
    #[Assert\CssColor(formats: Assert\CssColor::HEX_LONG)]
    private ?string $couleur = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): static
    {
        $this->libelle = $libelle;

        return $this;
    }

    public function getCouleur(): ?string
    {
        return $this->couleur;
    }

    public function setCouleur(string $couleur): static
    {
        $this->couleur = $couleur;

        return $this;
    }

    public function __toString(): string
    {
        return (string) $this->libelle;
    }
}
