<?php

namespace App\Entity;

use App\Repository\DocumentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DocumentRepository::class)]
class Document
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Request::class, inversedBy: 'document')]
    private Collection $request;

    #[ORM\Column(length: 50)]
    private ?string $type = null;

    #[ORM\Column(length: 20)]
    private ?string $version = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $url_pdf = null;

    #[ORM\Column(length: 64)]
    private ?string $hash = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $qr_payload = null;

    // --- Champs ajoutés pour le tableau de bord ---

    #[ORM\Column(length: 255)]
    private ?string $status = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateReception = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dueDate = null;

    // --- Fin des champs ajoutés ---

    public function __construct()
    {
        $this->request = new ArrayCollection();
    }

    // --- Getters and Setters existants ---

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRequest(): Collection
    {
        return $this->request;
    }

    public function setRequest(Collection $request): static
    {
        $this->request = $request;
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

    public function getVersion(): ?string
    {
        return $this->version;
    }

    public function setVersion(string $version): static
    {
        $this->version = $version;
        return $this;
    }

    public function getUrlPdf(): ?string
    {
        return $this->url_pdf;
    }

    public function setUrlPdf(string $url_pdf): static
    {
        $this->url_pdf = $url_pdf;
        return $this;
    }

    public function getHash(): ?string
    {
        return $this->hash;
    }

    public function setHash(string $hash): static
    {
        $this->hash = $hash;
        return $this;
    }

    public function getQrPayload(): ?string
    {
        return $this->qr_payload;
    }

    public function setQrPayload(string $qr_payload): static
    {
        $this->qr_payload = $qr_payload;
        return $this;
    }

    // --- Getters and Setters pour les nouveaux champs ---
    
    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $status): static
    {
        $this->status = $status;
        return $this;
    }

    public function getDateReception(): ?\DateTimeInterface
    {
        return $this->dateReception;
    }

    public function setDateReception(\DateTimeInterface $dateReception): static
    {
        $this->dateReception = $dateReception;
        return $this;
    }

    public function getDueDate(): ?\DateTimeInterface
    {
        return $this->dueDate;
    }

    public function setDueDate(?\DateTimeInterface $dueDate): static
    {
        $this->dueDate = $dueDate;
        return $this;
    }
}