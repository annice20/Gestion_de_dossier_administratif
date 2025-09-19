<?php

namespace App\Controller;

use App\Service\NotificationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class NotificationController extends AbstractController
{
    public function __construct(private NotificationService $notificationService) {}

    #[Route('/notifications/unread', name: 'app_get_unread_notifications', methods: ['GET'])]
    public function getUnread(): JsonResponse
    {
        $user = $this->getUser();
        if (!$user) {
            // Pas d'utilisateur connecté => renvoyer tableau vide
            return $this->json([]);
        }

        return $this->json($this->notificationService->getUnreadNotifications($user));
    }

    #[Route('/notifications/mark-as-read', name: 'app_mark_notifications_as_read', methods: ['POST'])]
    public function markAsRead(): JsonResponse
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->json(['status' => 'error', 'message' => 'Utilisateur non connecté.'], 403);
        }

        $this->notificationService->markNotificationsAsRead($user);
        return $this->json(['status' => 'success']);
    }
}
