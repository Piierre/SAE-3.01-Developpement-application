<?php
namespace App\Meteo\Controller;

use App\Meteo\Config\Conf;
use PDO;

class StationController {
    public static function getStationData($stationName, $date) {
        $apiUrl = "https://public.opendatasoft.com/api/explore/v2.1/catalog/datasets/donnees-synop-essentielles-omm/records?";
        $apiUrl .= "limit=100&refine=date%3A%22" . urlencode($date) . "%22";
        $apiUrl .= "&refine=nom%3A%22" . urlencode($stationName) . "%22";

        $response = file_get_contents($apiUrl);
        return json_decode($response, true)['results'] ?? [];
    }
}
?>
