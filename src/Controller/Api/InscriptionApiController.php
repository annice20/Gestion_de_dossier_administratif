<?php

namespace App\Controller\Api;

use App\Entity\User;
use App\Entity\UserRole;
use App\Entity\CitizenProfile;
use App\Entity\CodeValidation;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class InscriptionApiController extends AbstractController
{
    private $repositoryuser;

    public function __construct(UserRepository $repositoryuser) {
        $this->repositoryuser = $repositoryuser;
    }

    /**
     * @Route("/api/register", name="api_register", methods={"POST"})
     */
    public function register(
        Request $request,
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $passwordHasher,
        MailerInterface $mailer
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);

        if (!$data) {
            return $this->json(['error' => 'Données JSON invalides'], 400);
        }

        // Champs obligatoires
        $requiredFields = [
            'email', 'password', 'telephone', 'langue',
            'nom', 'prenoms', 'dateDeNaissance', 'nin',
            'adresse', 'commune'
        ];
        foreach ($requiredFields as $field) {
            if (empty($data[$field])) {
                return $this->json(['error' => "Champ manquant : $field"], 400);
            }
        }

        // Vérifier si l'email existe déjà
        if ($this->repositoryuser->findOneBy(['email' => $data['email']])) {
            return $this->json(['error' => 'Cet email est déjà utilisé'], 409);
        }

        // Créer l'utilisateur
        $user = new User();
        $user->setEmail($data['email']);
        $user->setPassword(
            $passwordHasher->hashPassword(
                $user,
                $data['password']
            )
        );
        $user->setTelephone($data['telephone']);
        $user->setLangue($data['langue']);
        $user->setStatut('inactif'); // reste inactif tant que non validé
        $user->setTwoFAEnabled(false);
        $user->setCreatedAt(new \DateTime());

        $entityManager->persist($user);
        $entityManager->flush();

        // Créer UserRole si présent
        if (!empty($data['role'])) {
            $userRole = new UserRole();
            $userRole->setUser($user);
            $userRole->setRole($data['role']);
            $userRole->setPortee('default');
            $entityManager->persist($userRole);
        }

        // Créer le profil
        $citizen = new CitizenProfile();
        $citizen->setUser($user);
        $citizen->setNom($data['nom']);
        $citizen->setPrenoms($data['prenoms']);
        $citizen->setDateDeNaissance(new \DateTime($data['dateDeNaissance']));
        $citizen->setNin($data['nin']);
        $citizen->setAdresse($data['adresse']);
        $citizen->setCommune($data['commune']);

        $entityManager->persist($citizen);
        $entityManager->flush();

        // Générer et envoyer email avec code validation
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

        return $this->json([
            'message' => 'Inscription réussie. Un code de validation a été envoyé.',
            'userId' => $user->getId(),
            'email' => $user->getEmail()
        ], 201);
    }

    /**
     * @Route("/api/verify-code", name="api_verify_code", methods={"POST"})
     */
    public function verifyCode(
        Request $request,
        EntityManagerInterface $entityManager
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);

        if (!$data || empty($data['email']) || empty($data['code'])) {
            return $this->json(['error' => 'Email et code sont requis'], 400);
        }

        $user = $this->repositoryuser->findOneBy(['email' => $data['email']]);
        if (!$user) {
            return $this->json(['error' => 'Utilisateur introuvable'], 404);
        }

        $codeValidation = $entityManager->getRepository(CodeValidation::class)->findOneBy([
            'user' => $user,
            'code' => $data['code'],
            'isUsed' => false
        ], ['createdAt' => 'DESC']);

        if (!$codeValidation) {
            return $this->json(['error' => 'Code invalide ou déjà utilisé'], 400);
        }

        if ($codeValidation->getExpiresAt() < new \DateTime()) {
            return $this->json(['error' => 'Code expiré'], 400);
        }

        // Valider le compte
        $codeValidation->setIsUsed(true);
        $user->setStatut('actif');

        $entityManager->flush();

        return $this->json([
            'message' => 'Compte validé avec succès',
            'userId' => $user->getId(),
            'email' => $user->getEmail()
        ], 200);
    }
}
