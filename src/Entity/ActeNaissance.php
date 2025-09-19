<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'acte_naissance')]
class ActeNaissance
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

    #[ORM\Column(type: 'string', length: 10)]
    private string $sexe;

    #[ORM\Column(type: 'string', length: 100)]
    private string $nomPere;

    #[ORM\Column(type: 'string', length: 100)]
    private string $prenomPere;

    #[ORM\Column(type: 'string', length: 100)]
    private string $nomMere;

    #[ORM\Column(type: 'string', length: 100)]
    private string $prenomMere;

    #[ORM\Column(type: 'string', length: 255)]
    private string $commune;

    // --- Getters et Setters ---
    public function getId(): ?int { return $this->id; }

    public function getNom(): string { return $this->nom; }
    public function setNom(string $nom): self { $this->nom = $nom; return $this; }

    public function getPrenom(): string { return $this->prenom; }
    public function setPrenom(string $prenom): self { $this->prenom = $prenom; return $this; }

    public function getDateNaissance(): \DateTimeInterface { return $this->dateNaissance; }
    public function setDateNaissance(\DateTimeInterface $dateNaissance): self { $this->dateNaissance = $dateNaissance; return $this; }

    public function getLieuNaissance(): string { return $this->lieuNaissance; }
    public function setLieuNaissance(string $lieuNaissance): self { $this->lieuNaissance = $lieuNaissance; return $this; }

    public function getSexe(): string { return $this->sexe; }
    public function setSexe(string $sexe): self { $this->sexe = $sexe; return $this; }

    public function getNomPere(): string { return $this->nomPere; }
    public function setNomPere(string $nomPere): self { $this->nomPere = $nomPere; return $this; }

    public function getPrenomPere(): string { return $this->prenomPere; }
    public function setPrenomPere(string $prenomPere): self { $this->prenomPere = $prenomPere; return $this; }

    public function getNomMere(): string { return $this->nomMere; }
    public function setNomMere(string $nomMere): self { $this->nomMere = $nomMere; return $this; }

    public function getPrenomMere(): string { return $this->prenomMere; }
    public function setPrenomMere(string $prenomMere): self { $this->prenomMere = $prenomMere; return $this; }

    public function getCommune(): string { return $this->commune; }
    public function setCommune(string $commune): self { $this->commune = $commune; return $this; }
}
