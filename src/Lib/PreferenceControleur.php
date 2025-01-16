<?php
namespace App\Meteo\Lib;

use App\Meteo\Model\HTTP\Cookie;

class PreferenceControleur {

    // Clé utilisée pour identifier la préférence dans les cookies
    private static string $clePreference = "preferenceControleur";

    // Méthode pour enregistrer une préférence
    public static function enregistrer(string $preference): void {
        // Utilise la classe Cookie pour enregistrer la préférence avec la clé définie
        Cookie::enregistrer(static::$clePreference, $preference);
    }

    // Méthode pour lire la préférence enregistrée
    public static function lire(): string {
        // Vérifie si la préférence existe dans les cookies
        if (static::existe()) {
            // Si elle existe, retourne sa valeur en utilisant la classe Cookie
            return Cookie::lire(static::$clePreference);
        } else {
            // Si elle n'existe pas, retourne une chaîne vide
            return "";
        }
    }

    // Méthode pour vérifier si une préférence existe
    public static function existe(): bool {
        // Utilise la classe Cookie pour vérifier si la clé est présente
        return Cookie::contient(static::$clePreference);
    }

    // Méthode pour supprimer la préférence
    public static function supprimer(): void {
        // Utilise la classe Cookie pour supprimer la préférence associée à la clé
        Cookie::supprimer(static::$clePreference);
    }
}
