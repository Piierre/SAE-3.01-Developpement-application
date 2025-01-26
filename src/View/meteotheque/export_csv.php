<?php
require_once __DIR__ . '/../../Controller/StationController.php';

use App\Meteo\Controller\StationController;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupération des données POST
    $stationName = $_POST['station_name'] ?? null;
    $searchDate = $_POST['date'] ?? null;

    // Vérifier si les variables existent
    if (!$stationName || !$searchDate) {
        die('Erreur : Les informations de station ou de date sont manquantes.');
    }

    // Debugging: Affiche les valeurs reçues
    error_log("Station: $stationName, Date: $searchDate");

    // Format de la date pour l'API
    $formattedDate = date("Y/m/d", strtotime($searchDate));
    $data = StationController::getStationData($stationName, $formattedDate);

    if (empty($data)) {
        die("Erreur : Aucune donnée à exporter.");
    }

    // Préparer le fichier CSV
    $filename = "station_data_" . str_replace(' ', '_', $stationName) . "_" . $searchDate . ".csv";

    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="' . $filename . '"');

    $output = fopen('php://output', 'w');
    // Ajouter BOM pour corriger l'UTF-8 dans Excel
    fputs($output, $bom = (chr(0xEF) . chr(0xBB) . chr(0xBF)));

    // En-têtes du fichier CSV
    fputcsv($output, [
        'Station', 'Date', 'Température (°C)', 'Humidité (%)', 
        'Vitesse du vent (m/s)', 'Direction du vent', 
        'Temp. Min (°C)', 'Temp. Max (°C)', 
        'Précipitations 6h (mm)', 'Précipitations 24h (mm)'
    ]);

    // Ajouter les données de la station
    foreach ($data as $record) {
        fputcsv($output, array_map('iconv', array_fill(0, 10, 'UTF-8'), array_fill(0, 10, 'ASCII//TRANSLIT'), [
            $stationName,
            $record['date'] ?? 'N/A',
            $record['tc'] ?? 'N/A',        // Température
            $record['u'] ?? 'N/A',         // Humidité
            $record['ff'] ?? 'N/A',        // Vitesse du vent
            $record['dd'] ?? 'N/A',        // Direction du vent
            $record['tn24'] ?? 'N/A',      // Température minimum
            $record['tx24'] ?? 'N/A',      // Température maximum
            $record['rr6'] ?? 'N/A',       // Précipitations sur 6h
            $record['rr24'] ?? 'N/A',      // Précipitations sur 24h
        ]));
    }

    fclose($output);
    exit;
} else {
    die("Erreur : Paramètres invalides.");
}
?>
