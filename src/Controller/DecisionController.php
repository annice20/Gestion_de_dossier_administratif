<?php

namespace App\Controller;

use App\Entity\Decision;
use App\Entity\Signature;
use App\Form\CombinedDecisionType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DecisionController extends AbstractController
{
    #[Route("/decision/new", name: "app_decision_new")]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $decision = new Decision();
        $signature = new Signature();
        $decision->setSignature($signature);

        $form = $this->createForm(CombinedDecisionType::class, $decision);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($decision);
            $entityManager->flush();

            // Redirect to a success page or another route
            return $this->redirectToRoute('app_some_success_route'); 
        }

        return $this->render('decision/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
