<?php
// src/Controller/FilAttenteController.php
namespace App\Controller;

use App\Entity\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request as HttpRequest;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FileAttenteController extends AbstractController
{
    #[Route('/fil-attente', name: 'fil_attente_index')]
    public function index(EntityManagerInterface $em): Response
    {
        $requests = $em->getRepository(Request::class)
            ->findBy([], ['priorite' => 'DESC', 'createdAt' => 'ASC']);

        return $this->render('file_attente/index.html.twig', [
            'requests' => $requests
        ]);
    }

    #[Route('/fil-attente/{id}/statut', name: 'fil_attente_update_statut', methods: ['POST'])]
    public function updateStatut(Request $requestEntity, HttpRequest $request, EntityManagerInterface $em): Response
    {
        $statut = $request->request->get('statut');
        if (!in_array($statut, ['Validé', 'Refusé'])) {
            $this->addFlash('error', 'Statut non valide.');
            return $this->redirectToRoute('fil_attente_index');
        }

        $requestEntity->setStatut($statut);
        $em->flush();

        $this->addFlash('success', 'Statut mis à jour avec succès.');
        return $this->redirectToRoute('fil_attente_index');
    }
}
