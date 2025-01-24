<?php
require_once __DIR__ . "/../../Controller/StationController.php";

use App\Meteo\Controller\StationController;

if (isset($_GET['station_name']) && isset($_GET['date'])) {
    $stationName = $_GET['station_name'];
    $date = $_GET['date'];

    // Format de la date requis par l'API
    $formattedDate = date("Y/m/d", strtotime($date));

    // Appel API OpenDataSoft
    $data = StationController::getStationData($stationName, $formattedDate);

    if (empty($data)) {
        header('Content-Type: application/json');
        echo json_encode([]);
        exit;
    }

    $processedData = [];
    foreach ($data as $record) {
        $processedData[] = [
            'date' => $record['date'],
            'tc' => $record['tc'] ?? null,  // Température (°C)
            'u' => $record['u'] ?? null,   // Humidité (%)
            'ff' => $record['ff'] ?? null,  // Vitesse du vent (m/s)
        ];
    }

    header('Content-Type: application/json');
    echo json_encode($processedData);
    exit;
}
?>
