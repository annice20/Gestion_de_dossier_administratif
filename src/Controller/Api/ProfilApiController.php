<?php

namespace App\Controller\Api;

use App\Entity\CitizenProfile;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class ProfilApiController extends AbstractController
{
    #[Route('/api/me', name: 'api_me', methods: ['GET'])]
    public function me(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        // Récupération du header Authorization
        $authHeader = $request->headers->get('Authorization');
        if (!$authHeader || !str_starts_with($authHeader, 'Bearer ')) {
            return $this->json(['error' => 'Token manquant'], 401);
        }

        // Extraction du token
        $token = substr($authHeader, 7);

        // Recherche de l'utilisateur correspondant au token
        $user = $entityManager->getRepository(User::class)->findOneBy(['apiToken' => $token]);
        if (!$user) {
            return $this->json(['error' => 'Token invalide'], 401);
        }

        // Récupération du profil lié à l’utilisateur
        $profile = $entityManager->getRepository(CitizenProfile::class)->findOneBy(['user' => $user]);
        if (!$profile) {
            return $this->json(['error' => 'Profil introuvable'], 404);
        }

        // Réponse JSON avec infos utilisateur + profil
        return $this->json([
            'id' => $user->getId(),
            'email' => $user->getEmail(),
            'nom' => $profile->getNom(),
            'prenoms' => $profile->getPrenoms(),
            'dateDeNaissance' => $profile->getDateDeNaissance()?->format('Y-m-d'),
            'telephone' => $user->getTelephone(),
            'adresse' => $profile->getAdresse(),
            'commune' => $profile->getCommune(),
            'langue' => $user->getLangue(),
            'nin' => $profile->getNin(),
        ]);
    }
}
