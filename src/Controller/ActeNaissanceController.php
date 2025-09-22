<?php

namespace App\Controller;

use App\Form\ActeNaissanceType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ActeNaissanceController extends AbstractController
{
    /**
     * @Route("/formulaire/naissance", name="formulaire_naissance")
     */
    public function formulaire(Request $request): Response
    {
        $form = $this->createForm(ActeNaissanceType::class);
        
        return $this->render('form/modal_naissance.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}