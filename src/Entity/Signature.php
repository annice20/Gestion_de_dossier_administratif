<?php

namespace App\Entity;

use App\Repository\SignatureRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SignatureRepository::class)]
class Signature
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(inversedBy: 'signature', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private ?Decision $decision = null;

    #[ORM\Column(length: 20)]
    private ?string $type = null;

    #[ORM\Column(length: 100)]
    private ?string $scelle = null;

    #[ORM\Column(type: 'datetime_immutable')]
    private ?\DateTimeImmutable $horodatage = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDecision(): ?Decision
    {
        return $this->decision;
    }

    public function setDecision(Decision $decision): static
    {
        $this->decision = $decision;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getScelle(): ?string
    {
        return $this->scelle;
    }

    public function setScelle(string $scelle): static
    {
        $this->scelle = $scelle;

        return $this;
    }

    public function getHorodatage(): ?\DateTimeImmutable
    {
        return $this->horodatage;
    }

    public function setHorodatage(\DateTimeImmutable $horodatage): static
    {
        $this->horodatage = $horodatage;

        return $this;
    }
}
