<?php

namespace App\Entity;

use App\Repository\DecisionRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DecisionRepository::class)]
class Decision
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Request::class, inversedBy: 'decisions')]
    #[ORM\JoinColumn(name: "request_id", referencedColumnName: "id", nullable: false)]
    private ?Request $request = null;

    #[ORM\Column(length: 20)]
    private ?string $resultat = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $motif = null;

    #[ORM\Column(length: 100)]
    private ?string $validePar = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $valideLe = null;

    #[ORM\OneToOne(mappedBy: 'decision', cascade: ['persist', 'remove'])]
    private ?Signature $signature = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRequest(): ?Request
    {
        return $this->request;
    }

    public function setRequest(?Request $request): static
    {
        $this->request = $request;

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
        return $this->validePar;
    }

    public function setValidePar(string $validePar): static
    {
        $this->validePar = $validePar;

        return $this;
    }

    public function getValideLe(): ?\DateTimeInterface
    {
        return $this->valideLe;
    }

    public function setValideLe(\DateTimeInterface $valideLe): static
    {
        $this->valideLe = $valideLe;

        return $this;
    }

    public function getSignature(): ?Signature
    {
        return $this->signature;
    }

    public function setSignature(Signature $signature): static
    {
        if ($signature->getDecision() !== $this) {
            $signature->setDecision($this);
        }

        $this->signature = $signature;

        return $this;
    }
}
