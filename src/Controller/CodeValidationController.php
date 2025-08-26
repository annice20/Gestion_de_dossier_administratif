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
}
