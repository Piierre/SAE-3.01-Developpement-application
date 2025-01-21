<?php
namespace App\Meteo\Model;

use App\Meteo\Config\Conf;
use PDO;
use PDOException;

class UserModel {
    public static function getUserByLogin($login) {
        // Récupère la connexion PDO via la classe Conf
        $pdo = Conf::getPDO();

        $stmt = $pdo->prepare("SELECT * FROM Utilisateur WHERE login = ?");
        $stmt->execute([$login]);
        return $stmt->fetch(PDO::FETCH_ASSOC); // Utiliser FETCH_ASSOC pour éviter d'obtenir des doublons
    }

    public static function createUser($login, $hashedPassword) {
        // Récupère la connexion PDO via la classe Conf
        $pdo = Conf::getPDO();

        try {
            $stmt = $pdo->prepare("INSERT INTO Utilisateur (login, mdp) VALUES (?, ?)");
            return $stmt->execute([$login, $hashedPassword]);
        } catch (PDOException $e) {
            // Affiche l'erreur pour le débogage
            echo "Erreur : " . $e->getMessage();
            return false;
        }
    }
}
?>
