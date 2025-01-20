<?php

namespace App\Meteo\Controller;
require_once __DIR__ . '/../../config/Conf.php';

use App\Meteo\Config\Conf;
use PDO;

$pdo = Conf::getPDO();

// Recherche des stations par nom
if (isset($_GET['query'])) {
    $query = $_GET['query'] . '%';
    $stmt = $pdo->prepare("SELECT nom FROM Station WHERE nom LIKE ? ORDER BY nom LIMIT 10");
    $stmt->execute([$query]);

    if ($stmt->rowCount() > 0) {
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo '<div onclick="selectStation(\'' . htmlspecialchars($row['nom']) . '\')">';
            echo htmlspecialchars($row['nom']);
            echo '</div>';
        }
    } else {
        echo '<p>Aucune station trouvée.</p>';
    }
    exit;
}

// Récupération des mesures pour une station spécifique à une date précise
if (isset($_GET['station_name']) && isset($_GET['date'])) {
    $stationName = $_GET['station_name'];
    $date = $_GET['date'];

    // Conversion de la date au format requis par l'API (YYYY/MM/DD)
    $formattedDate = date("Y/m/d", strtotime($date));

    // URL de l'API OpenDataSoft avec la station et la date précises
    $apiUrl = "https://public.opendatasoft.com/api/explore/v2.1/catalog/datasets/donnees-synop-essentielles-omm/records?";
    $apiUrl .= "limit=20&refine=date%3A%22" . urlencode($formattedDate) . "%22";
    $apiUrl .= "&refine=nom%3A%22" . urlencode($stationName) . "%22";

    // Récupération des données de l'API
    $response = file_get_contents($apiUrl);

    if (!$response) {
        echo "<p>Erreur : Impossible de récupérer les données de l'API.</p>";
        exit;
    }

    $data = json_decode($response, true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        echo "<p>Erreur de décodage JSON : " . json_last_error_msg() . "</p>";
        exit;
    }

    if (!isset($data['results']) || empty($data['results'])) {
        echo '<p>Aucune mesure trouvée pour cette station et cette date.</p>';
        exit;
    }

    // Affichage des données sous forme de tableau sans utiliser 'fields'
    echo '<h2>Mesures pour la station ' . htmlspecialchars($stationName) . ' le ' . htmlspecialchars($date) . '</h2>';
    echo '<table border="1">
            <tr>
                <th>Date</th>
                <th>Pression</th>
                <th>Température (°C)</th>
                <th>Humidité (%)</th>
                <th>Vent (Direction)</th>
                <th>Vent (Vitesse) (m/s)</th>
                <th>Temp. Min</th>
                <th>Temp. Max</th>
                <th>Précip. 6h (mm)</th>
                <th>Précip. 24h (mm)</th>
            </tr>';

    foreach ($data['results'] as $record) {
        echo '<tr>';
        echo '<td>' . htmlspecialchars($record['date'] ?? 'N/A') . '</td>';
        echo '<td>' . htmlspecialchars($record['pmer'] ?? 'N/A') . '</td>';
        echo '<td>' . htmlspecialchars($record['tc'] ?? 'N/A') . '</td>';
        echo '<td>' . htmlspecialchars($record['u'] ?? 'N/A') . '</td>';
        echo '<td>' . htmlspecialchars($record['dd'] ?? 'N/A') . '</td>';
        echo '<td>' . htmlspecialchars($record['ff'] ?? 'N/A') . '</td>';
        echo '<td>' . htmlspecialchars($record['tn24'] ?? 'N/A') . '</td>';
        echo '<td>' . htmlspecialchars($record['tx24'] ?? 'N/A') . '</td>';
        echo '<td>' . htmlspecialchars($record['rr6'] ?? 'N/A') . '</td>';
        echo '<td>' . htmlspecialchars($record['rr24'] ?? 'N/A') . '</td>';
        echo '</tr>';
    }

    echo '</table>';
} else {
    echo "<p>Veuillez spécifier un nom de station et une date.</p>";
}
?>
