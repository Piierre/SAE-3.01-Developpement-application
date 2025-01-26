<?php
require_once __DIR__ . '/../../Model/MeteothequeModel.php';

use App\Meteo\Model\MeteothequeModel;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['meteotheque_id'])) {
    $meteothequeId = $_POST['meteotheque_id'];

    // Récupération des données de la météothèque
    $meteotheque = MeteothequeModel::getMeteothequeById($meteothequeId);

    if (!$meteotheque) {
        die("Erreur : Météothèque non trouvée.");
    }

    // Nom du fichier CSV
    $filename = "meteotheque_" . $meteothequeId . ".csv";

    // En-têtes HTTP pour le téléchargement
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="' . $filename . '"');

    // Ouverture du flux de sortie
    $output = fopen('php://output', 'w');

    // En-têtes du fichier CSV
    fputcsv($output, ['Titre', 'Description', 'Station', 'Date de recherche']);

    // Ajout des données de la météothèque
    fputcsv($output, [
        $meteotheque['titre'],
        $meteotheque['description'],
        $meteotheque['station_name'],
        $meteotheque['search_date']
    ]);

    fclose($output);
    exit;
}
?>
