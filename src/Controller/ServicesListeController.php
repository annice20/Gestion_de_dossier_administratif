<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ServicesListeController extends AbstractController
{
    #[Route('/services/liste', name: 'app_services_liste')]
    public function index(): Response
    {
        return $this->render('services_liste/index.html.twig', [
            'controller_name' => 'ServicesListeController',
        ]);
    }
}
