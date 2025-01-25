<?php
use App\Meteo\Model\MeteothequeModel;

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $meteothequeId = $_GET['id'];

    $meteotheque = MeteothequeModel::getMeteothequeById($meteothequeId);

    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="meteotheque.csv"');

    $output = fopen('php://output', 'w');
    fputcsv($output, ['Titre', 'Description', 'Date de création']);
    fputcsv($output, [$meteotheque['titre'], $meteotheque['description'], $meteotheque['date_creation']]);
    fclose($output);
    exit;
}
