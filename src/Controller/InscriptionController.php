<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\UserRole;
use App\Entity\CitizenProfile;
use App\Form\InscriptionType;
use App\Form\ProfilType;
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
        // === Formulaire User ===
        $user = new User();
        $formInscription = $this->createForm(InscriptionType::class, $user);
        $formInscription->handleRequest($request);

        // === Formulaire CitizenProfile ===
        $citizen = new CitizenProfile();
        $formProfil = $this->createForm(ProfilType::class, $citizen);
        $formProfil->handleRequest($request);

        // === Gestion du formulaire Inscription ===
        if ($formInscription->isSubmitted() && $formInscription->isValid()) {

            // Hasher le mot de passe
            $hashedPassword = $passwordHasher->hashPassword($user, $user->getHashMdp());
            $user->setHashMdp($hashedPassword);

            // Initialiser d'autres champs
            $user->setStatut('actif');
            $user->setTwoFAEnabled(false);
            $user->setCreatedAt(new \DateTime());

            $entityManager->persist($user);
            $entityManager->flush(); // Persister User pour avoir un ID

            // Créer la relation UserRole
            $roleSelected = $formInscription->get('roles')->getData();
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

            // Lier le profil au nouvel utilisateur si le formulaire profil est rempli
            if ($formProfil->isSubmitted() && $formProfil->isValid()) {
                $citizen->setUserId($user);
                $entityManager->persist($citizen);
                $entityManager->flush();
                $this->addFlash('success', 'Votre profil a été enregistré avec succès !');
            }

            return $this->redirectToRoute('app_inscription');
        }

        // === Gestion du formulaire Profil si uniquement profil soumis ===
        if ($formProfil->isSubmitted() && $formProfil->isValid()) {
            // Ici on pourrait associer à un utilisateur existant si nécessaire
            $entityManager->persist($citizen);
            $entityManager->flush();
            $this->addFlash('success', 'Votre profil a été mis à jour avec succès !');

            return $this->redirectToRoute('app_inscription');
        }

        // Rendu Twig avec les deux formulaires
        return $this->render('inscription/index.html.twig', [
            'formInscription' => $formInscription->createView(),
            'formProfil' => $formProfil->createView(),
        ]);
    }
}
