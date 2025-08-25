<?php

namespace App\Controller;

use App\Entity\Decision;
use App\Entity\Signature;
use App\Form\DecisionFormType;
use App\Form\SignatureFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DecisionController extends AbstractController
{
    #[Route('/decision', name: 'app_decision')]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        // On crée les instances des entités pour les formulaires
        $decision = new Decision();
        $signature = new Signature();

        // On crée les deux formulaires
        $decisionForm = $this->createForm(DecisionFormType::class, $decision);
        $signatureForm = $this->createForm(SignatureFormType::class, $signature);
        
        // On gère la soumission des requêtes pour les deux formulaires
        $decisionForm->handleRequest($request);
        $signatureForm->handleRequest($request);

        // --- Logique pour le formulaire de DÉCISION ---
        if ($decisionForm->isSubmitted() && $decisionForm->isValid()) {
            
            // Le formulaire a géré le lien avec l'entité Request grâce au champ de sélection.
            // Il n'est donc plus nécessaire de le faire manuellement.

            $entityManager->persist($decision);
            $entityManager->flush();

            $this->addFlash('success', 'La décision a été enregistrée avec succès. Vous pouvez maintenant ajouter une signature.');
            
            // On redirige pour éviter les doubles soumissions et pour rafraîchir la liste de décisions si elle est affichée.
            return $this->redirectToRoute('app_decision');
        }

        // --- Logique pour le formulaire de SIGNATURE ---
        // Le code ne va s'exécuter que si le formulaire de décision n'a pas été soumis.
        if ($signatureForm->isSubmitted() && $signatureForm->isValid()) {
            
            // Le formulaire a géré le lien avec l'entité Decision grâce au champ de sélection.
            // On s'assure que le champ de sélection de la "Décision liée" est bien rempli.
            
            $entityManager->persist($signature);
            $entityManager->flush();

            $this->addFlash('success', 'La signature a été enregistrée avec succès.');
            
            // On redirige après la soumission réussie
            return $this->redirectToRoute('app_decision');
        }

        return $this->render('decision/index.html.twig', [
            'decisionForm' => $decisionForm->createView(),
            'signatureForm' => $signatureForm->createView(),
        ]);
    }
}