<?php
namespace App\Meteo\Model;

require_once __DIR__ . '/../Config/Conf.php'; // Ensure this path is correct
use App\Meteo\Config\Conf; // Ensure this class exists and is correctly namespaced
use PDO;
use PDOException;

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
    
    public static function getMeteothequeById($meteothequeId) {
        $pdo = Conf::getPDO();
        $stmt = $pdo->prepare("SELECT * FROM Meteotheque WHERE id = ?");
        $stmt->execute([$meteothequeId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    

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
    
}
?>
