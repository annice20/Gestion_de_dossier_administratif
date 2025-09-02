<?php

namespace App\Controller;

use App\Form\ServicesListeFormType;
use App\Entity\Request;
use App\Repository\RequestRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request as HttpRequest;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ServicesListeController extends AbstractController
{
    #[Route('/services-liste', name: 'services_liste_index')]
    public function index(HttpRequest $request, EntityManagerInterface $entityManager, RequestRepository $requestRepository): Response
    {
        $requestEntity = new Request();
        $form = $this->createForm(ServicesListeFormType::class, $requestEntity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $requestEntity->setStatut('nouveau');
            $entityManager->persist($requestEntity);
            $entityManager->flush();

            $this->addFlash('success', 'La demande a été créée avec succès.');

            // Rediriger vers la page pour ajouter les pièces jointes à cette demande
            return $this->redirectToRoute('app_piece_jointe', ['request_id' => $requestEntity->getId()]);
        }

        $archivedCount = $requestRepository->archiveOldRequests(30); // Archive après 30 jours
        if ($archivedCount > 0) {
            $this->addFlash('info', $archivedCount . ' anciennes demandes ont été archivées automatiquement.');
        }

        return $this->render('services_liste/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
