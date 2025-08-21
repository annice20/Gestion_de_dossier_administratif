<?php

namespace App\Entity;

use App\Repository\AttachmentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AttachmentRepository::class)]
class Attachment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * @var Collection<int, Request>
     */
    #[ORM\OneToMany(targetEntity: Request::class, mappedBy: 'attachment')]
    private Collection $request_id;

    #[ORM\Column(length: 50)]
    private ?string $type_piece = null;

    #[ORM\Column(length: 150)]
    private ?string $nom_fichier = null;

    #[ORM\Column(length: 64)]
    private ?string $hash = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $url = null;

    #[ORM\Column]
    private ?int $taille = null;

    #[ORM\Column(length: 20)]
    private ?string $verif_statut = null;

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
            $requestId->setAttachment($this);
        }

        return $this;
    }

    public function removeRequestId(Request $requestId): static
    {
        if ($this->request_id->removeElement($requestId)) {
            // set the owning side to null (unless already changed)
            if ($requestId->getAttachment() === $this) {
                $requestId->setAttachment(null);
            }
        }

        return $this;
    }

    public function getTypePiece(): ?string
    {
        return $this->type_piece;
    }

    public function setTypePiece(string $type_piece): static
    {
        $this->type_piece = $type_piece;

        return $this;
    }

    public function getNomFichier(): ?string
    {
        return $this->nom_fichier;
    }

    public function setNomFichier(string $nom_fichier): static
    {
        $this->nom_fichier = $nom_fichier;

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

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): static
    {
        $this->url = $url;

        return $this;
    }

    public function getTaille(): ?int
    {
        return $this->taille;
    }

    public function setTaille(int $taille): static
    {
        $this->taille = $taille;

        return $this;
    }

    public function getVerifStatut(): ?string
    {
        return $this->verif_statut;
    }

    public function setVerifStatut(string $verif_statut): static
    {
        $this->verif_statut = $verif_statut;

        return $this;
    }
}
