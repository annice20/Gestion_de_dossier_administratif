<?php

namespace App\Controller;

use App\Form\ResetPasswordRequestFormType;
use App\Form\ResetPasswordFormType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    #[Route('/mot-de-passe-oublie', name: 'forgotten_password')]
    public function forgottenPassword(
        Request $request,
        UserRepository $userRepository,
        EntityManagerInterface $entityManager,
        TokenGeneratorInterface $tokenGenerator,
        MailerInterface $mailer,
        UrlGeneratorInterface $urlGenerator
    ) : Response
    {
        $form = $this->createForm(ResetPasswordRequestFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $userRepository->findOneByEmail($form->get('email')->getData());

            if ($user) {
                // 1. Générer un token
                $resetToken = $tokenGenerator->generateToken();

                // 2. Sauvegarder ce token en BDD (dans ton User ou une autre table ResetPassword)
                $user->setResetToken($resetToken);
                $user->setResetTokenExpiresAt(new \DateTimeImmutable('+1 hour')); // expiration
                $entityManager->flush();

                // 3. Générer une URL avec ce token
                $resetUrl = $urlGenerator->generate('reset_password', [
                    'token' => $resetToken,
                ], UrlGeneratorInterface::ABSOLUTE_URL);

                // 4. Envoyer l’email
                $email = (new TemplatedEmail())
                    ->from('notahiana.princy@gmail.com')
                    ->to($user->getEmail())
                    ->subject('Réinitialisation de votre mot de passe')
                    ->htmlTemplate('emails/reset_password_email.html.twig')
                    ->textTemplate('emails/reset_password_email.txt.twig')
                    ->context([
                        'resetUrl' => $resetUrl,
                        'user' => $user,
                    ]);

                $mailer->send($email);

                $this->addFlash('success', 'Un email de réinitialisation vous a été envoyé.');
                return $this->redirectToRoute('forgotten_password');
            }

            $this->addFlash('danger', 'Aucun utilisateur trouvé avec cet email.');
            return $this->redirectToRoute('forgotten_password');
        }

        return $this->render('security/reset_password_request.html.twig', [
            'requestPassForm' => $form->createView()
        ]);
    }

    #[Route('/reinitialiser-mot-de-passe/{token}', name: 'reset_password')]
    public function resetPassword(
        string $token,
        Request $request,
        UserRepository $userRepository,
        UserPasswordHasherInterface $passwordHasher,
        EntityManagerInterface $entityManager
    ): Response {
        $user = $userRepository->findOneBy(['resetToken' => $token]);

        if (!$user || $user->getResetTokenExpiresAt() < new \DateTimeImmutable()) {
            $this->addFlash('danger', 'Token invalide ou expiré.');
            return $this->redirectToRoute('forgotten_password');
        }

        $form = $this->createForm(ResetPasswordFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword(
                $passwordHasher->hashPassword($user, $form->get('plainPassword')->getData())
            );
            $user->setResetToken(null);
            $user->setResetTokenExpiresAt(null);

            $entityManager->flush();

            $this->addFlash('success', 'Votre mot de passe a bien été réinitialisé.');
            return $this->redirectToRoute('app_login');
        }

        return $this->render('security/reset_password.html.twig', [
            'resetForm' => $form->createView(),
        ]);
    }
}
