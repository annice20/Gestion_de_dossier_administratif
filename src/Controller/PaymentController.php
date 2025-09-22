<?php

namespace App\Controller;

use App\Entity\Payment;
use App\Form\PaymentType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Annotation\Route;

// Dompdf
use Dompdf\Dompdf;
use Dompdf\Options;

class PaymentController extends AbstractController
{
    #[Route('/payment', name: 'app_payment')]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        $payment = new Payment();
        $form = $this->createForm(PaymentType::class, $payment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // 1️⃣ Enregistrer le paiement
            $entityManager->persist($payment);
            $entityManager->flush();

            // 2️⃣ Récupérer le profil du demandeur
            $requestEntity = $payment->getRequest();
            $demandeurProfile = $requestEntity ? $requestEntity->getDemandeur() : null;

            // 3️⃣ Génération PDF
            $options = new Options();
            $options->set('defaultFont', 'Arial');
            $dompdf = new Dompdf($options);

            $html = $this->renderView('payment/recu.html.twig', [
                'payment' => $payment,
                'demandeurProfile' => $demandeurProfile,
            ]);

            $dompdf->loadHtml($html);
            $dompdf->render();

            // 4️⃣ Déterminer le nom du fichier PDF
            $rawName = $request->query->get('filename', $payment->getRecuUrl() ?? 'recu_'.$payment->getId());
            $rawName = pathinfo($rawName, PATHINFO_FILENAME);
            $fileName = preg_replace('/[^a-zA-Z0-9_\-]/', '_', $rawName) . '.pdf';


            // 5️⃣ Renvoyer le PDF en téléchargement automatique
            $pdfOutput = $dompdf->output();
            $response = new Response($pdfOutput);

            $disposition = $response->headers->makeDisposition(
                ResponseHeaderBag::DISPOSITION_ATTACHMENT,
                $fileName
            );

            $response->headers->set('Content-Type', 'application/pdf');
            $response->headers->set('Content-Disposition', $disposition);

            return $response;
        }

        // Affichage du formulaire si non soumis ou invalide
        return $this->render('payment/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
