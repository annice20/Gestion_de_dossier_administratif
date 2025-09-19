<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'acte_deces')]
class ActeDeces
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 100)]
    private string $nom;

    #[ORM\Column(type: 'string', length: 100)]
    private string $prenom;

    #[ORM\Column(type: 'date')]
    private \DateTimeInterface $dateNaissance;

    #[ORM\Column(type: 'string', length: 255)]
    private string $lieuNaissance;

    #[ORM\Column(type: 'date')]
    private \DateTimeInterface $dateDeces;

    #[ORM\Column(type: 'string', length: 255)]
    private string $lieuDeces;

    #[ORM\Column(type: 'string', length: 255)]
    private string $causeDeces;

    #[ORM\Column(type: 'string', length: 255)]
    private string $commune;

    // --- Getters et Setters ---

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;
        return $this;
    }

    public function getPrenom(): string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;
        return $this;
    }

    public function getDateNaissance(): \DateTimeInterface
    {
        return $this->dateNaissance;
    }

    public function setDateNaissance(\DateTimeInterface $dateNaissance): self
    {
        $this->dateNaissance = $dateNaissance;
        return $this;
    }

    public function getLieuNaissance(): string
    {
        return $this->lieuNaissance;
    }

    public function setLieuNaissance(string $lieuNaissance): self
    {
        $this->lieuNaissance = $lieuNaissance;
        return $this;
    }

    public function getDateDeces(): \DateTimeInterface
    {
        return $this->dateDeces;
    }

    public function setDateDeces(\DateTimeInterface $dateDeces): self
    {
        $this->dateDeces = $dateDeces;
        return $this;
    }

    public function getLieuDeces(): string
    {
        return $this->lieuDeces;
    }

    public function setLieuDeces(string $lieuDeces): self
    {
        $this->lieuDeces = $lieuDeces;
        return $this;
    }

    public function getCauseDeces(): string
    {
        return $this->causeDeces;
    }

    public function setCauseDeces(string $causeDeces): self
    {
        $this->causeDeces = $causeDeces;
        return $this;
    }

    public function getCommune(): string
    {
        return $this->commune;
    }

    public function setCommune(string $commune): self
    {
        $this->commune = $commune;
        return $this;
    }
}
