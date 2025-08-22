<?php

namespace App\Entity;

use App\Repository\PaymentRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PaymentRepository::class)]
class Payment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;
    
    #[ORM\ManyToOne(targetEntity: Request::class, inversedBy: 'payments')]
    private ?Request $request = null;
    
    #[ORM\Column(length: 20)]
    private ?string $mode = null;

    #[ORM\Column(length: 50)]
    private ?string $reference = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 12, scale: 2)]
    private ?string $montant = null;

    #[ORM\Column(length: 20)]
    private ?string $statut = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $recu_url = null;

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
    
    // Les autres méthodes de votre entité
    public function getMode(): ?string
    {
        return $this->mode;
    }

    public function setMode(string $mode): static
    {
        $this->mode = $mode;
        return $this;
    }

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(string $reference): static
    {
        $this->reference = $reference;
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

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(string $statut): static
    {
        $this->statut = $statut;
        return $this;
    }

    public function getRecuUrl(): ?string
    {
        return $this->recu_url;
    }

    public function setRecuUrl(string $recu_url): static
    {
        $this->recu_url = $recu_url;
        return $this;
    }
}