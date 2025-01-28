<?php
namespace App\Meteo\Model;

require_once __DIR__ . '/../Config/Conf.php'; // Assurez-vous que le chemin est correct
use App\Meteo\Config\Conf; // Assurez-vous que cette classe existe et est correctement nommée
use PDO;
use PDOException;

class MeteothequeModel {
    // Récupère toutes les entrées de la Météothèque
    public static function getAllMeteotheques() {
        $pdo = Conf::getPDO();
        $stmt = $pdo->prepare("
            SELECT m.id, m.titre, m.description, m.station_name, m.search_date, m.date_creation,
                   u.id AS creator_id, u.login AS creator_login
            FROM Meteotheque m
            INNER JOIN Utilisateur u ON m.user_id = u.id
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Récupère les entrées de la Météothèque pour un utilisateur spécifique
    public static function getMeteothequesByUser($userId) {
        if ($userId === null) {
            return [];
        }
        $pdo = Conf::getPDO();
        $stmt = $pdo->prepare("SELECT * FROM Meteotheque WHERE user_id = ?");
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Récupère une entrée de la Météothèque par son ID
    public static function getMeteothequeById($meteothequeId) {
        $pdo = Conf::getPDO();
        $stmt = $pdo->prepare("SELECT * FROM Meteotheque WHERE id = ?");
        $stmt->execute([$meteothequeId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    // Crée une nouvelle entrée dans la Météothèque
    public static function createMeteotheque($userId, $titre, $description, $stationName, $searchDate) {
        $pdo = Conf::getPDO();
    
        // Remplacer les valeurs vides par NULL pour éviter les erreurs SQL
        $stationName = !empty($stationName) ? $stationName : null;
        $searchDate = !empty($searchDate) ? $searchDate : null;
    
        // Préparer la requête SQL
        $stmt = $pdo->prepare("INSERT INTO Meteotheque (user_id, titre, description, station_name, search_date) VALUES (?, ?, ?, ?, ?)");
    
        try {
            // Exécuter la requête avec des valeurs sécurisées
            $stmt->execute([
                $userId,
                $titre,
                $description,
                $stationName,
                $searchDate
            ]);
    
            // Retourner l'ID de l'enregistrement inséré
            return $pdo->lastInsertId();
    
        } catch (PDOException $e) {
            // Enregistrer l'erreur et retourner false
            error_log("Erreur lors de l'insertion dans la Météothèque : " . $e->getMessage());
            return false;
        }
    }
    
    // Supprime une entrée de la Météothèque par son ID
    public static function deleteMeteotheque($meteothequeId) {
        $pdo = Conf::getPDO();
        $stmt = $pdo->prepare("DELETE FROM Meteotheque WHERE id = ?");
        $stmt->execute([$meteothequeId]);
    }
}
?>
