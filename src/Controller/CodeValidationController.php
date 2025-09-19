<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\CodeValidation;
use App\Form\ValidationCodeType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Attribute\Route;

final class CodeValidationController extends AbstractController
{
    private $repositoryuser;

    public function __construct(UserRepository $repositoryuser) {
        $this->repositoryuser = $repositoryuser;
    }

    #[Route('/code/validation/{id}', name: 'app_code_validation', methods: ['GET', 'POST'])]
    public function index(User $user, Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ValidationCodeType::class, [
            'user_id' => $user->getId()
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            // Concaténer les 6 digits pour former le code
            $code = $data['digit1'].$data['digit2'].$data['digit3'].$data['digit4'].$data['digit5'].$data['digit6'];

            // Récupérer l'utilisateur via l'ID caché
            $userId = $data['user_id'];
            $user = $this->repositoryuser->find($userId);

            if (!$user) {
                throw $this->createNotFoundException('Utilisateur introuvable.');
            }

            // Chercher le code correspondant à l'utilisateur
            $validationCode = $entityManager->getRepository(CodeValidation::class)->findOneBy([
                'user' => $user,
                'code' => $code,
                'is_used' => false
            ]);

            if ($validationCode && $validationCode->getExpiresAt() >= new \DateTime()) {
                // Code valide
                $validationCode->setIsUsed(true);
                $entityManager->flush();
                $this->addFlash('success', 'Code correct !');
                return $this->redirectToRoute('app_couvre');
            } else {
                // Code invalide ou expiré
                $this->addFlash('error', 'Code invalide ou expiré.');
            }
        }

        return $this->render('code_validation/index.html.twig', [
            'formcodevalidation' => $form->createView(),
            'user' => $user,
        ]);
    }

    #[Route('/renvoyer-code', name: 'renvoyer_code_validation', methods: ['POST'])]
    public function renvoyerCode(Request $request, EntityManagerInterface $em, MailerInterface $mailer, UserRepository $repositoryuser)
    {
        // Récupérer l'ID utilisateur envoyé depuis le formulaire
        $userId = $request->request->get('user_id'); 
        $user = $repositoryuser->find($userId);

        if (!$user) {
            return $this->json([
                'status' => 'error',
                'message' => 'Utilisateur introuvable !'
            ], 404);
        }

        // Générer un nouveau code
        $code = random_int(100000, 999999);

        $validation = new CodeValidation();
        $validation->setUser($user);
        $validation->setCode($code);
        $validation->setCreatedAt(new \DateTime());
        $validation->setExpiresAt((new \DateTime())->modify('+15 minutes'));
        $validation->setIsUsed(false);

        $em->persist($validation);
        $em->flush();

        // Envoyer l'email
        $emailMessage = (new Email())
            ->from('notahiana.princy@gmail.com')
            ->to($user->getEmail())
            ->subject('Code de validation')
            ->text("Votre code de validation est : $code");

        $mailer->send($emailMessage);

        return $this->json([
            'status' => 'success',
            'message' => 'Un nouveau code de validation a été envoyé à votre email.'
        ]);
    }
}
