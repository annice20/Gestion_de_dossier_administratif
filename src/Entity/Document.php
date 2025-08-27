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

    /**
     * @var Collection<int, Request>
     */
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

    public function __construct()
    {
        $this->request_id = new ArrayCollection();
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
            $requestId->setDocument($this);
        }

        return $this;
    }

    public function removeRequestId(Request $requestId): static
    {
        if ($this->request_id->removeElement($requestId)) {
            // set the owning side to null (unless already changed)
            if ($requestId->getDocument() === $this) {
                $requestId->setDocument(null);
            }
        }

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
}
