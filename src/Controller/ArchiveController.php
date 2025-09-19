<?php

namespace App\Controller;

use App\Repository\RequestRepository;
use App\Entity\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArchiveController extends AbstractController
{
    #[Route('/archives', name: 'app_archives')]
    public function index(RequestRepository $requestRepository): Response
    {
        // Récupère uniquement les demandes archivées
        $archives = $requestRepository->findArchives();

        return $this->render('archive/index.html.twig', [
            'archives' => $archives,
        ]);
    }

    #[Route('/archiver-demande/{id}', name: 'app_archive_request')]
    public function archiveRequest(Request $request, EntityManagerInterface $entityManager): Response
    {
        if (!$request) {
            throw $this->createNotFoundException('Demande non trouvée');
        }

        // Marquer la demande comme archivée
        $request->setStatut('archivé');
        $request->setUpdatedAt(new \DateTimeImmutable());

        $entityManager->flush();

        $this->addFlash('success', 'La demande a été archivée avec succès.');

        return $this->redirectToRoute('app_archives');
    }

    #[Route('/restaurer-demande/{id}', name: 'app_restore_request')]
    public function restoreRequest(Request $request, EntityManagerInterface $entityManager): Response
    {
        if (!$request) {
            throw $this->createNotFoundException('Demande non trouvée');
        }

        // Restaurer la demande (statut par défaut)
        $request->setStatut('nouveau');
        $request->setUpdatedAt(new \DateTimeImmutable());

        $entityManager->flush();

        $this->addFlash('success', 'La demande a été restaurée avec succès.');

        return $this->redirectToRoute('app_archives');
    }

    #[Route('/archiver-toutes-demandes', name: 'app_archive_all_requests')]
    public function archiveAllRequests(RequestRepository $requestRepository, EntityManagerInterface $entityManager): Response
    {
        // Récupérer toutes les demandes non archivées
        $requests = $requestRepository->findBy(['statut' => ['nouveau', 'en_cours', 'traite']]);

        $count = 0;
        foreach ($requests as $request) {
            $request->setStatut('archivé');
            $request->setUpdatedAt(new \DateTimeImmutable());
            $count++;
        }

        $entityManager->flush();

        $this->addFlash('success', $count . ' demandes ont été archivées avec succès.');

        return $this->redirectToRoute('app_archives');
    }
}
