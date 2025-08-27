<?php

namespace App\Entity;

use App\Repository\AttachmentRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AttachmentRepository::class)]
class Attachment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    // Plusieurs pièces jointes appartiennent à une seule demande
    #[ORM\ManyToOne(targetEntity: Request::class, inversedBy: 'attachments')]
    private ?Request $request = null;

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
