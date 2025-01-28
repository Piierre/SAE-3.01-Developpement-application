<?php
namespace App\Meteo\Model;

require_once __DIR__ . '/../Config/Conf.php'; // Assurez-vous que le chemin est correct
use App\Meteo\Config\Conf; // Assurez-vous que cette classe existe et est correctement nommée
use PDO;
use PDOException;

class UserModel {
    // Récupère un utilisateur par son login
    public static function getUserByLogin($login) {
        // Récupère la connexion PDO via la classe Conf
        $pdo = Conf::getPDO();
        $stmt = $pdo->prepare("SELECT * FROM Utilisateur WHERE login = ?");
        $stmt->execute([$login]);
        return $stmt->fetch(PDO::FETCH_ASSOC); // Utiliser FETCH_ASSOC pour éviter d'obtenir des doublons
    }

    // Crée un nouvel utilisateur
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

    // Récupère tous les utilisateurs sauf les admins et ceux en attente
    public static function getAllUsers() {
        $pdo = Conf::getPDO();
        $stmt = $pdo->prepare("SELECT * FROM Utilisateur WHERE role != 'admin' AND status != 'pending'");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Bannit un utilisateur
    public static function banUser($userId) {
        $pdo = Conf::getPDO();
        $stmt = $pdo->prepare("UPDATE Utilisateur SET status = 'banned' WHERE id = ?");
        $stmt->execute([$userId]);
    }

    // Récupère les utilisateurs en attente
    public static function getPendingUsers() {
        $pdo = Conf::getPDO();
        $stmt = $pdo->prepare("SELECT * FROM Utilisateur WHERE status = 'pending'");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Approuve un utilisateur
    public static function approveUser($userId) {
        $pdo = Conf::getPDO();
        $stmt = $pdo->prepare("UPDATE Utilisateur SET status = 'active' WHERE id = ?");
        $stmt->execute([$userId]);
    }

    // Rejette un utilisateur
    public static function rejectUser($userId) {
        $pdo = Conf::getPDO();
        $stmt = $pdo->prepare("DELETE FROM Utilisateur WHERE id = ?");
        $stmt->execute([$userId]);
    }
}
?>
