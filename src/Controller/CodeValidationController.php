<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class CodeValidationController extends AbstractController
{
    #[Route('/code/validation', name: 'app_code_validation')]
    public function index(): Response
    {
        return $this->render('code_validation/index.html.twig', [
            'controller_name' => 'CodeValidationController',
        ]);
    }
}
