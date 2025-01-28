<?php
namespace App\Meteo\Controller;

// Inclure le fichier de configuration 
require_once __DIR__ . '/../Config/Conf.php';
use App\Meteo\Config\Conf;
use PDO;

class CarteController {

    // Méthode pour récupérer toutes les stations météo de la base de données
    public static function getStations() {
        try {
            // Établir une connexion à la base de données via PDO
            $pdo = Conf::getPDO();

            // Préparer et exécuter la requête SQL pour obtenir les informations des stations
            $sql = "SELECT nom, latitude, longitude, altitude FROM station";
            $stmt = $pdo->query($sql);

            // Récupérer tous les résultats sous forme de tableau associatif et les retourner
            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (\Exception $e) {
            // En cas d'erreur, afficher le message et retourner un tableau vide
            echo "Erreur : " . $e->getMessage();
            return [];
        }
    }
}
?>
