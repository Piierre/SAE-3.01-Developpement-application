<?php

namespace App\Meteo\Controller;
require_once __DIR__ . '/../../config/Conf.php';
use App\Meteo\Config\Conf;
use PDO;

// Connexion à la base de données
$pdo = Conf::getPDO();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recherche de stations</title>
    <link rel="stylesheet" href="/SAE-3.01-Developpement-application/web/assets/css/search.css">
    <style>
    /* Styles spécifiques pour search.php */
    body {
        background-color: #ADD8E6; /* Fond bleu clair */
    }
    /* Ajouter plus de styles spécifiques ici */
    .btn-container {
        display: flex;
        justify-content: center;
        gap: 10px;
        margin-top: 20px;
    }
    </style>
</head>
<body>
    <header>
        <h1>Recherche de stations</h1>
    </header>
</body>
</html>

<?php
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
    $redirect = isset($_GET['redirect']) ? $_GET['redirect'] : false;

    // Appel à l'API via search_api.php
    $apiUrl = "http://localhost/SAE-3.01-Developpement-application/src/View/map/search_api.php?station_name=" . urlencode($stationName) . "&date=" . urlencode($date);
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $apiUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode !== 200 || !$response) {
        echo "<p>Erreur : Impossible de récupérer les données de l'API. Code HTTP : $httpCode</p>";
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
    
    echo '<button class="chart-type-button" id="showChartsButton">📊 Afficher les graphiques</button>';
    
    // Formulaire pour exporter en CSV
    echo '<form method="post" action="/SAE-3.01-Developpement-application/src/View/meteotheque/export_csv.php">
            <input type="hidden" name="station_name" value="' . htmlspecialchars($stationName) . '">
            <input type="hidden" name="date" value="' . htmlspecialchars($date) . '">
            <div class="btn-container">
                <button type="submit" class="btn_exporter">📄 Exporter en CSV</button>
                <button type="button" class="btn" onclick="window.location.href=\'/SAE-3.01-Developpement-application/web/frontController.php\'">🏠 Accueil</button>
            </div>
          </form>';

    if ($redirect) {
        echo '<script type="text/javascript">
            window.onload = function() {
                document.getElementById("showChartsButton").click();
            };
        </script>';
    }

    echo '<div id="charts" style="display: none; flex-direction: row;">
        <div class="chart-container" style="border: none;">
            <h2>Température</h2>
            <canvas id="temperatureChart"></canvas>
        </div>
        <div class="chart-container" style="border: none;">
            <h2>Humidité</h2>
            <canvas id="humidityChart"></canvas>
        </div>
        <div class="chart-container" style="border: none;">
            <h2>Vitesse du vent</h2>
            <canvas id="windChart"></canvas>
        </div>
    </div>';

    echo '<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>';
    echo '<script type="text/javascript">
    document.getElementById("showChartsButton").addEventListener("click", function() {
        let stationName = "' . htmlspecialchars($stationName) . '";
        let searchDate = "' . htmlspecialchars($date) . '";

        fetch(`/SAE-3.01-Developpement-application/src/View/station/station_data.php?station_name=${encodeURIComponent(stationName)}&date=${searchDate}`)
            .then(response => response.json())
            .then(data => {
                if (data.length === 0) {
                    alert("Aucune donnée trouvée pour cette station à cette date.");
                    return;
                }

                document.getElementById("charts").style.display = "flex";

                // Trier les données par heure dans lordre souhaité
                const sortedData = data.sort((a, b) => {
                    const order = ["00:00", "03:00", "06:00", "09:00", "12:00", "15:00", "18:00", "21:00"];
                    const aHour = new Date(a.date).toISOString().substring(11, 16);
                    const bHour = new Date(b.date).toISOString().substring(11, 16);
                    return order.indexOf(aHour) - order.indexOf(bHour);
                });

                let labels = sortedData.map(entry => new Date(entry.date).toISOString().substring(11, 16));
                let temperatures = sortedData.map(entry => entry.tc);
                let humidities = sortedData.map(entry => entry.u);
                let windSpeeds = sortedData.map(entry => entry.ff);

                createChart("temperatureChart", "Température (°C)", labels, temperatures, "red");
                createChart("humidityChart", "Humidité (%)", labels, humidities, "blue");
                createChart("windChart", "Vitesse du vent (m/s)", labels, windSpeeds, "green");
            })
            .catch(error => {
                console.error("Erreur lors de la récupération des données:", error);
            });
    });

    function createChart(canvasId, label, labels, data, color) {
        var ctx = document.getElementById(canvasId).getContext("2d");
        new Chart(ctx, {
            type: "line",
            data: {
                labels: labels,
                datasets: [{
                    label: label,
                    data: data,
                    borderColor: color,
                    backgroundColor: "rgba(0, 0, 0, 0)",
                    borderWidth: 2
                }]
            },
            options: {
                plugins: {
                    legend: {
                        labels: { color: "black" }
                    }
                },
                scales: {
                    x: {
                        ticks: { color: "black" }
                    },
                    y: {
                        ticks: { color: "black" }
                    }
                }
            }
        });
    }
    </script>';
} else {
    echo "<p>Veuillez spécifier un nom de station et une date.</p>";
}
?>
