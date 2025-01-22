<?php
namespace App\Meteo\Config;

use PDO;
use PDOException;

class Conf {
    
    // Durée d'expiration de la session en secondes
    static public $dureeExpiration = 3600;

    static private array $databases = array(
        'hostname' => 'localhost', // Nom d'hôte
        'database' => 'meteoDB',   // Nom de la base de données
        'login' => 'root',         // Identifiant utilisateur
<<<<<<< HEAD
        'password' => ''           // Mot de passe
=======
        'password' => '123'           // Mot de passe
>>>>>>> b32c148080b40d0c414716f0ca3e42e4d0ab3af2
    );

    // Propriété statique pour stocker la connexion PDO
    private static $pdo;

    // Constructeur privé pour empêcher l'instanciation
    static public function getDatabase() : string {
        return static::$databases["database"];
    }

    // Méthodes statiques pour accéder au login
    static public function getLogin() : string {
        return static::$databases['login'];
    }

    // Méthodes statiques pour accéder au mot de passe
    static public function getPassword() : string {
        return static::$databases["password"];
    }

    // Méthodes statiques pour accéder au nom de l'hôte
    static public function getHostname() : string {
        return static::$databases["hostname"];
    }

    // Méthode statique pour obtenir une connexion PDO
    static public function getPDO() : PDO {
        $dsn = "mysql:host=" . static::getHostname() . ";dbname=" . static::getDatabase();
        return new PDO($dsn, static::getLogin(), static::getPassword());
    }
}
?>
