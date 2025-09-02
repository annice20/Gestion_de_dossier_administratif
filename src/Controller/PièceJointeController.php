<?php

namespace App\Controller;

use App\Entity\Attachment;
use App\Form\AttachmentType;
use App\Repository\RequestRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class PièceJointeController extends AbstractController
{
    #[Route('/piece/jointe/{request_id}', name: 'app_piece_jointe')]
    public function index(int $request_id, Request $request, EntityManagerInterface $em, RequestRepository $requestRepository): Response
    {
        $requestEntity = $requestRepository->find($request_id);

        if (!$requestEntity) {
            throw $this->createNotFoundException('Demande non trouvée.');
        }

        $attachment = new Attachment();
        $attachment->setRequest($requestEntity);

        $form = $this->createForm(AttachmentType::class, $attachment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $file = $form->get('file')->getData();

            if ($file) {
                $size = $file->getSize();
                $userFileName = $attachment->getNomFichier();

                if (empty($userFileName)) {
                    $userFileName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                }

                $safeFileName = preg_replace('/[^a-zA-Z0-9_-]/', '_', $userFileName);
                $extension = $file->guessExtension() ?: $file->getClientOriginalExtension() ?: 'bin';

                $newFilename = sprintf('%s_%s.%s', $safeFileName, uniqid(), $extension);

                $file->move(
                    $this->getParameter('upload_directory'),
                    $newFilename
                );

                $attachment->setUrl('/fichier_de_demandeur/' . $newFilename);
                $attachment->setNomFichier($userFileName);
                $attachment->setTaille($size);
            }

            $em->persist($attachment);
            $em->flush();

            $this->addFlash('success', 'Pièce jointe enregistrée avec succès ✅');

            return $this->redirectToRoute('app_piece_jointe', ['request_id' => $request_id]);
        }

        return $this->render('pièce_jointe/index.html.twig', [
            'form' => $form->createView(),
            'requestEntity' => $requestEntity,
        ]);
    }
}
