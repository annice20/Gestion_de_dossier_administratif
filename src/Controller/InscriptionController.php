<?php
namespace App\Controller;

use App\Entity\User;
use App\Form\InscriptionType;
use App\Form\ProfilType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class InscriptionController extends AbstractController
{
    #[Route('/', name: 'app_inscription')]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        // On crée une nouvelle instance de l'entité User
        $user = new User();

        // On crée les deux formulaires en les liant à l'entité User
        $formInscription = $this->createForm(InscriptionType::class, $user);
        $formProfil = $this->createForm(ProfilType::class, $user);

        // On gère la soumission du formulaire d'inscription
        $formInscription->handleRequest($request);
        if ($formInscription->isSubmitted() && $formInscription->isValid()) {
            // Persiste l'utilisateur dans la base de données
            $entityManager->persist($user);
            $entityManager->flush();

            // Ajoute un message flash pour la confirmation
            $this->addFlash('success', 'Votre inscription a été enregistrée. Veuillez compléter votre profil.');

            // Redirige vers la même page pour afficher le formulaire de profil
            return $this->redirectToRoute('app_inscription');
        }

        // On gère la soumission du formulaire de profil
        $formProfil->handleRequest($request);
        if ($formProfil->isSubmitted() && $formProfil->isValid()) {
            // L'entité est déjà en mémoire, on a juste à sauvegarder les modifications
            $entityManager->flush();

            // Ajoute un message flash pour la confirmation
            $this->addFlash('success', 'Votre profil a été mis à jour avec succès !');

            return $this->redirectToRoute('app_inscription');
        }

        // On rend la vue Twig en passant les formulaires
        return $this->render('inscription/index.html.twig', [
            'formInscription' => $formInscription->createView(),
            'formProfil' => $formProfil->createView(),
        ]);
    }
}