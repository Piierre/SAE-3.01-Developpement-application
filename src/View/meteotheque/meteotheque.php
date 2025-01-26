<?php
require_once __DIR__ . '/../../../src/Model/MeteothequeModel.php';
require_once __DIR__ . '/../../../src/Lib/MessageFlash.php';

use App\Meteo\Model\MeteothequeModel;
use App\Meteo\Lib\MessageFlash;

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header('Location: /SAE-3.01-Developpement-application/web/frontController.php?page=login');
    exit;
}

$userId = $_SESSION['user_id']; 
$meteotheques = MeteothequeModel::getMeteothequesByUser($userId);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Météothèque</title>
    <link rel="stylesheet" href="/SAE-3.01-Developpement-application/web/assets/css/styles.css">
</head>
<style>
.back-button {
    padding: 10px 20px;
    font-size: 1rem;
    background-color: #28a745;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    position: absolute;
    top: 20px;
    right: 20px;
}

.back-button:hover {
    background-color: #218838;
    box-shadow: 0 5px 15px rgba(0,0,0,0.3);
}

.meteotheque-item {
    margin-bottom: 15px;
}

.meteotheque-item a {
    display: inline-block;
    margin-right: 15px;
    padding: 8px 15px;
    background-color: #007bff;
    color: white;
    text-decoration: none;
    border-radius: 5px;
    transition: background-color 0.3s;
}

.meteotheque-item a:hover {
    background-color: #0056b3;
}
</style>

<body>
    <header>
        <h1>Météothèque</h1>
        <button class="back-button" onclick="window.location.href='/SAE-3.01-Developpement-application/web/frontController.php';">🏠 Accueil</button>
    </header>
    <main>
        <section>
            <h2>Vos Météothèques</h2>
            <?php MessageFlash::displayFlashMessages(); ?>
            <ul>
    <?php if (!empty($meteotheques)): ?>
        <?php foreach ($meteotheques as $meteotheque): ?>
            <?php if (!empty($meteotheque['station_name']) && !empty($meteotheque['search_date'])): ?>
                <li>
                    <strong><?= htmlspecialchars($meteotheque['titre']) ?></strong> :
                    <?= htmlspecialchars($meteotheque['description']) ?>
                    <br>
                    <a href="/SAE-3.01-Developpement-application/src/View/map/search.php?station_name=<?= urlencode($meteotheque['station_name']) ?>&date=<?= urlencode($meteotheque['search_date']) ?>&redirect=true">🔍 Rechercher cette station</a>
                    <a href="/SAE-3.01-Developpement-application/src/View/map/search.php?station_name=<?= urlencode($meteotheque['station_name']) ?>&date=<?= urlencode($meteotheque['search_date']) ?>">📊 Voir les graphiques</a>
                    </li>
            <?php else: ?>
                <li>
                    <strong><?= htmlspecialchars($meteotheque['titre']) ?></strong> :
                    <em>Informations de recherche incomplètes.</em>
                </li>
            <?php endif; ?>
        <?php endforeach; ?>
    <?php else: ?>
        <li>Aucune météothèque trouvée.</li>
    <?php endif; ?>
</ul>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
function createChart(canvasId, label, labels, data, color) {
    var ctx = document.getElementById(canvasId).getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: label,
                data: data,
                borderColor: color,
                backgroundColor: 'rgba(0, 0, 0, 0)',
                borderWidth: 2
            }]
        },
        options: {
            plugins: {
                legend: {
                    labels: { color: 'black' }
                }
            },
            scales: {
                x: {
                    ticks: { color: 'black' }
                },
                y: {
                    ticks: { color: 'black' }
                }
            }
        }
    });
}

function fetchAndDisplayCharts(stationName, searchDate) {
    fetch(`/SAE-3.01-Developpement-application/src/View/station/station_data.php?station_name=${encodeURIComponent(stationName)}&date=${searchDate}`)
        .then(response => response.json())
        .then(data => {
            if (data.length === 0) {
                alert("Aucune donnée trouvée pour cette station à cette date.");
                return;
            }

            document.getElementById('charts').style.display = 'block';

            const sortedData = data.sort((a, b) => new Date(a.date) - new Date(b.date));
            let labels = sortedData.map(entry => new Date(entry.date).toLocaleTimeString('fr-FR', { hour: '2-digit', minute: '2-digit' }));
            let temperatures = sortedData.map(entry => entry.tc);
            let humidities = sortedData.map(entry => entry.u);
            let windSpeeds = sortedData.map(entry => entry.ff);

            createChart('temperatureChart', 'Température (°C)', labels, temperatures, 'red');
            createChart('humidityChart', 'Humidité (%)', labels, humidities, 'blue');
            createChart('windChart', 'Vitesse du vent (m/s)', labels, windSpeeds, 'green');
        })
        .catch(error => {
            console.error('Erreur lors de la récupération des données:', error);
        });
}

document.querySelectorAll('.meteotheque-item a').forEach(link => {
    link.addEventListener('click', function(event) {
        if (this.textContent.includes('📊 Voir les graphiques')) {
            event.preventDefault();
            const urlParams = new URLSearchParams(this.href.split('?')[1]);
            const stationName = urlParams.get('station_name');
            const searchDate = urlParams.get('date');
            fetchAndDisplayCharts(stationName, searchDate);
        }
    });
});
</script>

<div id="charts" style="display: none;">
    <div class="chart-container">
        <h2>Température</h2>
        <canvas id="temperatureChart"></canvas>
    </div>
    <div class="chart-container">
        <h2>Humidité</h2>
        <canvas id="humidityChart"></canvas>
    </div>
    <div class="chart-container">
        <h2>Vitesse du vent</h2>
        <canvas id="windChart"></canvas>
    </div>
</div>
<style>
#charts {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-around;
    gap: 30px;
    margin-top: 20px;
}

.chart-container {
    background: rgba(255, 255, 255, 0.1);
    padding: 20px;
    border-radius: 10px;
    width: 100%;
    max-width: 600px;
}

canvas {
    max-width: 100%;
    max-height: 400px;
}
</style>

        </section>
    </main>
    <footer>
        <p>&copy; 2025 - Station météo</p>
    </footer>
</body>
</html>
