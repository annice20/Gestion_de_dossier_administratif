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
        $decision = new Decision();
        $signature = new Signature();

        $decisionForm = $this->createForm(DecisionFormType::class, $decision);
        $signatureForm = $this->createForm(SignatureFormType::class, $signature);

        $decisionForm->handleRequest($request);
        if ($decisionForm->isSubmitted() && $decisionForm->isValid()) {
            $entityManager->persist($decision);
            $entityManager->flush();

            $this->addFlash('success', 'La décision a été enregistrée avec succès.');
            return $this->redirectToRoute('app_decision');
        }

        $signatureForm->handleRequest($request);
        if ($signatureForm->isSubmitted() && $signatureForm->isValid()) {
            $entityManager->persist($signature);
            $entityManager->flush();

            $this->addFlash('success', 'La signature a été enregistrée avec succès.');
            return $this->redirectToRoute('app_decision');
        }

        return $this->render('decision/index.html.twig', [
            'decisionForm' => $decisionForm->createView(),
            'signatureForm' => $signatureForm->createView(),
        ]);
    }
    
}


    