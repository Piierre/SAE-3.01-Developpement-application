<?php
$stationName = isset($_GET['name']) ? $_GET['name'] : '';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Données de la station <?php echo htmlspecialchars($stationName); ?></title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;700&display=swap" rel="stylesheet">
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Roboto', sans-serif;
        }

        body {
            background: linear-gradient(to right,rgb(168, 172, 175), #00f2fe);
            color: #fff;
            padding: 20px;
            text-align: center;
        }

        h1 {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 20px;
        }

        .back-button {
            padding: 10px 20px;
            font-size: 1rem;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.3s ease;
            position: absolute;
            top: 20px;
            right: 20px;
        }

        .back-button:hover {
            background-color: #218838;
        }

        form {
            background: rgba(255, 255, 255, 0.2);
            padding: 20px;
            border-radius: 10px;
            display: inline-flex;
            gap: 10px;
        }

        input[type="date"] {
            padding: 10px;
            font-size: 1rem;
            border: none;
            border-radius: 5px;
            outline: none;
            color: #333;
        }

        button {
            padding: 12px 25px;
            font-size: 1rem;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background-color: #218838;
        }

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

        .compare-button {
            margin-top: 30px;
            padding: 15px 30px;
            font-size: 1rem;
            background-color:rgb(14, 134, 219);
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-block;
        }

        .compare-button:hover {
            background-color:rgb(0, 69, 118);
        }

        footer {
            margin-top: 40px;
            font-size: 0.9rem;
        }

        /* Dark mode styles */
        body.dark-mode {
            background: linear-gradient(to right, #2c3e50, #4ca1af);
            color: #ddd;
        }

        .dark-mode h1, .dark-mode footer {
            color: #ddd;
        }

        .dark-mode .back-button, .dark-mode .compare-button, .dark-mode button {
            background-color: #444;
        }

        .dark-mode .back-button:hover, .dark-mode .compare-button:hover, .dark-mode button:hover {
            background-color: #333;
        }

        .dark-mode form {
            background: rgba(255, 255, 255, 0.1);
        }
    </style>
</head>
<body>
    <h1>Données de la station <?php echo htmlspecialchars($stationName); ?></h1>
    <button class="back-button" onclick="window.location.href='/SAE-3.01-Developpement-application/web/frontController.php';">🏠 Accueil</button>
<button id="darkModeToggle" class="back-button" style="right: 160px;">🌙 Mode sombre</button>
<button class="back-button" onclick="window.location.href='/SAE-3.01-Developpement-application/web/frontController.php?page=carte';" style="right: 350px;">🗺️ Carte</button>

    <form id="dateForm">
        <label for="date">Choisir une date :</label>
        <input type="date" id="date" required>
        <button type="submit">Afficher les données</button>
    </form>

    

    <div id="charts">
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

    <button class="compare-button" id="compareBtn">📊📅 Comparer avec une autre date</button>

    <footer>
        SAE - Projet 3.01 - Développement d'application 
    </footer>

    <script>
    let charts = {}; 

    document.getElementById('dateForm').onsubmit = function(event) {
        event.preventDefault();
        fetchData(document.getElementById('date').value, false);
    };

    document.getElementById('compareBtn').onclick = function() {
        const secondDate = prompt("Entrez une autre date pour la comparaison (AAAA-MM-JJ):");
        if (secondDate) {
            fetchData(secondDate, true);
        }
    };

    function fetchData(date, isComparison) {
    var stationName = "<?php echo htmlspecialchars($stationName); ?>";

    fetch(`station_data.php?station_name=${encodeURIComponent(stationName)}&date=${date}`)
        .then(response => response.json())
        .then(data => {
            if (data.length === 0) {
                alert("Aucune donnée trouvée pour cette date.");
                return;
            }

            // Trier les données par heure dans l'ordre souhaité
            const sortedData = data.sort((a, b) => {
                const order = ["00:00", "03:00", "06:00", "09:00", "12:00", "15:00", "18:00", "21:00"];
                const aHour = new Date(a.date).toISOString().substring(11, 16);
                const bHour = new Date(b.date).toISOString().substring(11, 16);
                return order.indexOf(aHour) - order.indexOf(bHour);
            });

            let hours = sortedData.map(entry => new Date(entry.date).toISOString().substring(11, 16));

            if (isComparison) {
                addComparisonData(hours, sortedData);
            } else {
                destroyCharts();
                createChart('temperatureChart', 'Température (°C)', hours, sortedData.map(entry => entry.tc), 'red');
                createChart('humidityChart', 'Humidité (%)', hours, sortedData.map(entry => entry.u), 'blue');
                createChart('windChart', 'Vitesse du vent (m/s)', hours, sortedData.map(entry => entry.ff), 'black');
            }
        })
        .catch(error => console.error('Erreur:', error));
}


    function addComparisonData(labels, newData) {
        addDataToChart('temperatureChart', labels, newData.map(entry => entry.tc), 'orange', 'Comparaison Température');
        addDataToChart('humidityChart', labels, newData.map(entry => entry.u), 'cyan', 'Comparaison Humidité');
        addDataToChart('windChart', labels, newData.map(entry => entry.ff), 'purple', 'Comparaison Vent');
    }

    function createChart(canvasId, label, labels, data, color) {
        var ctx = document.getElementById(canvasId).getContext('2d');
        let textColor = document.body.classList.contains('dark-mode') ? '#ecf0f1' : '#000';

        if (charts[canvasId]) {
            charts[canvasId].destroy();
        }

        charts[canvasId] = new Chart(ctx, {
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
                        labels: {
                            color: textColor // Changement de couleur de la légende en fonction du mode
                        }
                    }
                },
                scales: {
                    x: {
                        ticks: { color: textColor } // Changement de couleur des labels axe X
                    },
                    y: {
                        ticks: { color: textColor } // Changement de couleur des labels axe Y
                    }
                }
            }
        });
    }

    function addDataToChart(canvasId, labels, data, color, legend) {
        if (charts[canvasId]) {
            charts[canvasId].data.datasets.push({
                label: legend,
                data: data,
                borderColor: color,
                backgroundColor: 'rgba(0, 0, 0, 0)',
                borderWidth: 2
            });
            charts[canvasId].update();
        }
    }

    function destroyCharts() {
        for (let key in charts) {
            charts[key].destroy();
        }
        charts = {};
    }

    // Mode sombre toggle
    document.getElementById('darkModeToggle').onclick = function() {
        document.body.classList.toggle('dark-mode');

        let textColor = document.body.classList.contains('dark-mode') ? '#ecf0f1' : '#000';
        for (let key in charts) {
            charts[key].options.scales.x.ticks.color = textColor;
            charts[key].options.scales.y.ticks.color = textColor;
            charts[key].options.plugins.legend.labels.color = textColor; // Changement de la légende
            charts[key].update();
        }
    };
</script>


</body>
</html>
