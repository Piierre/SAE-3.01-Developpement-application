<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carte Thermique des Températures</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <link rel="stylesheet" href="/SAE-3.01-Developpement-application/web/assets/css/styles.css">
    <link rel="stylesheet" href="/SAE-3.01-Developpement-application/web/assets/css/auth.css">
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet.heat/dist/leaflet-heat.js"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body, html {
            height: 100%;
            font-family: Arial, sans-serif;
            background: linear-gradient(to right, #a8acaf, #00f2fe);
            color: #fff;
            text-align: center;
            overflow: auto; /* Ensure the body is scrollable in both directions */
        }

        h1 {
            margin-top: 20px;
            font-size: 2.5rem; /* Increased font size */
            font-weight: bold;
            text-shadow: 2px 2px 5px rgba(0,0,0,0.3);
        }

        #map {
            height: 80vh;
            width: 90%;
            margin: 20px auto;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
            position: relative;
            z-index: 1; /* Ensure these elements are above the particles */
        }

        footer {
            margin-top: 10px;
            font-size: 0.9rem;
            color: rgba(255, 255, 255, 0.7);
        }

        .temperature-label {
            font-size: 12px;
            font-weight: bold;
            color: white;
            background-color: rgba(0, 0, 0, 0.5);
            padding: 2px 5px;
            border-radius: 3px;
        }

        .button-container {
            position: absolute;
            top: 20px;
            right: 20px;
            display: flex;
            gap: 10px;
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
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
            position: relative;
            z-index: 1; /* Ensure these elements are above the particles */
        }

        .back-button:hover {
            background-color: #218838;
        }

        #particles-js {
            position: fixed; /* Ensure it covers the entire background */
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 0; /* Adjust z-index to be just behind the map and texts */
        }

        #dateForm {
            margin: 20px 0;
            font-size: 1.2em; /* Decreased font size */
            position: relative;
            z-index: 1; /* Ensure these elements are above the particles */
        }
        #dateForm label, #dateForm input, #dateForm button {
            font-size: 1.2em; /* Decreased font size */
            margin: 5px;
        }

        body.dark-mode, html.dark-mode {
            background: linear-gradient(to right, #2c3e50, #4ca1af);
            color: #ddd;
        }

        body.dark-mode h1, body.dark-mode footer {
            color: #ddd;
        }

        body.dark-mode .temperature-label {
            color: #ddd;
            background-color: rgba(0, 0, 0, 0.7);
        }

        body.dark-mode .back-button {
            background-color: #444;
            color: #ddd;
        }

        body.dark-mode .back-button:hover {
            background-color: #555;
        }

        body.dark-mode #dateForm label, body.dark-mode #dateForm input, body.dark-mode #dateForm button {
            color: #ddd;
            background-color: #444;
            border: 1px solid #555;
        }

    </style>
</head>
<body>
    <div id="particles-js"></div>
    <header>
        <h1>Carte Thermique des Températures en France</h1>
        <div class="button-home">
            <button class="btn" onclick="window.location.href='/SAE-3.01-Developpement-application/web/frontController.php'">🏠 Accueil</button>
        </div>
        <div class="button-container">
            <button class="btn" id="darkModeButton" onclick="toggleDarkMode()">🌙 Mode sombre</button>
        </div>
    </header>
    <main>
        <form id="dateForm">
            <label for="date">Sélectionner une date :</label>
            <input type="date" id="date" required>
            <button type="submit">Afficher</button>
        </form>
        <div id="map"></div>
    </main>
    <footer>
        SAE - Projet 3.01 - Développement d'application
    </footer>

    <script src="https://cdn.jsdelivr.net/particles.js/2.0.0/particles.min.js"></script>
    <script>
        particlesJS("particles-js", {
            "particles": {
                "number": {
                    "value": 120, // Increased number of particles
                    "density": { "enable": true, "value_area": 800 }
                },
                "color": { "value": "#ffffff" },
                "shape": { "type": "circle" },
                "opacity": { "value": 0.7, "random": true }, // Increased opacity
                "size": { "value": 4, "random": true }, // Increased size
                "line_linked": { "enable": true, "distance": 100, "color": "#ffffff", "opacity": 0.6, "width": 2 }, // Increased contrast
                "move": { "enable": true, "speed": 4 } // Increased speed
            },
            "interactivity": {
                "events": {
                    "onhover": { "enable": true, "mode": "repulse" }
                }
            }
        });

        function toggleDarkMode() {
            document.body.classList.toggle('dark-mode');
            document.documentElement.classList.toggle('dark-mode');
            const darkModeButton = document.getElementById('darkModeButton');
            if (document.body.classList.contains('dark-mode')) {
                darkModeButton.innerHTML = '☀️ Mode clair';
            } else {
                darkModeButton.innerHTML = '🌙 Mode sombre';
            }
        }

        var map = L.map('map').setView([46.603354, 1.888334], 6);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        // Fonction pour récupérer les données météo
        document.getElementById('dateForm').addEventListener('submit', function(event) {
            event.preventDefault();
            let selectedDate = document.getElementById('date').value;
            if (!selectedDate) {
                alert("Veuillez sélectionner une date.");
                return;
            }

            fetch(`/SAE-3.01-Developpement-application/src/View/map/get_meteo_data.php?date=${encodeURIComponent(selectedDate)}`)
                .then(response => response.json())
                .then(data => {
                    if (!data || data.length === 0) {
                        alert("Aucune donnée disponible pour cette date.");
                        return;
                    }

                    let minTemp = Math.min(...data.map(station => station.temp));
                    let maxTemp = Math.max(...data.map(station => station.temp));

                    // Amplification artificielle des valeurs de température pour la coloration
                    let amplifiedHeatData = data.map(station => [
                        station.lat,
                        station.lon,
                        station.temp + (15 - station.temp) * 0.5  // Exagération contrôlée des températures
                    ]);

                    // Ajouter la carte thermique avec des températures amplifiées
                    updateHeatMap(amplifiedHeatData);

                    // Ajouter les vraies valeurs de température dans les tooltips
                    data.forEach(point => {
                        L.tooltip({
                            permanent: true,
                            direction: 'right',
                            className: 'temperature-label'
                        })
                        .setLatLng([point.lat, point.lon])
                        .setContent(`${point.temp.toFixed(1)} °C`)  // Affichage de la température réelle
                        .addTo(map);
                    });
                })
                .catch(error => {
                    console.error("Erreur lors du chargement des données :", error);
                    alert("Une erreur est survenue, veuillez réessayer.");
                });

            function updateHeatMap(heatData) {
                if (window.heatLayer) {
                    map.removeLayer(window.heatLayer);
                }

                window.heatLayer = L.heatLayer(heatData, {
                    radius: 32,   // Taille des points de chaleur
                    blur: 60,     // Flou pour rendre les transitions plus douces
                    maxZoom: 10,
                    max: 1,       // Augmenter pour un effet plus prononcé
                    gradient: {
                        0.0: 'blue',        // Froid extrême
                        0.3: 'cyan',        // Températures fraîches
                        0.49: 'yellowgreen',// Début du jaune clair
                        0.5: 'orange',      // Point central chaud
                        0.51: 'gold',       // Transition douce après l'orange
                        0.55: 'darkorange', // Passage vers le rouge
                        0.6: 'orangered',   // Rouges légers
                        0.75: 'red',        // Très chaud
                        1.0: 'darkred'      // Extrêmement chaud
                    }
                }).addTo(map);
            }
        });
    </script>
</body>
</html>