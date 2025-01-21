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

        /* Conteneur des boutons pour une meilleure disposition */
.button-container {
    display: flex;
    justify-content: center;
    flex-wrap: wrap;
    gap: 10px;
    margin-bottom: 20px;
}

/* Ajustement des boutons */
.back-button {
    position: relative; /* Désactiver position absolue */
    padding: 12px 20px;
    font-size: 1rem;
    background-color: #28a745;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.back-button:hover {
    background-color: #218838;
}

/* Media query pour les petits écrans */
@media screen and (max-width: 768px) {
    h1 {
        font-size: 1.5rem;
    }

    .button-container {
        flex-direction: column;
        align-items: center;
    }

    .back-button {
        width: 90%;
        font-size: 1rem;
    }

    form {
        flex-direction: column;
        width: 100%;
    }

    input[type="date"], button {
        width: 90%;
    }

    #charts {
        flex-direction: column;
        gap: 20px;
    }

    .chart-container {
        max-width: 100%;
    }
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

        /* Style du loader */
        #loader {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: #f3f3f3;
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
        }

        /* Animation du spinner */
        .spinner {
            border: 8px solid #f3f3f3;
            border-top: 8px solid #3498db;
            border-radius: 50%;
            width: 60px;
            height: 60px;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Contenu caché initialement */
        #content {
            display: none;
        }
/* Conteneur de la barre de recherche */
#stationSearchContainer {
    position: relative;
    display: inline-block;
    width: 100%;
    max-width: 400px;
    margin: 20px auto;
}

/* Style pour la barre de recherche */
#searchStation {
    padding: 12px 20px;
    font-size: 1rem;
    border: none;
    border-radius: 5px;
    outline: none;
    color: #333;
    width: 100%;
    text-align: center;
    background: rgba(255, 255, 255, 0.9);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

/* Style pour la liste des suggestions */
#stationSuggestions {
    position: absolute;
    top: 100%;  /* Place la liste juste en dessous du champ */
    left: 0;
    right: 0;
    z-index: 1000;
    border-radius: 5px;
    background: rgba(255, 255, 255, 0.95);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    max-height: 200px;
    overflow-y: auto;
}

/* Style pour chaque élément de la suggestion */
#stationSuggestions div {
    padding: 12px;
    border-bottom: 1px solid #ddd;
    cursor: pointer;
    font-size: 1rem;
    color: #333;
    transition: background 0.3s ease;
}

#stationSuggestions div:hover {
    background: #28a745;
    color: #fff;
}


    </style>
</head>
<body>
    <!-- Loader -->
    <div id="loader">
        <div class="spinner"></div>
    </div>

    <!-- Contenu principal du site -->
    <div id="content">
        <h1>Données de la station <?php echo htmlspecialchars($stationName); ?></h1>
        <div class="button-container">
    <button class="back-button" onclick="window.location.href='/SAE-3.01-Developpement-application/src/View/home/index.php';">🏠 Accueil</button>
    <button id="darkModeToggle" class="back-button">🌙 Mode sombre</button>
    <button class="back-button" onclick="window.location.href='/SAE-3.01-Developpement-application/web/frontController.php?page=carte';">🗺️ Carte</button>
</div>

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
        <!-- Bouton ajouté pour comparer avec une autre station -->
        <button class="compare-button" id="compareStationBtn">🏢📊 Comparer avec une autre station</button>
        <div id="stationSearchContainer" style="display:none;">
    <input type="text" id="searchStation" placeholder="Rechercher une station..." onkeyup="searchStations(this.value)">
    <div id="stationSuggestions"></div>
</div>


</div>


        <footer>
            SAE - Projet 3.01 - Développement d'application 
        </footer>
    </div>

    <script>
        // Simuler un chargement initial
        window.addEventListener("load", function () {
            // Masquer le loader et afficher le contenu après le chargement initial
            document.getElementById("loader").style.display = "none";
            document.getElementById("content").style.display = "block";
        });

        let charts = {}; 

        document.getElementById('dateForm').onsubmit = function(event) {
            event.preventDefault();
            showLoader();
            fetchData(document.getElementById('date').value, false);
        };

        document.getElementById('compareBtn').onclick = function() {
            const secondDate = prompt("Entrez une autre date pour la comparaison (AAAA-MM-JJ):");
            if (secondDate) {
                showLoader();
                fetchData(secondDate, true);
            }
        };

        document.getElementById('compareStationBtn').onclick = function() {
    let searchContainer = document.getElementById('stationSearchContainer');
    if (searchContainer.style.display === 'none' || searchContainer.style.display === '') {
        searchContainer.style.display = 'block';
    } else {
        searchContainer.style.display = 'none';
    }
};



function searchStations(query) {
    if (query.length === 0) {
        document.getElementById("stationSuggestions").innerHTML = "";
        return;
    }
    const xhr = new XMLHttpRequest();
    xhr.open("GET", "/SAE-3.01-Developpement-application/src/View/map/search.php?query=" + encodeURIComponent(query), true);
    xhr.onload = function() {
        if (this.status === 200) {
            document.getElementById("stationSuggestions").innerHTML = this.responseText;
            document.getElementById("stationSuggestions").style.display = "block";
        }
    };
    xhr.send();
}

document.getElementById("searchStation").onblur = function() {
    setTimeout(() => {
        document.getElementById("stationSuggestions").style.display = "none";
    }, 200);
};


        function selectStation(stationName) {
            document.getElementById("searchStation").value = stationName;
            document.getElementById("stationSuggestions").innerHTML = "";
            showLoader();
            compareStations(stationName);
        }

        function showLoader() {
            document.getElementById("loader").style.display = "flex";
            document.getElementById("content").style.display = "none";
        }

        function hideLoader() {
            document.getElementById("loader").style.display = "none";
            document.getElementById("content").style.display = "block";
        }

        function fetchData(date, isComparison) {
    var stationName = "<?php echo htmlspecialchars($stationName); ?>";

    fetch(`station_data.php?station_name=${encodeURIComponent(stationName)}&date=${date}`)
        .then(response => response.json())
        .then(data => {
            hideLoader();
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
                addComparisonData(hours, sortedData, date);
            } else {
                destroyCharts();
                createChart('temperatureChart', 'Température (°C)', hours, sortedData.map(entry => entry.tc), 'red');
                createChart('humidityChart', 'Humidité (%)', hours, sortedData.map(entry => entry.u), 'blue');
                createChart('windChart', 'Vitesse du vent (m/s)', hours, sortedData.map(entry => entry.ff), 'black');
            }
        })
        .catch(error => {
            hideLoader();
            console.error('Erreur:', error);
        });
}


        function compareStations(secondStation) {
            var date = document.getElementById('date').value;
            var stationName = "<?php echo htmlspecialchars($stationName); ?>";

            fetch(`station_data.php?station_name=${encodeURIComponent(secondStation)}&date=${date}`)
                .then(response => response.json())
                .then(data => {
                    hideLoader();
                    if (data.length === 0) {
                        alert("Aucune donnée trouvée pour cette station à cette date.");
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

                    addComparisonData(hours, sortedData, secondStation);
                })
                .catch(error => {
                    hideLoader();
                    console.error('Erreur:', error);
                });
        }

        function addComparisonData(labels, newData, identifier) {
    addDataToChart('temperatureChart', labels, newData.map(entry => entry.tc), 'purple', `Température - ${identifier}`);
    addDataToChart('humidityChart', labels, newData.map(entry => entry.u), 'orange', `Humidité - ${identifier}`);
    addDataToChart('windChart', labels, newData.map(entry => entry.ff), 'green', `Vent - ${identifier}`);
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
