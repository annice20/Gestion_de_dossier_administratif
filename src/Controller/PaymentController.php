<?php

namespace App\Controller;

use App\Entity\Payment;
use App\Form\PaymentType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

final class PaymentController extends AbstractController
{
    #[Route('/payment', name: 'app_payment')]
    public function index(Request $request): Response
    {
        $payment = new Payment();
        $form = $this->createForm(PaymentType::class, $payment);
        $form->handleRequest($request);

        return $this->render('payment/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
