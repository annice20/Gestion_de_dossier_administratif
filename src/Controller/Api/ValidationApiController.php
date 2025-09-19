<?php

namespace App\Controller\Api;

use App\Entity\User;
use App\Entity\CodeValidation;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

final class ValidationApiController extends AbstractController
{
    private $repositoryuser;

    public function __construct(UserRepository $repositoryuser) {
        $this->repositoryuser = $repositoryuser;
    }

    #[Route('/api/code/validate', name: 'api_code_validate', methods: ['POST'])]
    public function validateCode(Request $request, EntityManagerInterface $entityManager): Response
    {
        $data = json_decode($request->getContent(), true);

        if (!$data || !isset($data['user_id'], $data['code'])) {
            return new JsonResponse([
                'success' => false,
                'message' => 'Paramètres manquants.'
            ], Response::HTTP_BAD_REQUEST);
        }

        $userId = $data['user_id'];
        $code = $data['code'];

        // Vérifier que l’utilisateur existe
        $user = $this->repositoryuser->find($userId);
        if (!$user) {
            return new JsonResponse([
                'success' => false,
                'message' => 'Utilisateur introuvable.'
            ], Response::HTTP_NOT_FOUND);
        }

        // Chercher le code de validation
        $validationCode = $entityManager->getRepository(CodeValidation::class)->findOneBy([
            'user' => $user,
            'code' => $code,
            'is_used' => false
        ]);

        if ($validationCode && $validationCode->getExpiresAt() >= new \DateTime()) {
            // Code valide → on le marque comme utilisé
            $validationCode->setIsUsed(true);
            $entityManager->flush();

            return new JsonResponse([
                'success' => true,
                'message' => 'Code correct !'
            ]);
        }

        return new JsonResponse([
            'success' => false,
            'message' => 'Code invalide ou expiré.'
        ], Response::HTTP_UNAUTHORIZED);
    }
}
