<?php

namespace App\Controller;

use App\Form\ActedecesType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ActeDecesController extends AbstractController
{
    /**
     * @Route("/formulaire/deces", name="formulaire_deces")
     */
    public function formulaire(Request $request): Response
    {
        $form = $this->createForm(ActedecesType::class);
        
        return $this->render('form/modal_deces.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}