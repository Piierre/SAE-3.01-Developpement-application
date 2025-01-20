<?php

namespace App\Meteo\Controller;

use App\Meteo\Config\Conf;
use PDO;

class StationController {

    public static function getStationData($stationName, $date) {
        // Formatage de la date pour l'API
        $formattedDate = date("Y/m/d", strtotime($date));

        // URL de l'API OpenDataSoft
        $apiUrl = "https://public.opendatasoft.com/api/explore/v2.1/catalog/datasets/donnees-synop-essentielles-omm/records?";
        $apiUrl .= "limit=100&refine=date%3A%22" . urlencode($formattedDate) . "%22";
        $apiUrl .= "&refine=nom%3A%22" . urlencode($stationName) . "%22";

        // Récupération des données API
        $response = file_get_contents($apiUrl);

        if (!$response) {
            return ['error' => 'Erreur lors de la récupération des données'];
        }

        $data = json_decode($response, true);

        
        // Traitement des données pour affichage
        $processedData = [];
        foreach ($data['results'] as $record) {
            $processedData[] = [
                'date' => $record['date'],
                'temperature' => $record['tc'] ?? null,  // Température en °C
                'humidite' => $record['u'] ?? null,   // Humidité
                'vent' => $record['ff'] ?? null,  // Vitesse du vent
                'precipitations' => $record['rr6'] ?? null  // Précipitations sur 6 heures
            ];
        }

        return $processedData;
    }
}
