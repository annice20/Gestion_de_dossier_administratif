<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class AppExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            // Déclare le filtre Twig pour le formatage des numéros de téléphone
            new TwigFilter('format_phone_number', [$this, 'formatPhoneNumber']),
        ];
    }

    public function formatPhoneNumber(string $phoneNumber): string
    {
        // 1. Supprime tous les caractères non numériques
        $digits = preg_replace('/[^0-9]/', '', $phoneNumber);

        // 2. Vérifie et formate le numéro en fonction de sa longueur
        $length = strlen($digits);

        if ($length === 10 && substr($digits, 0, 1) === '0') {
            // Format Malgache local (0344887315) -> 34 48 873 15
            return substr($digits, 1, 2) . ' ' . substr($digits, 3, 2) . ' ' . substr($digits, 5, 3) . ' ' . substr($digits, 8, 2);
        } elseif ($length === 11 && substr($digits, 0, 3) === '261') {
            // Format Malgache international sans +261 (261344887315) -> 34 48 873 15
            return substr($digits, 3, 2) . ' ' . substr($digits, 5, 2) . ' ' . substr($digits, 7, 3) . ' ' . substr($digits, 10, 2);
        }

        // 3. Si le format n'est pas reconnu, retourne le numéro tel quel
        return $phoneNumber;
    }
}
