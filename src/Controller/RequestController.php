<?php

namespace App\Controller;

use App\Entity\Request;
use App\Service\NotificationService;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class RequestController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private NotificationService $notificationService,
        private LoggerInterface $logger,
    ) {}

    #[Route('/demande/{id}/statut/{statut}', name: 'app_request_update_status', methods: ['POST'])]
    public function updateStatus(Request $request, string $statut): JsonResponse
    {
        if (!in_array($statut, ['accepte', 'refuse'])) {
            return $this->json(['status' => 'error', 'message' => 'Statut non valide.'], 400);
        }

        $user = $request->getDemandeur()?->getUser();
        if (!$user || !$user->getEmail()) {
            return $this->json(['status' => 'error', 'message' => 'Email de l\'utilisateur introuvable.'], 404);
        }

        try {
            $message = $statut === 'accepte' ? 'Votre demande a été acceptée.' : 'Votre demande a été refusée.';

            // Notification email
            $this->notificationService->sendEmailNotification($user->getEmail(), $message, $request->getRef());

            // Notification base
            $this->notificationService->createAndSaveNotification($user, 'Mise à jour de votre demande', $message);

            // Mise à jour du statut
            $request->setStatut($statut === 'accepte' ? 'Validé' : 'Refusé');
            $this->entityManager->flush();

            return $this->json(['status' => 'success', 'message' => 'Statut mis à jour et notification envoyée.']);

        } catch (\Exception $e) {
            $this->logger->error('Erreur notification : ' . $e->getMessage());

            return $this->json([
                'status' => 'error',
                'message' => 'Erreur lors de la mise à jour ou de l\'envoi de la notification.',
                'details' => $e->getMessage()
            ], 500);
        }
    }

    #[Route('/request/{id}', name: 'request_show', methods: ['GET'])]
    public function show(Request $request): Response
    {
        return $this->render('request/show.html.twig', ['request' => $request]);
    }
}
