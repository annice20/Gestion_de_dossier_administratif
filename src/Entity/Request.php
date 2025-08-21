<?php

namespace App\Entity;

use App\Repository\RequestRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RequestRepository::class)]
class Request
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $ref = null;

    #[ORM\ManyToMany(targetEntity: RequestType::class, mappedBy: 'requests')]
    private Collection $types;

    #[ORM\ManyToMany(targetEntity: CitizenProfile::class, mappedBy: 'requests')]
    private Collection $demandeurs;

    #[ORM\Column(length: 20)]
    private ?string $statut = null;

    #[ORM\Column(length: 50)]
    private ?string $centre = null;

    #[ORM\Column]
    private ?int $priorite = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 12, scale: 2)]
    private ?string $montant = null;

    #[ORM\Column(length: 30)]
    private ?string $canal = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\ManyToOne(inversedBy: 'requests')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Attachment $attachment = null;

    #[ORM\ManyToOne(inversedBy: 'requests')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Document $document = null;

    #[ORM\ManyToOne(inversedBy: 'requests')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Task $task = null;

    #[ORM\ManyToOne(inversedBy: 'requests')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Payment $payment = null;

    #[ORM\ManyToOne(inversedBy: 'requests')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Decision $decision = null;

    public function __construct()
    {
        $this->types = new ArrayCollection();
        $this->demandeurs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRef(): ?string
    {
        return $this->ref;
    }

    public function setRef(string $ref): static
    {
        $this->ref = $ref;
        return $this;
    }

    /**
     * @return Collection<int, RequestType>
     */
    public function getTypes(): Collection
    {
        return $this->types;
    }

    public function addType(RequestType $type): static
    {
        if (!$this->types->contains($type)) {
            $this->types->add($type);
            $type->addRequest($this);
        }
        return $this;
    }

    public function removeType(RequestType $type): static
    {
        if ($this->types->removeElement($type)) {
            $type->removeRequest($this);
        }
        return $this;
    }

    /**
     * @return Collection<int, CitizenProfile>
     */
    public function getDemandeurs(): Collection
    {
        return $this->demandeurs;
    }

    public function addDemandeur(CitizenProfile $demandeur): static
    {
        if (!$this->demandeurs->contains($demandeur)) {
            $this->demandeurs->add($demandeur);
            $demandeur->addRequest($this);
        }
        return $this;
    }

    public function removeDemandeur(CitizenProfile $demandeur): static
    {
        if ($this->demandeurs->removeElement($demandeur)) {
            $demandeur->removeRequest($this);
        }
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

    public function getCentre(): ?string
    {
        return $this->centre;
    }

    public function setCentre(string $centre): static
    {
        $this->centre = $centre;
        return $this;
    }

    public function getPriorite(): ?int
    {
        return $this->priorite;
    }

    public function setPriorite(int $priorite): static
    {
        $this->priorite = $priorite;
        return $this;
    }

    public function getMontant(): ?string
    {
        return $this->montant;
    }

    public function setMontant(string $montant): static
    {
        $this->montant = $montant;
        return $this;
    }

    public function getCanal(): ?string
    {
        return $this->canal;
    }

    public function setCanal(string $canal): static
    {
        $this->canal = $canal;
        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    public function getAttachment(): ?Attachment
    {
        return $this->attachment;
    }

    public function setAttachment(?Attachment $attachment): static
    {
        $this->attachment = $attachment;
        return $this;
    }

    public function getDocument(): ?Document
    {
        return $this->document;
    }

    public function setDocument(?Document $document): static
    {
        $this->document = $document;
        return $this;
    }

    public function getTask(): ?Task
    {
        return $this->task;
    }

    public function setTask(?Task $task): static
    {
        $this->task = $task;
        return $this;
    }

    public function getPayment(): ?Payment
    {
        return $this->payment;
    }

    public function setPayment(?Payment $payment): static
    {
        $this->payment = $payment;
        return $this;
    }

    public function getDecision(): ?Decision
    {
        return $this->decision;
    }

    public function setDecision(?Decision $decision): static
    {
        $this->decision = $decision;
        return $this;
    }
}
