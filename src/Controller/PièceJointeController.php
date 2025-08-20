<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class PièceJointeController extends AbstractController
{
    #[Route('/pi/ce/jointe', name: 'app_pi_ce_jointe')]
    public function index(): Response
    {
        return $this->render('pièce_jointe/index.html.twig', [
            'controller_name' => 'PièceJointeController',
        ]);
    }
}
