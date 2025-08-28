<?php

namespace App\Controller;

use App\Form\ServicesListeFormType;
use App\Entity\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request as HttpRequest;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ServicesListeController extends AbstractController
{
    #[Route('/services-liste', name: 'services_liste_index')]
    public function index(HttpRequest $request, EntityManagerInterface $entityManager): Response
    {
        $requestEntity = new Request();

        $form = $this->createForm(ServicesListeFormType::class, $requestEntity);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Sauvegarde de l'entité
            $entityManager->persist($requestEntity);
            $entityManager->flush();

            // Message de confirmation
            $this->addFlash('success', 'La demande a été créée avec succès.');

            // Redirection pour éviter la resoumission du formulaire
            return $this->redirectToRoute('services_liste_index');
        }

        return $this->render('services_liste/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}