<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class CodeValidationController extends AbstractController
{
    private $repositoryuser;

    public function __construct(UserRepository $repositoryuser) {
        $this->repositoryuser = $repositoryuser;
    }

    #[Route('/code/validation/{id}', name: 'app_code_validation', methods: "GET")]
    public function index(User $user): Response
    {
        $userid = $this->repositoryuser->find($user->getId());
        return $this->render('code_validation/index.html.twig', [
            'userid' => $userid,
        ]);
    }
}
