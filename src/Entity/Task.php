<?php

namespace App\Entity;

use App\Repository\TaskRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TaskRepository::class)]
class Task
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    // Relation avec Request (OneToMany)
    #[ORM\OneToMany(targetEntity: Request::class, mappedBy: 'task')]
    private Collection $requests;

    #[ORM\Column(length: 50)]
    private ?string $etape = null;

    // Relation avec User (OneToMany)
    #[ORM\OneToMany(targetEntity: User::class, mappedBy: 'task')]
    private Collection $assignees;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTime $due_date = null;

    #[ORM\Column(length: 20)]
    private ?string $statut = null;

    public function __construct()
    {
        $this->requests = new ArrayCollection();
        $this->assignees = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRequests(): Collection
    {
        return $this->requests;
    }

    public function addRequest(Request $request): static
    {
        if (!$this->requests->contains($request)) {
            $this->requests->add($request);
            $request->setTask($this);
        }
        return $this;
    }

    public function removeRequest(Request $request): static
    {
        if ($this->requests->removeElement($request)) {
            if ($request->getTask() === $this) {
                $request->setTask(null);
            }
        }
        return $this;
    }

    public function getEtape(): ?string
    {
        return $this->etape;
    }

    public function setEtape(string $etape): static
    {
        $this->etape = $etape;
        return $this;
    }

    public function getAssignees(): Collection
    {
        return $this->assignees;
    }

    public function addAssignee(User $user): static
    {
        if (!$this->assignees->contains($user)) {
            $this->assignees->add($user);
            $user->setTask($this);
        }
        return $this;
    }

    public function removeAssignee(User $user): static
    {
        if ($this->assignees->removeElement($user)) {
            if ($user->getTask() === $this) {
                $user->setTask(null);
            }
        }
        return $this;
    }

    public function getDueDate(): ?\DateTime
    {
        return $this->due_date;
    }

    public function setDueDate(\DateTime $due_date): static
    {
        $this->due_date = $due_date;
        return $this;
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
}
