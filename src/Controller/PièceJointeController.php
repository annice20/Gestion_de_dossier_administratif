<?php

namespace App\Controller;

use App\Entity\Attachment;
use App\Form\AttachmentType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class PièceJointeController extends AbstractController
{
    #[Route('/piece/jointe', name: 'app_piece_jointe')]
    public function index(Request $request, EntityManagerInterface $em): Response
    {
        // Crée un nouvel objet Attachment
        $attachment = new Attachment();

        // Crée le formulaire lié à l'objet
        $form = $this->createForm(AttachmentType::class, $attachment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Enregistre en base
            $em->persist($attachment);
            $em->flush();

            $this->addFlash('success', 'Pièce jointe enregistrée avec succès ✅');

            // Redirection après enregistrement
            return $this->redirectToRoute('app_piece_jointe');
        }

        return $this->render('pièce_jointe/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
