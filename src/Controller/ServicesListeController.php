<?php

namespace App\Controller;

use App\Form\ServicesListeFormType;
use App\Entity\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request as HttpRequest;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;  // Import indispensable

class ServicesListeController extends AbstractController
{
    #[Route('/services-liste', name: 'services_liste_index')]
    public function index(HttpRequest $request): Response
    {
        $entity = new Request();

        $form = $this->createForm(ServicesListeFormType::class, $entity);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Traitement du formulaire ici
        }

        return $this->render('services_liste/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
