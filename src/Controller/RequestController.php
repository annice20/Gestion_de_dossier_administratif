<?php
namespace App\Controller;

use App\Entity\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request as HttpRequest;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RequestController extends AbstractController
{
    #[Route('/demande/{id}', name: 'request_show')]
    public function show(Request $requestEntity): Response
    {
        return $this->render('request/show.html.twig', ['request' => $requestEntity]);
    }

    #[Route('/demande/{id}/update-statut', name: 'request_update_statut', methods: ['POST'])]
    public function updateStatut(Request $requestEntity, HttpRequest $request, EntityManagerInterface $em): Response
    {
        $statut = $request->request->get('statut');
        if (!in_array($statut, ['Validé', 'Refusé', ''])) {
            $this->addFlash('error', 'Statut invalide.');
            return $this->redirectToRoute('request_show', ['id' => $requestEntity->getId()]);
        }

        $requestEntity->setStatut($statut ?: null);
        $em->flush();

        $this->addFlash('success', 'Statut mis à jour avec succès.');
        return $this->redirectToRoute('request_show', ['id' => $requestEntity->getId()]);
    }
}
