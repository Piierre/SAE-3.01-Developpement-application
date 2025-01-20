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
            transition: background 0.3s ease, color 0.3s ease;
        }

        body.dark-mode {
            background: linear-gradient(to right, #2c3e50, #34495e);
            color: #ecf0f1;
        }

        h1 {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 20px;
            text-shadow: 2px 2px 10px rgba(0,0,0,0.2);
        }

        .toggle-mode {
            padding: 10px 20px;
            font-size: 1rem;
            background-color: #222;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-bottom: 20px;
        }

        .toggle-mode:hover {
            background-color: #444;
        }

        form {
            margin-bottom: 30px;
            background: rgba(255, 255, 255, 0.2);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            display: inline-block;
        }

        body.dark-mode form {
            background: rgba(255, 255, 255, 0.1);
        }

        label {
            font-size: 1.2rem;
            font-weight: 400;
        }

        input[type="date"] {
            padding: 10px;
            font-size: 1rem;
            border: none;
            border-radius: 5px;
            outline: none;
            margin: 10px 0;
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
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
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
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
            transition: transform 0.3s ease;
            width: 100%;
            max-width: 600px;
        }

        body.dark-mode .chart-container {
            background: rgba(255, 255, 255, 0.1);
        }

        .chart-container:hover {
            transform: translateY(-50px);
        }

        canvas {
            max-width: 100%;
            max-height: 400px;
            border: 2px solid rgba(253, 251, 251, 0.2);
            border-radius: 10px;
        }

        footer {
            margin-top: 40px;
            font-size: 0.9rem;
            color: rgba(255, 255, 255, 0.7);
        }
    </style>
</head>
<body>
    <h1>Données de la station <?php echo htmlspecialchars($stationName); ?></h1>

    <button class="toggle-mode" onclick="toggleDarkMode()">🌙 Mode sombre</button>

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

    <footer>
        SAE - Projet 3.01 - Développement d'application 
    </footer>

    <script>
        let charts = {}; 

        document.getElementById('dateForm').onsubmit = function(event) {
            event.preventDefault();
            var date = document.getElementById('date').value;
            var stationName = "<?php echo htmlspecialchars($stationName); ?>";

            fetch(`station_data.php?station_name=${encodeURIComponent(stationName)}&date=${date}`)
                .then(response => response.json())
                .then(data => {
                    if (data.length === 0) {
                        alert("Aucune donnée trouvée pour cette date.");
                        return;
                    }

                    data.sort((a, b) => new Date(a.date) - new Date(b.date));
                    let hours = data.map(entry => new Date(entry.date).toISOString().substring(11, 16));

                    destroyCharts();

                    createChart('temperatureChart', 'Température (°C)', hours, data.map(entry => entry.tc), 'red');
                    createChart('humidityChart', 'Humidité (%)', hours, data.map(entry => entry.u), 'blue');
                    createChart('windChart', 'Vitesse du vent (m/s)', hours, data.map(entry => entry.ff), 'green');
                })
                .catch(error => console.error('Erreur:', error));
        };

        function createChart(canvasId, label, labels, data, color) {
            var ctx = document.getElementById(canvasId).getContext('2d');
            let textColor = document.body.classList.contains('dark-mode') ? '#ecf0f1' : '#000'; 

            charts[canvasId] = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: label,
                        data: data,
                        borderColor: color,
                        backgroundColor: 'rgba(0, 0, 0, 0)',
                        borderWidth: 2,
                        pointRadius: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        x: {
                            ticks: { color: textColor },
                            title: { display: true, text: 'Heures', color: textColor }
                        },
                        y: {
                            ticks: { color: textColor },
                            title: { display: true, text: label, color: textColor }
                        }
                    }
                }
            });
        }

        function destroyCharts() {
            for (let key in charts) {
                if (charts[key]) {
                    charts[key].destroy();
                }
            }
            charts = {};
        }

        function toggleDarkMode() {
            document.body.classList.toggle('dark-mode');
            localStorage.setItem('darkMode', document.body.classList.contains('dark-mode'));
        }

        if (localStorage.getItem('darkMode') === 'true') {
            document.body.classList.add('dark-mode');
        }
    </script>
</body>
</html>
