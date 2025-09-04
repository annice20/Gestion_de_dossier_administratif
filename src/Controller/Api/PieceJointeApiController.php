<?php

namespace App\Controller\Api;

use App\Entity\Attachment;
use App\Repository\RequestRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/piece-jointe', name: 'api_piece_jointe_')]
final class PieceJointeApiController extends AbstractController
{
    #[Route('/upload/{request_id}', name: 'upload', methods: ['POST'])]
    public function upload(
        int $request_id,
        Request $request,
        EntityManagerInterface $em,
        RequestRepository $requestRepository
    ): JsonResponse {
        $requestEntity = $requestRepository->find($request_id);

        if (!$requestEntity) {
            return $this->json(['error' => 'Demande non trouvée.'], 404);
        }

        $file = $request->files->get('file'); // nom du champ "file" dans React Native

        if (!$file) {
            return $this->json(['error' => 'Aucun fichier envoyé.'], 400);
        }

        $attachment = new Attachment();
        $attachment->setRequest($requestEntity);

        $size = $file->getSize();
        $userFileName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFileName = preg_replace('/[^a-zA-Z0-9_-]/', '_', $userFileName);
        $extension = $file->guessExtension() ?: $file->getClientOriginalExtension() ?: 'bin';
        $newFilename = sprintf('%s_%s.%s', $safeFileName, uniqid(), $extension);

        try {
            $file->move(
                $this->getParameter('upload_directory'),
                $newFilename
            );
        } catch (\Exception $e) {
            return $this->json(['error' => 'Impossible de sauvegarder le fichier.'], 500);
        }

        $attachment->setUrl('/fichier_de_demandeur/' . $newFilename);
        $attachment->setNomFichier($userFileName);
        $attachment->setTaille($size);

        $em->persist($attachment);
        $em->flush();

        return $this->json([
            'message' => 'Pièce jointe enregistrée avec succès ✅',
            'attachment' => [
                'id' => $attachment->getId(),
                'nomFichier' => $attachment->getNomFichier(),
                'url' => $attachment->getUrl(),
                'taille' => $attachment->getTaille()
            ]
        ]);
    }
}
