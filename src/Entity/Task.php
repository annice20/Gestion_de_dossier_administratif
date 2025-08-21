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

    /**
     * @var Collection<int, Request>
     */
    #[ORM\OneToMany(targetEntity: Request::class, mappedBy: 'task')]
    private Collection $request_id;

    #[ORM\Column(length: 50)]
    private ?string $etape = null;

    /**
     * @var Collection<int, User>
     */
    #[ORM\OneToMany(targetEntity: User::class, mappedBy: 'task')]
    private Collection $assigne_a;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTime $due_date = null;

    #[ORM\Column(length: 20)]
    private ?string $statut = null;

    public function __construct()
    {
        $this->request_id = new ArrayCollection();
        $this->assigne_a = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, Request>
     */
    public function getRequestId(): Collection
    {
        return $this->request_id;
    }

    public function addRequestId(Request $requestId): static
    {
        if (!$this->request_id->contains($requestId)) {
            $this->request_id->add($requestId);
            $requestId->setTask($this);
        }

        return $this;
    }

    public function removeRequestId(Request $requestId): static
    {
        if ($this->request_id->removeElement($requestId)) {
            // set the owning side to null (unless already changed)
            if ($requestId->getTask() === $this) {
                $requestId->setTask(null);
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

    /**
     * @return Collection<int, User>
     */
    public function getAssigneA(): Collection
    {
        return $this->assigne_a;
    }

    public function addAssigneA(User $assigneA): static
    {
        if (!$this->assigne_a->contains($assigneA)) {
            $this->assigne_a->add($assigneA);
            $assigneA->setTask($this);
        }

        return $this;
    }

    public function removeAssigneA(User $assigneA): static
    {
        if ($this->assigne_a->removeElement($assigneA)) {
            // set the owning side to null (unless already changed)
            if ($assigneA->getTask() === $this) {
                $assigneA->setTask(null);
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
