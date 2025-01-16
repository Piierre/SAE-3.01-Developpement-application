<?php

namespace App\Covoiturage\Lib;

use App\Covoiturage\Model\HTTP\Session;

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
        // on réécrit les messages flahs qui sont sont supprimé lorsqu'on les a récupérés
        $session->enregistrer(static::$cleFlash, $messagesFlash);
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
        $messages = $messagesFlash[$type];
        // on les supprime
        $_SESSION[static::$cleFlash][$type] = [];
        return $messages;   
    }
    
    public static function lireTousMessages() : array
    {
        // On récupère les messages qui nous intéressent
        $messagesFlash = Session::getInstance()->lire(static::$cleFlash);
        // on les supprime
        static::initialiser();
        return $messagesFlash;
    }
}