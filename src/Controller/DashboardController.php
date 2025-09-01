<?php
// src/Controller/DashboardController.php

namespace App\Controller;

use App\Repository\DocumentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    
    #[Route('/dashboard', name: 'app_dashboard')]
    public function index(DocumentRepository $documentRepository): Response
    {
        // 1. On récupère le nombre total de documents reçus
        $totalDocumentsRecus = $documentRepository->countAll();

        // 2. On récupère le nombre de documents en attente de traitement
        $documentsEnAttente = $documentRepository->countByStatus('en_attente');
        
        // 3. On récupère le nombre de documents reçus ce mois-ci
        $documentsRecusCeMois = $documentRepository->countReceivedThisMonth();

        // 4. On récupère le nombre de documents en retard
        $documentsEnRetard = $documentRepository->countOverdue();

        // On passe toutes ces données à la vue (le template Twig)
        return $this->render('dashboard/index.html.twig', [
            'totalDocumentsRecus' => $totalDocumentsRecus,
            'documentsEnAttente' => $documentsEnAttente,
            'documentsRecusCeMois' => $documentsRecusCeMois,
            'documentsEnRetard' => $documentsEnRetard,
        ]);
    }
}