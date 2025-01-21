<?php
namespace App\Meteo\Controller;

require_once __DIR__ . '/../Config/Conf.php'; // Ensure this path is correct
use App\Meteo\Config\Conf; // Ensure this class exists and is correctly namespaced
use PDO;

class CarteController {

    public static function getStations() {
        try {
            // Connexion à la base de données
            $pdo = Conf::getPDO();

            // Requête pour récupérer les stations
            $sql = "SELECT nom, latitude, longitude, altitude FROM station";
            $stmt = $pdo->query($sql);

            // Retourner les données sous forme d'un tableau associatif
            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (\Exception $e) {
            // Gestion des erreurs
            echo "Erreur : " . $e->getMessage();
            return [];
        }
    }
}
?>
