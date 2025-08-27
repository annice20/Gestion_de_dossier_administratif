<?php

namespace App\Entity;

use App\Repository\RequestTypeRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RequestTypeRepository::class)]
class RequestType
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $code = null;

    #[ORM\Column(length: 100)]
    private ?string $libelle = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $schema_formulaire = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $piece_requise = null;

    #[ORM\Column]
    private ?int $delais_cible = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $workflow = null;

    #[ORM\OneToMany(mappedBy: 'requestType', targetEntity: Request::class)]
    private Collection $requests;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): static
    {
        $this->code = $code;

        return $this;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): static
    {
        $this->libelle = $libelle;

        return $this;
    }

    public function getSchemaFormulaire(): ?string
    {
        return $this->schema_formulaire;
    }

    public function setSchemaFormulaire(string $schema_formulaire): static
    {
        $this->schema_formulaire = $schema_formulaire;

        return $this;
    }

    public function getPieceRequise(): ?string
    {
        return $this->piece_requise;
    }

    public function setPieceRequise(string $piece_requise): static
    {
        $this->piece_requise = $piece_requise;

        return $this;
    }

    public function getDelaisCible(): ?int
    {
        return $this->delais_cible;
    }

    public function setDelaisCible(int $delais_cible): static
    {
        $this->delais_cible = $delais_cible;

        return $this;
    }

    public function getWorkflow(): ?string
    {
        return $this->workflow;
    }

    public function setWorkflow(string $workflow): static
    {
        $this->workflow = $workflow;

        return $this;
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
}
