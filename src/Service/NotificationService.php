<?php

namespace App\Service;

use App\Entity\Notification;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class NotificationService
{
    public function __construct(
        private EntityManagerInterface $em,
        private MailerInterface $mailer,
    ) {}

    public function createAndSaveNotification(User $user, string $titre, string $message): Notification
    {
        $notification = new Notification();
        $notification->setUser($user);
        $notification->setTitre($titre);
        $notification->setMessage($message);
        $notification->setIsRead(false);
        $notification->setHorodatage(new \DateTimeImmutable());

        $this->em->persist($notification);
        $this->em->flush();

        return $notification;
    }

    public function sendEmailNotification(string $email, string $message, string $requestRef): void
    {
        $emailMessage = (new Email())
            ->from('notahiana.princy@gmail.com')
            ->to($email)
            ->subject('Mise à jour de votre demande')
            ->html("<p>{$message}</p><p>Réf : {$requestRef}</p>");

        $this->mailer->send($emailMessage);
    }

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
