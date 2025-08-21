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
    #[ORM\JoinColumn(nullable: false)]
    private ?Decision $decision_id = null;

    #[ORM\Column(length: 20)]
    private ?string $type = null;

    #[ORM\Column(length: 100)]
    private ?string $scelle = null;

    #[ORM\Column]
    private ?\DateTime $horodatage = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDecisionId(): ?Decision
    {
        return $this->decision_id;
    }

    public function setDecisionId(Decision $decision_id): static
    {
        $this->decision_id = $decision_id;

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

    public function getHorodatage(): ?\DateTime
    {
        return $this->horodatage;
    }

    public function setHorodatage(\DateTime $horodatage): static
    {
        $this->horodatage = $horodatage;

        return $this;
    }
}
