<?php

namespace App\Entity;

use App\Repository\UtilisateurRepository;
use Doctrine\ORM\Mapping as ORM;


#[ORM\Entity(repositoryClass: UtilisateurRepository::class)]
class Utilisateur extends User
{
    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Role $role = null;

    public function getRole(): ?Role
    {
        return $this->role;
    }

    public function setRole(Role $role): static
    {
        $this->role = $role;

        return $this;
    }

    /**
     * Ajoute les rôles donnés par la table Role (Administrateur inclut Comptable).
     */
    public function getRoles(): array
    {
        return array_values(array_unique([...parent::getRoles(), ...($this->role?->getRolesSecurite() ?? [])]));
    }
}
