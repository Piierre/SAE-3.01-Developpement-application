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
        'password' => 'butinfo'           // Mot de passe
    );

    private static $pdo;

    static public function getDatabase() : string {
        return static::$databases["database"];
    }

    static public function getLogin() : string {
        return static::$databases['login'];
    }

    static public function getPassword() : string {
        return static::$databases["password"];
    }

    static public function getHostname() : string {
        return static::$databases["hostname"];
    }

    static public function getPDO() : \PDO {
        $dsn = "mysql:host=" . static::getHostname() . ";dbname=" . static::getDatabase();
        return new \PDO($dsn, static::getLogin(), static::getPassword());
    }
}
?>
