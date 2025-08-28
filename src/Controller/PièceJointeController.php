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
        $attachment = new Attachment();

        $form = $this->createForm(AttachmentType::class, $attachment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $file = $form->get('file')->getData();

            if ($file) {
                // Récupère la taille AVANT déplacement
                $size = $file->getSize();

                // Récupère le nom saisi dans le formulaire
                $userFileName = $attachment->getNomFichier();

                // Si l'utilisateur n'a rien saisi, prendre le nom original du fichier
                if (empty($userFileName)) {
                    $userFileName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                }

                // Nettoyer le nom pour enlever caractères invalides
                $safeFileName = preg_replace('/[^a-zA-Z0-9_-]/', '_', $userFileName);

                $extension = $file->guessExtension() ?: $file->getClientOriginalExtension();
                if (!$extension) {
                    $extension = 'bin';
                }

                // Générer un nom fichier unique combiné
                $newFilename = sprintf('%s_%s.%s', $safeFileName, uniqid(), $extension);

                // Déplacement du fichier dans public/fichier_de_demandeur
                $file->move(
                    $this->getParameter('upload_directory'),
                    $newFilename
                );

                // Mise à jour des propriétés de l'entité
                $attachment->setUrl('/fichier_de_demandeur/' . $newFilename);
                $attachment->setNomFichier($userFileName);
                $attachment->setTaille($size);
            }

            $em->persist($attachment);
            $em->flush();

            $this->addFlash('success', 'Pièce jointe enregistrée avec succès ✅');

            return $this->redirectToRoute('app_piece_jointe');
        }

        return $this->render('pièce_jointe/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
