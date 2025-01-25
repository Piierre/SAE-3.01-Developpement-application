<?php

namespace App\Meteo\Lib;

require_once __DIR__ . '/../Model/HTTP/Session.php'; // Assurez-vous que le chemin est correct
use App\Meteo\Model\HTTP\Session;

class MessageFlash {
    
    // Les messages sont enregistrés en session associée à la clé suivante
    private static string $cleFlash = "_messagesFlash";

    public static function initialiser() : void {
        Session::getInstance()->enregistrer(static::$cleFlash, [
            "success" => [],
            "info" => [],
            "warning" => [],
            "danger" => []
        ]);
    }
    
    // $type parmi "success", "info", "warning" ou "danger"
    public static function ajouter(string $type, string $message): void
    {
        // On récupère la session
        $session = Session::getInstance(); 
        // On récupère les messages flash existants
        $messagesFlash = static::lireTousMessages();
        // on ajoute notre message dans le type souhaité
        $messagesFlash[$type][] = $message;
        // On enregistre les messages flash mis à jour
        $session->enregistrer(static::$cleFlash, $messagesFlash);
    }

    public static function lireTousMessages(): array
    {
        // On récupère la session
        $session = Session::getInstance();
        // On lit les messages flash existants
        return $session->lire(static::$cleFlash) ?? [
            "success" => [],
            "info" => [],
            "warning" => [],
            "danger" => []
        ];
    }

    public static function displayFlashMessages(): void
    {
        $messagesFlash = static::lireTousMessages();
        foreach ($messagesFlash as $type => $messages) {
            foreach ($messages as $message) {
                echo '<div class="flash-message ' . htmlspecialchars($type) . '">';
                echo htmlspecialchars($message);
                echo '</div>';
            }
        }
        // Clear flash messages after displaying them
        Session::getInstance()->enregistrer(static::$cleFlash, [
            "success" => [],
            "info" => [],
            "warning" => [],
            "danger" => []
        ]);
    }

    public static function contientMessage(string $type): bool
    {
        $messagesFlash = Session::getInstance()->lire(static::$cleFlash);
        return !empty($messagesFlash[$type]); 
    }

    // Attention : la lecture doit détruire le message
    public static function lireMessages(string $type): array
    {
        // On récupère les messages qui nous intéressent
        $messagesFlash = Session::getInstance()->lire(static::$cleFlash);
        $messages = $messagesFlash[$type] ?? [];
        // on les supprime
        $_SESSION[static::$cleFlash][$type] = [];
        return $messages;   
    }
}
?>