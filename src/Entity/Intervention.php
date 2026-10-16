<?php

namespace App\Entity;

use App\Repository\InterventionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: InterventionRepository::class)]
class Intervention
{
    public const TYPE_INCIDENT = 'Incident';
    public const TYPE_MAINTENANCE = 'Maintenance';
    public const TYPES = [self::TYPE_INCIDENT, self::TYPE_MAINTENANCE];

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 20)]
    #[Assert\Choice(choices: self::TYPES)]
    private ?string $type = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank]
    private ?string $description = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $dateHeureDebut = null;

    /** Null tant que l'intervention n'est pas terminée. */
    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $dateHeureFin = null;

    /** @var Collection<int, Unite> */
    #[ORM\ManyToMany(targetEntity: Unite::class, inversedBy: 'interventions')]
    private Collection $unites;

    public function __construct()
    {
        $this->unites = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getDateHeureDebut(): ?\DateTimeImmutable
    {
        return $this->dateHeureDebut;
    }

    public function setDateHeureDebut(\DateTimeImmutable $dateHeureDebut): static
    {
        $this->dateHeureDebut = $dateHeureDebut;

        return $this;
    }

    public function getDateHeureFin(): ?\DateTimeImmutable
    {
        return $this->dateHeureFin;
    }

    public function setDateHeureFin(?\DateTimeImmutable $dateHeureFin): static
    {
        $this->dateHeureFin = $dateHeureFin;

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
}
