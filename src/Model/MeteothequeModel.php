<?php
namespace App\Meteo\Model;

require_once __DIR__ . '/../Config/Conf.php'; // Ensure this path is correct
use App\Meteo\Config\Conf; // Ensure this class exists and is correctly namespaced
use PDO;

class MeteothequeModel {
    public static function getAllMeteotheques() {
        $pdo = Conf::getPDO();
        $stmt = $pdo->query("SELECT * FROM Meteotheque");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getMeteothequesByUser($userId) {
        if ($userId === null) {
            return [];
        }
        $pdo = Conf::getPDO();
        $stmt = $pdo->prepare("SELECT * FROM Meteotheque WHERE user_id = ?");
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getMeteothequeById($id) {
        $pdo = Conf::getPDO();
        $stmt = $pdo->prepare("SELECT * FROM Meteotheque WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function createMeteotheque($userId, $titre, $description) {
        $pdo = Conf::getPDO();
        $stmt = $pdo->prepare("INSERT INTO Meteotheque (user_id, titre, description) VALUES (?, ?, ?)");
        $stmt->execute([$userId, $titre, $description]);
        return $pdo->lastInsertId();
    }
}
?>
