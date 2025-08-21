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

    /**
     * @var Collection<int, RequestType>
     */
    #[ORM\OneToMany(targetEntity: RequestType::class, mappedBy: 'request')]
    private Collection $type_id;

    /**
     * @var Collection<int, CitizenProfile>
     */
    #[ORM\OneToMany(targetEntity: CitizenProfile::class, mappedBy: 'request')]
    private Collection $demandeur_id;

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

    #[ORM\Column]
    private ?\DateTime $created_at = null;

    #[ORM\Column]
    private ?\DateTime $updated_at = null;

    #[ORM\ManyToOne(inversedBy: 'request_id')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Attachment $attachment = null;

    #[ORM\ManyToOne(inversedBy: 'request_id')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Document $document = null;

    #[ORM\ManyToOne(inversedBy: 'request_id')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Task $task = null;

    #[ORM\ManyToOne(inversedBy: 'request_id')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Payment $payment = null;

    #[ORM\ManyToOne(inversedBy: 'request_id')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Decision $decision = null;

    public function __construct()
    {
        $this->type_id = new ArrayCollection();
        $this->demandeur_id = new ArrayCollection();
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
    public function getTypeId(): Collection
    {
        return $this->type_id;
    }

    public function addTypeId(RequestType $typeId): static
    {
        if (!$this->type_id->contains($typeId)) {
            $this->type_id->add($typeId);
            $typeId->setRequest($this);
        }

        return $this;
    }

    public function removeTypeId(RequestType $typeId): static
    {
        if ($this->type_id->removeElement($typeId)) {
            // set the owning side to null (unless already changed)
            if ($typeId->getRequest() === $this) {
                $typeId->setRequest(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, CitizenProfile>
     */
    public function getDemandeurId(): Collection
    {
        return $this->demandeur_id;
    }

    public function addDemandeurId(CitizenProfile $demandeurId): static
    {
        if (!$this->demandeur_id->contains($demandeurId)) {
            $this->demandeur_id->add($demandeurId);
            $demandeurId->setRequest($this);
        }

        return $this;
    }

    public function removeDemandeurId(CitizenProfile $demandeurId): static
    {
        if ($this->demandeur_id->removeElement($demandeurId)) {
            // set the owning side to null (unless already changed)
            if ($demandeurId->getRequest() === $this) {
                $demandeurId->setRequest(null);
            }
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

    public function getCreatedAt(): ?\DateTime
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTime $created_at): static
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTime
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(\DateTime $updated_at): static
    {
        $this->updated_at = $updated_at;

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
