<?php

namespace App\Controller;

use App\Entity\RequestType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReceptionController extends AbstractController
{
    #[Route('/reception', name: 'reception_liste')]
    public function reception(EntityManagerInterface $entityManager): Response
    {
        // Récupère toutes les entités RequestType en base
        $requests = $entityManager->getRepository(RequestType::class)->findAll();

        // Rend la vue et transmet les données
        return $this->render('reception/index.html.twig', [
            'requests' => $requests,
        ]);
    }
}
