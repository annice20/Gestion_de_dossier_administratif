<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\UserRole;
use App\Form\InscriptionType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class InscriptionController extends AbstractController
{
    #[Route('/inscription', name: 'app_inscription')]
    public function register(
        Request $request,
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $passwordHasher,
        MailerInterface $mailer
    ): Response
    {
        $user = new User();
        $form = $this->createForm(InscriptionType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Hasher le mot de passe
            $hashedPassword = $passwordHasher->hashPassword($user, $user->getHashMdp());
            $user->setHashMdp($hashedPassword);

            // Initialiser d'autres champs
            $user->setStatut('actif');
            $user->setTwoFaenabled(false);
            $user->setCreatedAt(new \DateTime());

            $entityManager->persist($user);
            $entityManager->flush(); // Persister User pour avoir un ID

            // Créer la relation UserRole
            $roleSelected = $form->get('roles')->getData();
            if ($roleSelected) {
                $userRole = new UserRole();
                $userRole->setUser($user);
                $userRole->setRole($roleSelected);
                $userRole->setPortee('default');
                $entityManager->persist($userRole);
            }

            // Générer un code de validation à 6 chiffres
            $codeValidation = random_int(100000, 999999);

            // Envoyer l'e-mail
            $email = (new Email())
                ->from('notahiana.princy@gmail.com')
                ->to($user->getEmail())
                ->subject('Code de validation')
                ->text("Votre code de validation est : $codeValidation");

            $mailer->send($email);

            $entityManager->flush();

            $this->addFlash('success', 'Inscription réussie ! Un code de validation a été envoyé par e-mail.');

            return $this->redirectToRoute('app_inscription');
        }

        return $this->render('inscription/index.html.twig', [
            'formInscription' => $form->createView(),
        ]);
    }
}
