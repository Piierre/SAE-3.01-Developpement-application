<?php
namespace App\Meteo\Model\HTTP;

use App\Meteo\Config\Conf;
use Exception;

class Session {

    private static ?Session $instance = null;
    private function __construct()
    { 
        // Démarrer la session et lancer une exception si cela échoue
        if (session_status() === PHP_SESSION_NONE) {
            if (session_start() === false) {
                throw new Exception("La session n'a pas réussi à démarrer.");
            }
        }
    }

    // Méthode pour obtenir l'instance de la classe Session
    public static function getInstance(): Session
    {
        // Vérifier si l'instance est nulle et la créer si nécessaire
        if (is_null(static::$instance)) {
            static::$instance = new Session();
            static::$instance->verifierDerniereActivite();
        }
        return static::$instance;
    }
    
    // Méthode pour vérifier si une variable de session existe
    public function contient($name): bool
    {
        // Vérifier si la variable de session est définie
        return isset($_SESSION[$name]);
    }

    // Méthode pour enregistrer une variable de session
    public function enregistrer(string $name, mixed $value): void
    {
        // Enregistrer la valeur dans la variable de session
        $_SESSION[$name] = $value;
    }

    // Méthode pour lire une variable de session
    public function lire(string $name): mixed
    {
        // Vérifier si la variable de session existe et retourner sa valeur, sinon retourner une chaîne vide
        if(static::contient($name)) {
            return $_SESSION[$name];
        } else {
            return "";
        }
    }

    // Méthode pour supprimer une variable de session
    public function supprimer($name): void
    {
        // Supprimer la variable de session
        unset($_SESSION[$name]);
    }

    // Méthode pour détruire la session
    public function detruire() : void
    {
        // Effacer les variables de session pour l'exécution actuelle
        session_unset(); 
        // Détruire les données de session en stockage
        session_destroy(); 
        // Supprimer le cookie de session
        Cookie::supprimer(session_name()); 
        // Réinitialiser l'instance pour la prochaine appel de getInstance()
        static::$instance = null;
    }

    // Méthode privée pour vérifier la dernière activité de la session
    private function verifierDerniereActivite() : void {
        // Récupérer la dernière activité enregistrée
        $derniere_activite = static::lire("derniereActivite");
        // Vérifier si la session a expiré selon la durée d'expiration configurée
        if($derniere_activite && ((time() - $derniere_activite) > Conf::$dureeExpiration)) {
            // Détruire la session si elle a expiré
            static::detruire();
        }
        // Mettre à jour la dernière activité
        static::enregistrer("derniereActivite", time());
    }
}
?>
