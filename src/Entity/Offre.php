<?php

namespace App\Entity;

use App\Repository\OffreRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: OffreRepository::class)]
class Offre
{
    /** Remise supplémentaire accordée en cas de paiement annuel (en %). */
    public const REMISE_ANNUELLE = 10;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    #[Assert\NotBlank]
    private ?string $libelle = null;

    #[ORM\Column]
    #[Assert\Range(min: 1, max: 42)]
    private ?int $nbUnites = null;

    #[ORM\Column]
    #[Assert\PositiveOrZero]
    private ?float $prixMensuel = null;

    /** Remise en pourcentage déjà appliquée au prix mensuel (affichage). */
    #[ORM\Column]
    #[Assert\Range(min: 0, max: 100)]
    private ?float $remise = 0;

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

    public function getNbUnites(): ?int
    {
        return $this->nbUnites;
    }

    public function setNbUnites(int $nbUnites): static
    {
        $this->nbUnites = $nbUnites;

        return $this;
    }

    public function getPrixMensuel(): ?float
    {
        return $this->prixMensuel;
    }

    public function setPrixMensuel(float $prixMensuel): static
    {
        $this->prixMensuel = $prixMensuel;

        return $this;
    }

    public function getRemise(): ?float
    {
        return $this->remise;
    }

    public function setRemise(float $remise): static
    {
        $this->remise = $remise;

        return $this;
    }

    /** Prix d'une période : un mois, ou douze mois avec la remise annuelle. */
    public function calculerPrix(bool $annuel): float
    {
        if (!$annuel) {
            return $this->prixMensuel;
        }

        return round($this->prixMensuel * 12 * (1 - self::REMISE_ANNUELLE / 100), 2);
    }

    public function __toString(): string
    {
        return (string) $this->libelle;
    }
}
