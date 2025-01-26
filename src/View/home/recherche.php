<?php
$stationName = isset($_GET['station_name']) ? $_GET['station_name'] : '';
$searchDate = isset($_GET['date']) ? $_GET['date'] : '';
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/SAE-3.01-Developpement-application/web/assets/css/recherche.css">
    <style>
    #charts {
        display: flex;
        flex-wrap: nowrap;
        justify-content: space-between;
        gap: 10px;
        margin-top: 20px;
        overflow-x: auto;
    }

    .chart-container {
        background: rgba(255, 255, 255, 0.1);
        padding: 10px;
        border-radius: 10px;
        width: 300px;
        flex: 0 0 auto;
    }

    canvas {
        max-width: 100%;
        max-height: 200px;
    }
    </style>
</head>
<title>Recherche de Station Météo</title>

<script src="/SAE-3.01-Developpement-application/web/assets/css/js/recherche.js"></script>

<script>
document.addEventListener("DOMContentLoaded", function() {
    document.querySelector("form").addEventListener("submit", function(event) {
        let stationName = document.getElementById('search').value.trim();
        let searchDate = document.getElementById('date').value.trim();
        
        if (!stationName || !searchDate) {
            alert("Veuillez remplir tous les champs avant d'ajouter à la Météothèque.");
            event.preventDefault();
        }
    });
});
</script>

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

        <form action="/SAE-3.01-Developpement-application/web/frontController.php?action=addMeteotheque" method="post" onsubmit="return validateForm()">
    <input type="hidden" name="titre" id="hiddenTitre">
    <input type="hidden" name="description" id="hiddenDescription">
    <input type="hidden" name="station_name" id="hiddenStation">
    <input type="hidden" name="search_date" id="hiddenDate">
    <input type="hidden" name="redirect" value="true">  <!-- Ajout du paramètre de redirection -->

    <button type="submit">Ajouter cette recherche à ma Météothèque</button>
</form>


        <script>
        function validateForm() {
    let stationName = document.getElementById('search').value.trim();
    let searchDate = document.getElementById('date').value.trim();

    if (stationName === '' || searchDate === '') {
        alert("Veuillez remplir tous les champs avant d'ajouter à la Météothèque.");
        return false;
    }

    // Mettre à jour les valeurs des champs cachés avant l'envoi
    document.getElementById('hiddenTitre').value = `Recherche: ${stationName}`;
    document.getElementById('hiddenDescription').value = `Recherche pour la station ${stationName} à la date ${searchDate}`;
    document.getElementById('hiddenStation').value = stationName;
    document.getElementById('hiddenDate').value = searchDate;

    return true;
}


        function searchMeasures() {
            let stationName = document.getElementById("search").value.trim();
            let date = document.getElementById("date").value.trim();

            if (stationName === "" || date === "") {
                alert("Veuillez remplir tous les champs.");
                return;
            }

            fetch(`/SAE-3.01-Developpement-application/src/View/map/search.php?station_name=${encodeURIComponent(stationName)}&date=${encodeURIComponent(date)}`)
                .then(response => response.text())
                .then(data => {
                    document.getElementById("dataTable").innerHTML = data;
                })
                .catch(error => {
                    console.error('Erreur lors de la récupération des données:', error);
                    alert("Erreur lors de la récupération des données.");
                });
        }
        </script>
<script>
window.onload = function() {
    const urlParams = new URLSearchParams(window.location.search);
    let stationName = urlParams.get('station_name');
    let searchDate = urlParams.get('date');

    if (stationName && searchDate) {
        document.getElementById('search').value = stationName;
        document.getElementById('date').value = searchDate;
        
        // Effectuer la recherche et afficher les graphiques automatiquement
        searchMeasures();
    }
};
</script>



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

    <button class="chart-type-button" id="showChartsButton">📊 Afficher les graphiques</button>

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


    <footer>
        SAE - Projet 3.01 - Développement d'application
    </footer>
</body>
</html>
