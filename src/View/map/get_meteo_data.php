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

    echo json_encode($heatmapData);
} catch (PDOException $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
?>
