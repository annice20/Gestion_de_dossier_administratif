<?php

namespace App\Controller;

use App\Repository\DocumentRepository;
use App\Repository\RequestRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    #[Route('/dashboard', name: 'app_dashboard')]
    public function index(DocumentRepository $documentRepository, RequestRepository $requestRepository): Response
    {
        $totalDocumentsRecus = $documentRepository->countAll();
        $documentsEnAttente = $documentRepository->countByStatus('en_attente');

        $totalDemandes = $requestRepository->countAll();
        $demandesEnAttente = $requestRepository->countByStatus('en_attente');

        return $this->render('dashboard/index.html.twig', [
            'totalDocumentsRecus' => $totalDocumentsRecus + $totalDemandes,
            'documentsEnAttente' => $documentsEnAttente + $demandesEnAttente,
        ]);
    }

    // API pour évolution 7 derniers jours
    #[Route('/api/dashboard-data-week', name: 'api_dashboard_data_week')]
    public function apiDataWeek(DocumentRepository $documentRepository, RequestRepository $requestRepository): JsonResponse
    {
        $dates = [];
        $dataDocuments = [];
        $dataDemandes = [];

        for ($i = 6; $i >= 0; $i--) {
            $day = new \DateTime("-$i days");
            $dates[] = $day->format('d/m');
            $dataDocuments[] = $documentRepository->countByDate($day);
            $dataDemandes[] = $requestRepository->countByDate($day);
        }

        return $this->json([
            'dates' => $dates,
            'dataDocuments' => $dataDocuments,
            'dataDemandes' => $dataDemandes
        ]);
    }

    // API pour évolution 6 derniers mois
    #[Route('/api/dashboard-data-month', name: 'api_dashboard_data_month')]
    public function apiDataMonth(DocumentRepository $documentRepository, RequestRepository $requestRepository): JsonResponse
    {
        $dates = [];
        $dataDocuments = [];
        $dataDemandes = [];

        for ($i = 5; $i >= 0; $i--) {
            $month = new \DateTime("first day of -$i month");
            $dates[] = $month->format('M/Y');
            $dataDocuments[] = $documentRepository->countByMonth($month);
            $dataDemandes[] = $requestRepository->countByMonth($month);
        }

        return $this->json([
            'dates' => $dates,
            'dataDocuments' => $dataDocuments,
            'dataDemandes' => $dataDemandes
        ]);
    }

    #[Route('/dashboard/pilotage', name: 'app_dashboard_pilotage')]
    public function pilotage(): Response
    {
        return $this->render('dashboard/pilotage_evolution.html.twig');
    }
}
