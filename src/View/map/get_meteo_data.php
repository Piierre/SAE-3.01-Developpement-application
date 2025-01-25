<?php
namespace App\Meteo\Controller;

require_once __DIR__ . '/../../config/Conf.php';

use App\Meteo\Config\Conf;
use PDO;
use PDOException;

header('Content-Type: application/json');

// Vérifier si une date est fournie
if (!isset($_GET['date'])) {
    echo json_encode(["error" => "Aucune date fournie."]);
    exit;
}

$date = $_GET['date'];

try {
    $pdo = Conf::getPDO();
    $stmt = $pdo->query("SELECT id, nom, latitude, longitude, code_reg FROM station");
    $stations = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $heatmapData = [];

    foreach ($stations as $station) {
        // Construire l'URL de l'API pour chaque station
        $apiUrl = "https://public.opendatasoft.com/api/explore/v2.1/catalog/datasets/donnees-synop-essentielles-omm/records?";
        $apiUrl .= "refine=numer_sta%3A%22" . urlencode($station['id']) . "%22";
        $apiUrl .= "&refine=date%3A%22" . urlencode($date) . "%22";

        $response = file_get_contents($apiUrl);
        if ($response) {
            $data = json_decode($response, true);
            $temperatures = array_column($data['results'], 'tc');
            if (!empty($temperatures)) {
                $averageTemp = array_sum($temperatures) / count($temperatures);
                $heatmapData[] = [
                    'lat' => (float)$station['latitude'],
                    'lon' => (float)$station['longitude'],
                    'temp' => (float)$averageTemp
                ];
            }
        }
    }

    // Clustering logic
    $clusteredData = clusterStations($heatmapData);

    echo json_encode($clusteredData);
} catch (PDOException $e) {
    echo json_encode(["error" => $e->getMessage()]);
}

function clusterStations($stations, $distance = 0.1) {
    $clusters = [];
    foreach ($stations as $station) {
        $added = false;
        foreach ($clusters as &$cluster) {
            if (haversine($station['lat'], $station['lon'], $cluster['lat'], $cluster['lon']) < $distance) {
                $cluster['temp'] = ($cluster['temp'] * $cluster['count'] + $station['temp']) / ($cluster['count'] + 1);
                $cluster['count']++;
                $added = true;
                break;
            }
        }
        if (!$added) {
            $station['count'] = 1;
            $clusters[] = $station;
        }
    }
    return $clusters;
}

function haversine($lat1, $lon1, $lat2, $lon2) {
    $earthRadius = 6371; // km
    $dLat = deg2rad($lat2 - $lat1);
    $dLon = deg2rad($lon2 - $lon1);
    $a = sin($dLat / 2) * sin($dLat / 2) +
         cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
         sin($dLon / 2) * sin($dLon / 2);
    $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
    return $earthRadius * $c;
}
?>


