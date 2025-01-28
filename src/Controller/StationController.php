<?php
namespace App\Meteo\Controller;

// Import des classes nécessaires
use App\Meteo\Config\Conf;
use PDO;

class StationController {
    // Cette méthode récupère les données de la station pour un nom de station et une date donnés
    public static function getStationData($stationName, $date) {
        // URL de l'API pour récupérer les données
        $apiUrl = "https://public.opendatasoft.com/api/explore/v2.1/catalog/datasets/donnees-synop-essentielles-omm/records?";
        $apiUrl .= "limit=100&refine=date%3A%22" . urlencode($date) . "%22";
        $apiUrl .= "&refine=nom%3A%22" . urlencode($stationName) . "%22";

        // Récupération de la réponse de l'API
        $response = file_get_contents($apiUrl);
        // Décodage de la réponse JSON et retour des résultats
        return json_decode($response, true)['results'] ?? [];
    }
}
?>
