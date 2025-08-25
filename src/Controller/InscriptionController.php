<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\UserRole;
use App\Entity\CitizenProfile;
use App\Form\RegistrationProfilType;
use App\Repository\UserRepository;
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
            $data = $form->getData();

            // Créer l'utilisateur
            $user = new User();
            $user->setEmail($data['email']);
            $hashedPassword = $passwordHasher->hashPassword($user, $data['hashMdp']);
            $user->setHashMdp($hashedPassword);
            $user->setTelephone($data['telephone']);
            $user->setLangue($data['langue']);
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
            $citizen->setNom($data['nom']);
            $citizen->setPrenoms($data['prenoms']);
            $citizen->setDateDeNaissance($data['dateDeNaissance']);
            $citizen->setNin($data['nin']);
            $citizen->setAdresse($data['adresse']);
            $citizen->setCommune($data['commune']);

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
