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
            $requestEntity->setStatut('En attente');
            $entityManager->persist($requestEntity);
            $entityManager->flush();

            $this->addFlash('success', 'La demande a été créée avec succès.');

            // Redirection vers l'ajout de pièces jointes
            return $this->redirectToRoute('app_piece_jointe', ['request_id' => $requestEntity->getId()]);
        }

        // Archiver automatiquement les requêtes de plus de 30 jours
        $archivedCount = $requestRepository->archiveOldRequests(30);
        if ($archivedCount > 0) {
            $this->addFlash('info', $archivedCount . ' anciennes demandes ont été archivées automatiquement.');
        }
        // Dans votre contrôleur
if ($form->isSubmitted() && $form->isValid()) {
    $data = $form->getData();
    
    // Récupérer les données des formulaires modaux
    $naissanceData = json_decode($request->request->get('form_naissance_data'), true);
    $mariageData = json_decode($request->request->get('form_mariage_data'), true);
    $decesData = json_decode($request->request->get('form_deces_data'), true);
    
    // Traiter les données selon le type sélectionné
    switch ($data->getType()) {
        case 'naissance':
            // Traiter les données de naissance
            break;
        case 'mariage':
            // Traiter les données de mariage
            break;
        case 'deces':
            // Traiter les données de décès
            break;
    }
    
    // ... reste du traitement
}

        return $this->render('services_liste/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
