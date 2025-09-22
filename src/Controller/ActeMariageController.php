<?php

namespace App\Controller;

use App\Form\ActeMariageType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ActeMariageController extends AbstractController
{
    /**
     * @Route("/formulaire/mariage", name="formulaire_mariage")
     */
    public function formulaire(Request $request): Response
    {
        $form = $this->createForm(ActeMariageType::class);
        
        return $this->render('form/modal_mariage.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}