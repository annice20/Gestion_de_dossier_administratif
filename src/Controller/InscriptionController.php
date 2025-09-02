<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\UserRole;
use App\Entity\CitizenProfile;
use App\Form\RegistrationProfilType;
use App\Repository\UserRepository;
use App\Entity\CodeValidation;
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
    private $repositoryuser;

    public function __construct(UserRepository $repositoryuser) {
        $this->repositoryuser = $repositoryuser;
    }
    
    #[Route('/inscription', name: 'app_inscription')]
    public function register(
        Request $request,
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $passwordHasher,
        MailerInterface $mailer
    ): Response
    {
        $form = $this->createForm(RegistrationProfilType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Créer l'utilisateur
            $user = new User();
            $user->setEmail($form->get('email')->getData());
            // Récupère le mot de passe en clair du formulaire
            $plainPassword = $form->get('password')->getData();

            // Hache le mot de passe et le définit sur l'entité
            $user->setPassword(
                $passwordHasher->hashPassword(
                    $user,
                    $plainPassword
                )
            );
            
            $user->setTelephone($form->get('telephone')->getData());
            $user->setLangue($form->get('langue')->getData());
            $user->setStatut('actif');
            $user->setTwoFAEnabled(false);
            $user->setCreatedAt(new \DateTime());

            $entityManager->persist($user);
            $entityManager->flush();

            // Créer UserRole si sélectionné
            $roleSelected = $form->get('roles')->getData();
            if ($roleSelected) {
                $userRole = new UserRole();
                $userRole->setUser($user);
                $userRole->setRole($roleSelected);
                $userRole->setPortee('default');
                $entityManager->persist($userRole);
            }

            // Créer le profil
            $citizen = new CitizenProfile();
            $citizen->setUser($user);
            $citizen->setNom($form->get('nom')->getData());
            $citizen->setPrenoms($form->get('prenoms')->getData());
            $citizen->setDateDeNaissance($form->get('dateDeNaissance')->getData());
            $citizen->setNin($form->get('nin')->getData());
            $citizen->setAdresse($form->get('adresse')->getData());
            $citizen->setCommune($form->get('commune')->getData());

            $entityManager->persist($citizen);
            $entityManager->flush();

            // Envoyer email avec code validation
            $codeValidation = random_int(100000, 999999);
            $email = (new Email())
                ->from('notahiana.princy@gmail.com')
                ->to($user->getEmail())
                ->subject('Code de validation')
                ->text("Votre code de validation est : $codeValidation");
            $mailer->send($email);

            // Stocker le code dans la table code_validation
            $validationCode = new CodeValidation();
            $validationCode->setUser($user);
            $validationCode->setCode($codeValidation);
            $validationCode->setCreatedAt(new \DateTime());
            $validationCode->setExpiresAt((new \DateTime())->modify('+15 minutes'));
            $validationCode->setIsUsed(false);

            $entityManager->persist($validationCode);
            $entityManager->flush();

            $this->addFlash('success', 'Inscription et profil enregistrés ! Un code de validation a été envoyé par email.');

            return $this->redirectToRoute('app_inscription');
        }

        $lastUser = $this->repositoryuser->findOneBy([], ['id' => 'DESC']);
        
        return $this->render('inscription/index.html.twig', [
            'formInscription' => $form->createView(),
            'lastUser' => $lastUser,
        ]);
    }
}
