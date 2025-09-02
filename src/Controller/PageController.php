<?php

namespace App\Controller;

use App\Entity\CitizenProfile;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class PageController extends AbstractController
{
    #[Route('/page', name: 'app_page')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        // Récupérer l'utilisateur connecté
        $user = $this->getUser();

        if (!$user) {
            // Si personne n'est connecté → redirection vers /login
            return $this->redirectToRoute('app_login');
        }

        // Récupérer le profil lié à cet utilisateur
        $profile = $entityManager
            ->getRepository(CitizenProfile::class)
            ->findOneBy(['user' => $user]);
            
        return $this->render('page/index.html.twig', [
            'user' => $user,
            'profile' => $profile,
        ]);
    }
}
