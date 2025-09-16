<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 255)]
    private ?string $statut = null;

    #[ORM\Column(length: 255)]
    private ?string $telephone = null;

    #[ORM\Column(length: 255)]
    private ?string $langue = null;

    #[ORM\Column]
    private ?bool $two_faenabled = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $created_at = null;

    #[ORM\OneToOne(mappedBy: 'user', cascade: ['persist', 'remove'])]
    private ?CitizenProfile $citizenProfile = null;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: UserRole::class, orphanRemoval: true)]
    private Collection $userRoles;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: CodeValidation::class, orphanRemoval: true)]
    private Collection $codeValidations;

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private ?string $apiToken = null;

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private ?string $resetToken = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $resetTokenExpiresAt = null;

    public function __construct()
    {
        $this->userRoles = new ArrayCollection();
        $this->codeValidations = new ArrayCollection();
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

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = ['ROLE_USER'];

        foreach ($this->getUserRoles() as $userRole) {
            if ($userRole->getRole() !== null) {
                // On récupère le code du rôle ('ADMIN', 'USER') et on ajoute le préfixe
                $roles[] = 'ROLE_' . strtoupper($userRole->getRole()->getCode());
            }
        }

        return array_unique($roles);
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // Si vous stockez des données temporaires et sensibles sur l'utilisateur, effacez-les ici
        // $this->plainPassword = null;
    }

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(string $statut): static
    {
        $this->statut = $statut;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(string $telephone): static
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getLangue(): ?string
    {
        return $this->langue;
    }

    public function setLangue(string $langue): static
    {
        $this->langue = $langue;

        return $this;
    }

    public function isTwoFAEnabled(): ?bool
    {
        return $this->two_faenabled;
    }

    public function setTwoFAEnabled(bool $two_faenabled): static
    {
        $this->two_faenabled = $two_faenabled;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): static
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getCitizenProfile(): ?CitizenProfile
    {
        return $this->citizenProfile;
    }

    public function setCitizenProfile(CitizenProfile $citizenProfile): static
    {
        // set the owning side of the relation if necessary
        if ($citizenProfile->getUser() !== $this) {
            $citizenProfile->setUser($this);
        }

        $this->citizenProfile = $citizenProfile;

        return $this;
    }

    /**
     * @return Collection<int, UserRole>
     */
    public function getUserRoles(): Collection
    {
        return $this->userRoles;
    }

    public function addUserRole(UserRole $userRole): static
    {
        if (!$this->userRoles->contains($userRole)) {
            $this->userRoles->add($userRole);
            $userRole->setUser($this);
        }

        return $this;
    }

    public function removeUserRole(UserRole $userRole): static
    {
        if ($this->userRoles->removeElement($userRole)) {
            // set the owning side to null (unless already changed)
            if ($userRole->getUser() === $this) {
                $userRole->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, CodeValidation>
     */
    public function getCodeValidations(): Collection
    {
        return $this->codeValidations;
    }

    public function addCodeValidation(CodeValidation $codeValidation): static
    {
        if (!$this->codeValidations->contains($codeValidation)) {
            $this->codeValidations->add($codeValidation);
            $codeValidation->setUser($this);
        }

        return $this;
    }

    public function removeCodeValidation(CodeValidation $codeValidation): static
    {
        if ($this->codeValidations->removeElement($codeValidation)) {
            if ($codeValidation->getUser() === $this) {
                $codeValidation->setUser(null);
            }
        }

        return $this;
    }

    public function getApiToken(): ?string
    {
        return $this->apiToken;
    }

    public function setApiToken(?string $apiToken): self
    {
        $this->apiToken = $apiToken;
        return $this;
    }

    public function getResetToken(): ?string
    {
        return $this->resetToken;
    }

    public function setResetToken(?string $resetToken): self
    {
        $this->resetToken = $resetToken;
        return $this;
    }

    public function getResetTokenExpiresAt(): ?\DateTimeInterface
    {
        return $this->resetTokenExpiresAt;
    }

    public function setResetTokenExpiresAt(?\DateTimeInterface $expiresAt): self
    {
        $this->resetTokenExpiresAt = $expiresAt;
        return $this;
    }
}
