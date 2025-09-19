<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity]
#[ORM\Table(name: 'acte_mariage')]
class ActeMariage
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    // Informations sur la commune et l'acte
    #[ORM\Column(type: 'string', length: 255)]
    private string $commune;

    #[ORM\Column(type: 'date')]
    private \DateTimeInterface $dateMariage;

    #[ORM\Column(type: 'string', length: 10, nullable: true)]
    private ?string $heureMariage = null;

    #[ORM\Column(type: 'string', length: 50, nullable: true)]
    private ?string $numeroActe = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $officierNom = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $officierTitre = null;

    // Informations époux
    #[ORM\Column(type: 'string', length: 100)]
    private string $epouxNom;

    #[ORM\Column(type: 'string', length: 100)]
    private string $epouxPrenom;

    #[ORM\Column(type: 'date')]
    private \DateTimeInterface $epouxDateNaissance;

    #[ORM\Column(type: 'string', length: 255)]
    private string $epouxLieuNaissance;

    #[ORM\Column(type: 'string', length: 100, nullable: true)]
    private ?string $epouxPereNom = null;

    #[ORM\Column(type: 'string', length: 100, nullable: true)]
    private ?string $epouxPerePrenom = null;

    #[ORM\Column(type: 'string', length: 100, nullable: true)]
    private ?string $epouxMereNom = null;

    #[ORM\Column(type: 'string', length: 100, nullable: true)]
    private ?string $epouxMerePrenom = null;

    #[ORM\Column(type: 'string', length: 100, nullable: true)]
    private ?string $epouxProfession = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $epouxDomicile = null;

    // Informations épouse
    #[ORM\Column(type: 'string', length: 100)]
    private string $epouseNom;

    #[ORM\Column(type: 'string', length: 100)]
    private string $epousePrenom;

    #[ORM\Column(type: 'date')]
    private \DateTimeInterface $epouseDateNaissance;

    #[ORM\Column(type: 'string', length: 255)]
    private string $epouseLieuNaissance;

    #[ORM\Column(type: 'string', length: 100, nullable: true)]
    private ?string $epousePereNom = null;

    #[ORM\Column(type: 'string', length: 100, nullable: true)]
    private ?string $epousePerePrenom = null;

    #[ORM\Column(type: 'string', length: 100, nullable: true)]
    private ?string $epouseMereNom = null;

    #[ORM\Column(type: 'string', length: 100, nullable: true)]
    private ?string $epouseMerePrenom = null;

    #[ORM\Column(type: 'string', length: 100, nullable: true)]
    private ?string $epouseProfession = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $epouseDomicile = null;

    // Témoins (JSON pour simplifier)
    #[ORM\Column(type: 'json', nullable: true)]
    private array $temoins = [];

    public function __construct()
    {
        $this->dateMariage = new \DateTime();
        $this->epouxDateNaissance = new \DateTime();
        $this->epouseDateNaissance = new \DateTime();
        $this->temoins = [];
    }

    // --- Getters et setters ---
    public function getId(): ?int { return $this->id; }
    public function getCommune(): string { return $this->commune; }
    public function setCommune(string $commune): self { $this->commune = $commune; return $this; }
    public function getDateMariage(): \DateTimeInterface { return $this->dateMariage; }
    public function setDateMariage(\DateTimeInterface $dateMariage): self { $this->dateMariage = $dateMariage; return $this; }
    public function getHeureMariage(): ?string { return $this->heureMariage; }
    public function setHeureMariage(?string $heureMariage): self { $this->heureMariage = $heureMariage; return $this; }
    public function getNumeroActe(): ?string { return $this->numeroActe; }
    public function setNumeroActe(?string $numeroActe): self { $this->numeroActe = $numeroActe; return $this; }
    public function getOfficierNom(): ?string { return $this->officierNom; }
    public function setOfficierNom(?string $officierNom): self { $this->officierNom = $officierNom; return $this; }
    public function getOfficierTitre(): ?string { return $this->officierTitre; }
    public function setOfficierTitre(?string $officierTitre): self { $this->officierTitre = $officierTitre; return $this; }

    public function getEpouxNom(): string { return $this->epouxNom; }
    public function setEpouxNom(string $nom): self { $this->epouxNom = $nom; return $this; }
    public function getEpouxPrenom(): string { return $this->epouxPrenom; }
    public function setEpouxPrenom(string $prenom): self { $this->epouxPrenom = $prenom; return $this; }
    public function getEpouxDateNaissance(): \DateTimeInterface { return $this->epouxDateNaissance; }
    public function setEpouxDateNaissance(\DateTimeInterface $date): self { $this->epouxDateNaissance = $date; return $this; }
    public function getEpouxLieuNaissance(): string { return $this->epouxLieuNaissance; }
    public function setEpouxLieuNaissance(string $lieu): self { $this->epouxLieuNaissance = $lieu; return $this; }
    public function getEpouxPereNom(): ?string { return $this->epouxPereNom; }
    public function setEpouxPereNom(?string $nom): self { $this->epouxPereNom = $nom; return $this; }
    public function getEpouxPerePrenom(): ?string { return $this->epouxPerePrenom; }
    public function setEpouxPerePrenom(?string $prenom): self { $this->epouxPerePrenom = $prenom; return $this; }
    public function getEpouxMereNom(): ?string { return $this->epouxMereNom; }
    public function setEpouxMereNom(?string $nom): self { $this->epouxMereNom = $nom; return $this; }
    public function getEpouxMerePrenom(): ?string { return $this->epouxMerePrenom; }
    public function setEpouxMerePrenom(?string $prenom): self { $this->epouxMerePrenom = $prenom; return $this; }
    public function getEpouxProfession(): ?string { return $this->epouxProfession; }
    public function setEpouxProfession(?string $profession): self { $this->epouxProfession = $profession; return $this; }
    public function getEpouxDomicile(): ?string { return $this->epouxDomicile; }
    public function setEpouxDomicile(?string $domicile): self { $this->epouxDomicile = $domicile; return $this; }

    public function getEpouseNom(): string { return $this->epouseNom; }
    public function setEpouseNom(string $nom): self { $this->epouseNom = $nom; return $this; }
    public function getEpousePrenom(): string { return $this->epousePrenom; }
    public function setEpousePrenom(string $prenom): self { $this->epousePrenom = $prenom; return $this; }
    public function getEpouseDateNaissance(): \DateTimeInterface { return $this->epouseDateNaissance; }
    public function setEpouseDateNaissance(\DateTimeInterface $date): self { $this->epouseDateNaissance = $date; return $this; }
    public function getEpouseLieuNaissance(): string { return $this->epouseLieuNaissance; }
    public function setEpouseLieuNaissance(string $lieu): self { $this->epouseLieuNaissance = $lieu; return $this; }
    public function getEpousePereNom(): ?string { return $this->epousePereNom; }
    public function setEpousePereNom(?string $nom): self { $this->epousePereNom = $nom; return $this; }
    public function getEpousePerePrenom(): ?string { return $this->epousePerePrenom; }
    public function setEpousePerePrenom(?string $prenom): self { $this->epousePerePrenom = $prenom; return $this; }
    public function getEpouseMereNom(): ?string { return $this->epouseMereNom; }
    public function setEpouseMereNom(?string $nom): self { $this->epouseMereNom = $nom; return $this; }
    public function getEpouseMerePrenom(): ?string { return $this->epouseMerePrenom; }
    public function setEpouseMerePrenom(?string $prenom): self { $this->epouseMerePrenom = $prenom; return $this; }
    public function getEpouseProfession(): ?string { return $this->epouseProfession; }
    public function setEpouseProfession(?string $profession): self { $this->epouseProfession = $profession; return $this; }
    public function getEpouseDomicile(): ?string { return $this->epouseDomicile; }
    public function setEpouseDomicile(?string $domicile): self { $this->epouseDomicile = $domicile; return $this; }

    public function getTemoins(): array { return $this->temoins; }
    public function setTemoins(array $temoins): self { $this->temoins = $temoins; return $this; }
    
    public function addTemoin(array $temoin): self
    {
        $this->temoins[] = $temoin;
        return $this;
    }
    
    public function removeTemoin(int $index): self
    {
        if (isset($this->temoins[$index])) {
            unset($this->temoins[$index]);
            $this->temoins = array_values($this->temoins);
        }
        return $this;
    }
}