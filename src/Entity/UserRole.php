<?php

namespace App\Entity;

use App\Repository\UserRoleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserRoleRepository::class)]
class UserRole
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * @var Collection<int, User>
     */
    #[ORM\OneToMany(targetEntity: User::class, mappedBy: 'userRole')]
    private Collection $user_id;

    /**
     * @var Collection<int, Role>
     */
    #[ORM\OneToMany(targetEntity: Role::class, mappedBy: 'userRole')]
    private Collection $role_id;

    #[ORM\Column(length: 50)]
    private ?string $portee = null;

    public function __construct()
    {
        $this->user_id = new ArrayCollection();
        $this->role_id = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUserId(): Collection
    {
        return $this->user_id;
    }

    public function addUserId(User $userId): static
    {
        if (!$this->user_id->contains($userId)) {
            $this->user_id->add($userId);
            $userId->setUserRole($this);
        }

        return $this;
    }

    public function removeUserId(User $userId): static
    {
        if ($this->user_id->removeElement($userId)) {
            // set the owning side to null (unless already changed)
            if ($userId->getUserRole() === $this) {
                $userId->setUserRole(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Role>
     */
    public function getRoleId(): Collection
    {
        return $this->role_id;
    }

    public function addRoleId(Role $roleId): static
    {
        if (!$this->role_id->contains($roleId)) {
            $this->role_id->add($roleId);
            $roleId->setUserRole($this);
        }

        return $this;
    }

    public function removeRoleId(Role $roleId): static
    {
        if ($this->role_id->removeElement($roleId)) {
            // set the owning side to null (unless already changed)
            if ($roleId->getUserRole() === $this) {
                $roleId->setUserRole(null);
            }
        }

        return $this;
    }

    public function getPortee(): ?string
    {
        return $this->portee;
    }

    public function setPortee(string $portee): static
    {
        $this->portee = $portee;

        return $this;
    }
}
