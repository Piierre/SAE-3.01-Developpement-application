<?php
$stationName = isset($_POST['station_name']) ? $_POST['station_name'] : '';
$searchDate = isset($_POST['date']) ? $_POST['date'] : '';
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/SAE-3.01-Developpement-application/web/assets/css/recherche.css">
</head>
    <title>Recherche de Station Météo</title>
    
    <script src="/SAE-3.01-Developpement-application/web/assets/css/js/recherche.js"></script>

</head>
<body>
    <button class="btn" onclick="window.location.href='/SAE-3.01-Developpement-application/web/frontController.php'">🏠 Accueil</button>
    <button class="btn" id="darkModeButton" style="right: 160px;" onclick="toggleDarkMode()">🌙 Mode sombre</button>

    <h1>Recherche de Station Météo</h1>
    <div class="container">
        <input 
            type="text" 
            id="search" 
            placeholder="Rechercher une station..." 
            value="<?= htmlspecialchars($stationName) ?>"
            onkeyup="searchStations(this.value)"
        >
        <div id="suggestions" class="suggestions"></div>

        <input 
            type="date" 
            id="date"   
            placeholder="Sélectionner une date"
            value="<?= htmlspecialchars($searchDate) ?>"
        >
        <button onclick="searchMeasures()">Rechercher</button>

        <form action="/SAE-3.01-Developpement-application/web/frontController.php?action=addMeteotheque" method="post">
            <input type="hidden" name="titre" value="Recherche: <?= htmlspecialchars($stationName) ?>">
            <input type="hidden" name="description" value="Recherche pour la station <?= htmlspecialchars($stationName) ?> à la date <?= htmlspecialchars($searchDate) ?>">
            <input type="hidden" name="station_name" value="<?= htmlspecialchars($stationName) ?>">
            <input type="hidden" name="search_date" value="<?= htmlspecialchars($searchDate) ?>">
            <button type="submit">Ajouter cette recherche à ma Météothèque</button>
        </form>

        <div id="results" class="results">
            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Température (°C)</th>
                        <th>Humidité (%)</th>
                        <th>Vent (m/s)</th>
                        <th>Précipitations (mm)</th>
                    </tr>
                </thead>
                <tbody id="dataTable">
                </tbody>
            </table>
        </div>
    </div>

<<<<<<< HEAD
=======

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

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.getElementById('showChartsButton').addEventListener('click', function() {
    let stationName = document.getElementById('search').value.trim();
    let searchDate = document.getElementById('date').value.trim();

    if (!stationName || !searchDate) {
        alert("Veuillez remplir tous les champs avant d'afficher les graphiques.");
        return;
    }

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
});

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
</script>


>>>>>>> 3a946d7eb7a12a30b2be7f1319782fcb5c997eb4
    <footer>
        SAE - Projet 3.01 - Développement d'application
    </footer>
</body>
</html>
