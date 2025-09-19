<?php

namespace App\Controller;

use App\Entity\Request as UserRequest;
use App\Entity\User;
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
    public function updateStatus(int $id, string $statut): JsonResponse
    {
        if (!in_array($statut, ['accepte', 'refuse'])) {
            return $this->json(['status' => 'error', 'message' => 'Statut non valide.'], 400);
        }

        // Récupérer la requête depuis la base
        $userRequest = $this->entityManager->getRepository(UserRequest::class)->find($id);
        if (!$userRequest) {
            return $this->json(['status' => 'error', 'message' => 'Requête introuvable.'], 404);
        }

        // Récupérer l'utilisateur associé à la demande
        $user = $userRequest->getDemandeur()?->getUser();
        if (!$user || !$user->getEmail()) {
            return $this->json(['status' => 'error', 'message' => 'Email de l\'utilisateur introuvable.'], 404);
        }

        try {
            $message = $statut === 'accepte' ? 'Votre demande a été acceptée.' : 'Votre demande a été refusée.';

            // Notification email
            $this->notificationService->sendEmailNotification($user->getEmail(), $message, $userRequest->getRef());

            // Notification + décision avec le statut
            $this->notificationService->createNotificationAndDecision(
                $user,
                $userRequest,
                'Mise à jour de votre demande',
                $message,
                $statut // <-- important pour que "resultat" soit correct
            );

            // Mise à jour du statut
            $userRequest->setStatut($statut === 'accepte' ? 'Validé' : 'Refusé');
            $this->entityManager->flush();

            return $this->json([
                'status' => 'success',
                'message' => 'Statut mis à jour, notification et décision créées.'
            ]);

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
    public function show(UserRequest $request): Response
    {
        return $this->render('request/show.html.twig', ['request' => $request]);
    }
}
