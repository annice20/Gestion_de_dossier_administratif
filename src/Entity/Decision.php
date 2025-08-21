<?php

namespace App\Entity;

use App\Repository\DecisionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DecisionRepository::class)]
class Decision
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * @var Collection<int, request>
     */
    #[ORM\OneToMany(targetEntity: request::class, mappedBy: 'decision')]
    private Collection $request_id;

    #[ORM\Column(length: 20)]
    private ?string $resultat = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $motif = null;

    #[ORM\Column(length: 100)]
    private ?string $valide_par = null;

    #[ORM\Column]
    private ?\DateTime $valide_le = null;

    #[ORM\OneToOne(mappedBy: 'decision_id', cascade: ['persist', 'remove'])]
    private ?Signature $signature = null;

    public function __construct()
    {
        $this->request_id = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, request>
     */
    public function getRequestId(): Collection
    {
        return $this->request_id;
    }

    public function addRequestId(request $requestId): static
    {
        if (!$this->request_id->contains($requestId)) {
            $this->request_id->add($requestId);
            $requestId->setDecision($this);
        }

        return $this;
    }

    public function removeRequestId(request $requestId): static
    {
        if ($this->request_id->removeElement($requestId)) {
            // set the owning side to null (unless already changed)
            if ($requestId->getDecision() === $this) {
                $requestId->setDecision(null);
            }
        }

        return $this;
    }

    public function getResultat(): ?string
    {
        return $this->resultat;
    }

    public function setResultat(string $resultat): static
    {
        $this->resultat = $resultat;

        return $this;
    }

    public function getMotif(): ?string
    {
        return $this->motif;
    }

    public function setMotif(string $motif): static
    {
        $this->motif = $motif;

        return $this;
    }

    public function getValidePar(): ?string
    {
        return $this->valide_par;
    }

    public function setValidePar(string $valide_par): static
    {
        $this->valide_par = $valide_par;

        return $this;
    }

    public function getValideLe(): ?\DateTime
    {
        return $this->valide_le;
    }

    public function setValideLe(\DateTime $valide_le): static
    {
        $this->valide_le = $valide_le;

        return $this;
    }

    public function getSignature(): ?Signature
    {
        return $this->signature;
    }

    public function setSignature(Signature $signature): static
    {
        // set the owning side of the relation if necessary
        if ($signature->getDecisionId() !== $this) {
            $signature->setDecisionId($this);
        }

        $this->signature = $signature;

        return $this;
    }
}
