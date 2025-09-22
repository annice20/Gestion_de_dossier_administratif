<?php

namespace App\Controller;

use App\Entity\Decision;
use App\Entity\Signature;
use App\Repository\DecisionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DecisionController extends AbstractController
{
    #[Route('/decision', name: 'app_decision')]
    public function index(DecisionRepository $decisionRepository): Response
    {
        $decisions = $decisionRepository->getAllDecisions();

        return $this->render('decision/new.html.twig', [
            'decisions' => $decisions,
        ]);
    }
}
