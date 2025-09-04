<?php

namespace App\Controller;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request as HttpRequest;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

// ----------------- Entity -----------------
#[ORM\Entity]
class RequestEntity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $ref = null;

    #[ORM\Column(length: 20)]
    private ?string $statut = null;

    #[ORM\Column(length: 50)]
    private ?string $centre = null;

    #[ORM\Column]
    private ?int $priorite = null;

    public function getId(): ?int { return $this->id; }
    public function getRef(): ?string { return $this->ref; }
    public function setRef(string $ref): static { $this->ref = $ref; return $this; }

    public function getStatut(): ?string { return $this->statut; }
    public function setStatut(string $statut): static { $this->statut = $statut; return $this; }

    public function getCentre(): ?string { return $this->centre; }
    public function setCentre(string $centre): static { $this->centre = $centre; return $this; }

    public function getPriorite(): ?int { return $this->priorite; }
    public function setPriorite(int $priorite): static { $this->priorite = $priorite; return $this; }
}

// ----------------- Controller API -----------------
class RequestApiController extends AbstractController
{
    #[Route('/api/requests', name: 'api_requests', methods: ['GET','POST'])]
    public function api(HttpRequest $request, EntityManagerInterface $em): JsonResponse
    {
        if ($request->isMethod('POST')) {
            $data = json_decode($request->getContent(), true);

            $requestEntity = new RequestEntity();
            $requestEntity->setRef($data['ref'] ?? '');
            $requestEntity->setStatut('nouveau');
            $requestEntity->setCentre($data['centre'] ?? '');
            $requestEntity->setPriorite($data['priorite'] ?? 0);

            $em->persist($requestEntity);
            $em->flush();

            return $this->json([
                'status' => 'success',
                'request_id' => $requestEntity->getId()
            ], 201);
        }

        // GET: lister toutes les demandes
        $repo = $em->getRepository(RequestEntity::class);
        $requests = $repo->findAll();

        $result = array_map(fn($r) => [
            'id' => $r->getId(),
            'ref' => $r->getRef(),
            'statut' => $r->getStatut(),
            'centre' => $r->getCentre(),
            'priorite' => $r->getPriorite()
        ], $requests);

        return $this->json($result);
    }
}
