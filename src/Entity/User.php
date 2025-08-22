<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    private ?string $hashMdp = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $statut = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $telephone = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $langue = null;

    #[ORM\Column(type: 'boolean')]
    private bool $twoFAEnabled = false;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $createdAt = null;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->twoFAEnabled = false;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;
        return $this;
    }

    public function getHashMdp(): ?string
    {
        return $this->hashMdp;
    }

    public function setHashMdp(string $hashMdp): static
    {
        $this->hashMdp = $hashMdp;
        return $this;
    }

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(?string $statut): static
    {
        $this->statut = $statut;
        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(?string $telephone): static
    {
        $this->telephone = $telephone;
        return $this;
    }

    public function getLangue(): ?string
    {
        return $this->langue;
    }

    public function setLangue(?string $langue): static
    {
        $this->langue = $langue;
        return $this;
    }

    public function isTwoFAEnabled(): bool
    {
        return $this->twoFAEnabled;
    }

    public function setTwoFAEnabled(bool $twoFAEnabled): static
    {
        $this->twoFAEnabled = $twoFAEnabled;
        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): static
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    // ========================================
    // Méthodes pour UserInterface & PasswordAuthenticatedUserInterface
    // ========================================

    public function getUserIdentifier(): string
    {
        return (string) $this->email; // Identifiant pour Symfony
    }

    public function getPassword(): string
    {
        return (string) $this->hashMdp; // Retourne le mot de passe hashé
    }

    public function getRoles(): array
    {
        // Pour l'instant on retourne un rôle par défaut
        return ['ROLE_USER'];
    }

    public function eraseCredentials(): void
    {
        // Si tu stockes des infos sensibles temporairement, les effacer ici
    }
}
