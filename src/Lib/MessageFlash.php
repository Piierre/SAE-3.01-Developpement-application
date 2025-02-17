<?php

namespace App\Meteo\Lib;

require_once __DIR__ . '/../Model/HTTP/Session.php'; // Assurez-vous que le chemin est correct
use App\Meteo\Model\HTTP\Session;

class MessageFlash {
    
    // Les messages sont enregistrés en session associée à la clé suivante
    private static string $cleFlash = "_messagesFlash";

    // Initialise les messages flash en session
    public static function initialiser() : void {
        Session::getInstance()->enregistrer(static::$cleFlash, [
            "success" => [],
            "info" => [],
            "warning" => [],
            "danger" => []
        ]);
    }
    
    // Ajoute un message flash de type $type parmi "success", "info", "warning" ou "danger"
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

    // Lit tous les messages flash
    public static function lireTousMessages(): array
    {
        // On récupère la session
        $session = Session::getInstance();
        // On lit les messages flash existants
        $messagesFlash = $session->lire(static::$cleFlash);
        // Vérifier que les messages flash sont bien un tableau
        if (!is_array($messagesFlash)) {
            $messagesFlash = [
                "success" => [],
                "info" => [],
                "warning" => [],
                "danger" => []
            ];
        }
        return $messagesFlash;
    }

    // Affiche les messages flash
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
        // Efface les messages flash après les avoir affichés
        Session::getInstance()->enregistrer(static::$cleFlash, [
            "success" => [],
            "info" => [],
            "warning" => [],
            "danger" => []
        ]);
    }

    // Vérifie si un message de type $type existe
    public static function contientMessage(string $type): bool
    {
        $messagesFlash = Session::getInstance()->lire(static::$cleFlash);
        return !empty($messagesFlash[$type]); 
    }

    // Lit et détruit les messages de type $type
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