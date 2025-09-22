<?php

namespace App\Service;

use App\Entity\Notification;
use App\Entity\Decision;
use App\Entity\User;
use App\Entity\Request as UserRequest;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Bundle\SecurityBundle\Security;

class NotificationService
{
    public function __construct(
        private EntityManagerInterface $em,
        private MailerInterface $mailer,
        private Security $security, // <-- injection du service Security
    ) {}

    /**
     * Crée une notification et une décision en même temps
     */
    public function createNotificationAndDecision(User $user, UserRequest $userRequest, string $titre, string $message, string $statut): void
    {
        $now = new \DateTimeImmutable();

        // --- Notification (toujours pour le demandeur) ---
        $notification = new Notification();
        $notification->setUser($user);
        $notification->setTitre($titre);
        $notification->setMessage($message);
        $notification->setIsRead(false);
        $notification->setHorodatage($now);

        $this->em->persist($notification);

        // --- Décision ---
        $decision = new Decision();
        $decision->setRequest($userRequest);
        $decision->setResultat($statut === 'accepte' ? 'Validé' : 'Refusé');
        $decision->setMotif("Demande de type {$userRequest->getType()?->getLibelle()} (réf: {$userRequest->getRef()})");

        // Récupérer l'admin connecté
        $admin = $this->security->getUser();
        if ($admin instanceof User) {
            $nomComplet = trim(($admin->getCitizenProfile()?->getNom() ?? '') . ' ' . ($admin->getCitizenProfile()?->getPrenoms() ?? ''));
            $decision->setValidePar($nomComplet !== '' ? $nomComplet : $admin->getEmail());
        } else {
            // fallback si jamais pas d'admin connecté
            $decision->setValidePar('Système');
        }

        $decision->setValideLe($now);

        $this->em->persist($decision);

        // Sauvegarde tout
        $this->em->flush();
    }

    /**
     * Envoi de notification par email
     */
    public function sendEmailNotification(string $email, string $message, string $requestRef): void
    {
        $emailMessage = (new Email())
            ->from('notahiana.princy@gmail.com')
            ->to($email)
            ->subject('Mise à jour de votre demande')
            ->html("<p>{$message}</p><p>Réf : {$requestRef}</p>");

        $this->mailer->send($emailMessage);
    }

    /**
     * Récupère toutes les notifications non lues d'un utilisateur
     */
    public function getUnreadNotifications(User $user): array
    {
        $notifications = $this->em->getRepository(Notification::class)
            ->findBy(['user' => $user, 'isRead' => false], ['horodatage' => 'DESC']);

        return array_map(fn($n) => [
            'titre' => $n->getTitre(),
            'message' => $n->getMessage(),
            'horodatage' => $n->getHorodatage()->format('d/m/Y H:i'),
        ], $notifications);
    }

    /**
     * Marque toutes les notifications non lues comme lues
     */
    public function markNotificationsAsRead(User $user): void
    {
        $notifications = $this->em->getRepository(Notification::class)
            ->findBy(['user' => $user, 'isRead' => false]);

        foreach ($notifications as $notification) {
            $notification->setIsRead(true);
        }
        $this->em->flush();
    }
}
