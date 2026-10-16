<?php

namespace App\Entity;

use App\Repository\BaieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: BaieRepository::class)]
#[UniqueEntity('numero')]
class Baie
{
    public const NB_UNITES = 42;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /** De B001 à B030. */
    #[ORM\Column(length: 4, unique: true)]
    #[Assert\Regex('/^B\d{3}$/')]
    private ?string $numero = null;

    #[ORM\Column]
    private int $nbUnites = self::NB_UNITES;

    /** @var Collection<int, Unite> */
    #[ORM\OneToMany(targetEntity: Unite::class, mappedBy: 'baie', cascade: ['persist'], orphanRemoval: true)]
    #[ORM\OrderBy(['numero' => 'ASC'])]
    private Collection $unites;

    public function __construct()
    {
        $this->unites = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumero(): ?string
    {
        return $this->numero;
    }

    public function setNumero(string $numero): static
    {
        $this->numero = $numero;

        return $this;
    }

    public function getNbUnites(): int
    {
        return $this->nbUnites;
    }

    public function setNbUnites(int $nbUnites): static
    {
        $this->nbUnites = $nbUnites;

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
            $unite->setBaie($this);
        }

        return $this;
    }

    public function removeUnite(Unite $unite): static
    {
        $this->unites->removeElement($unite);

        return $this;
    }

    public function __toString(): string
    {
        return (string) $this->numero;
    }
}
