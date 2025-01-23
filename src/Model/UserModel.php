<?php
namespace App\Meteo\Model;

require_once __DIR__ . '/../Config/Conf.php'; // Ensure this path is correct
use App\Meteo\Config\Conf; // Ensure this class exists and is correctly namespaced
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

    public static function getAllUsers() {
        $pdo = Conf::getPDO();
        $stmt = $pdo->prepare("SELECT * FROM Utilisateur WHERE role != 'admin'");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function banUser($userId) {
        $pdo = Conf::getPDO();
        $stmt = $pdo->prepare("UPDATE Utilisateur SET status = 'banned' WHERE id = ?");
        $stmt->execute([$userId]);
    }

    public static function getPendingUsers() {
        $pdo = Conf::getPDO();
        $stmt = $pdo->prepare("SELECT * FROM Utilisateur WHERE status = 'pending'");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function approveUser($userId) {
        $pdo = Conf::getPDO();
        $stmt = $pdo->prepare("UPDATE Utilisateur SET status = 'active' WHERE id = ?");
        $stmt->execute([$userId]);
    }

    public static function rejectUser($userId) {
        $pdo = Conf::getPDO();
        $stmt = $pdo->prepare("DELETE FROM Utilisateur WHERE id = ?");
        $stmt->execute([$userId]);
    }
}
?>
