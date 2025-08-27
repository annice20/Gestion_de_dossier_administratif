<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Http\Event\LoginSuccessEvent;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class LoginSuccessSubscriber implements EventSubscriberInterface
{
    private $urlGenerator;

    public function __construct(UrlGeneratorInterface $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            LoginSuccessEvent::class => 'onLoginSuccess',
        ];
    }

    public function onLoginSuccess(LoginSuccessEvent $event): void
    {
        $user = $event->getPassport()->getUser();
        $targetUrl = '';

        $roles = $user->getRoles();

        // Si l'utilisateur a le rôle d'administrateur
        if (in_array('ROLE_ADMIN', $roles)) {
            // Redirige vers la page de décision de l'administration
            $targetUrl = $this->urlGenerator->generate('app_decision');
        } elseif (in_array('ROLE_USER', $roles)) {
            // Redirige vers la page d'accueil du citoyen
            $targetUrl = $this->urlGenerator->generate('app_accueil');
        } else {
            // Redirection par défaut si aucun rôle n'est trouvé
            $targetUrl = $this->urlGenerator->generate('app_login');
        }

        $response = new RedirectResponse($targetUrl);
        $event->setResponse($response);
    }
}