<?php

namespace App\Entity;

use App\Repository\RoleRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: RoleRepository::class)]
#[UniqueEntity('libelle')]
class Role
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /** Exemple : ADMINISTRATEUR, COMPTABLE (donne ROLE_ADMINISTRATEUR, ROLE_COMPTABLE). */
    #[ORM\Column(length: 50, unique: true)]
    #[Assert\NotBlank]
    private ?string $libelle = null;

    /** Hiérarchie des rôles : le rôle Administrateur inclut le rôle Comptable. */
    #[ORM\ManyToOne(targetEntity: self::class)]
    #[ORM\JoinColumn(onDelete: 'SET NULL')]
    private ?Role $inclut = null;

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
        $this->libelle = strtoupper($libelle);

        return $this;
    }

    public function getInclut(): ?Role
    {
        return $this->inclut;
    }

    public function setInclut(?Role $inclut): static
    {
        $this->inclut = $inclut;

        return $this;
    }

    /**
     * Rôles Symfony donnés par ce rôle et par ceux qu'il inclut.
     *
     * @return list<string>
     */
    public function getRolesSecurite(): array
    {
        $roles = [];
        $role = $this;
        while (null !== $role && !in_array('ROLE_'.$role->getLibelle(), $roles, true)) {
            $roles[] = 'ROLE_'.$role->getLibelle();
            $role = $role->getInclut();
        }

        return $roles;
    }

    public function __toString(): string
    {
        return (string) $this->libelle;
    }
}
