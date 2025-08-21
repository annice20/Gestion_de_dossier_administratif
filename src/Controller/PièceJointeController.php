<?php

namespace App\Controller;

use App\Form\AttachmentType; // 👈 Changez le nom de la classe importée
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;

final class PièceJointeController extends AbstractController
{
    #[Route('/piece/jointe', name: 'app_pi_ce_jointe')]
    public function index(Request $request): Response
    {
        // 1. Créez le formulaire en utilisant le nom de classe correct
        $form = $this->createForm(AttachmentType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Traitez les données ici
        }

        return $this->render('pièce_jointe/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}