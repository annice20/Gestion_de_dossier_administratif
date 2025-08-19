<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class CouvreController extends AbstractController
{
    #[Route('/couvre', name: 'app_couvre')]
    public function index(): Response
    {
        return $this->render('couvre/index.html.twig', [
            'controller_name' => 'CouvreController',
        ]);
    }
}
