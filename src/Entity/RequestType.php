<?php

namespace App\Entity;

use App\Repository\RequestTypeRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

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

    public function __construct()
    {
        $this->requests = new ArrayCollection();
    }

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

    // --------- JSON : Schema Formulaire ---------
    public function getSchemaFormulaire(): ?array
    {
        return $this->schema_formulaire ? json_decode($this->schema_formulaire, true) : null;
    }

    public function setSchemaFormulaire(array $schema_formulaire): self
    {
        $this->schema_formulaire = json_encode($schema_formulaire, JSON_UNESCAPED_UNICODE);
        return $this;
    }

    // --------- JSON : Pièce requise ---------
    public function getPieceRequise(): ?array
    {
        return $this->piece_requise ? json_decode($this->piece_requise, true) : null;
    }

    public function setPieceRequise(array $piece_requise): static
    {
        $this->piece_requise = json_encode($piece_requise, JSON_UNESCAPED_UNICODE);
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

    // --------- JSON : Workflow ---------
    public function getWorkflow(): ?array
    {
        return $this->workflow ? json_decode($this->workflow, true) : null;
    }

    public function setWorkflow(array $workflow): static
    {
        $this->workflow = json_encode($workflow, JSON_UNESCAPED_UNICODE);
        return $this;
    }

    // --------- Relation Request ---------
    /**
     * @return Collection<int, Request>
     */
    public function getRequests(): Collection
    {
        return $this->requests;
    }

    public function addRequest(Request $request): static
    {
        if (!$this->requests->contains($request)) {
            $this->requests->add($request);
            $request->setRequestType($this);
        }
        return $this;
    }

    public function removeRequest(Request $request): static
    {
        if ($this->requests->removeElement($request)) {
            if ($request->getRequestType() === $this) {
                $request->setRequestType(null);
            }
        }
        return $this;
    }
}
