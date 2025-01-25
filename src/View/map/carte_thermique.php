<!DOCTYPE html>
    <html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Carte Thermique des Températures</title>
        <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
        <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
        <script src="https://unpkg.com/leaflet.heat/dist/leaflet-heat.js"></script>
        <script src="https://unpkg.com/leaflet.markercluster/dist/leaflet.markercluster.js"></script>
        <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster/dist/MarkerCluster.css" />
        <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster/dist/MarkerCluster.Default.css" />
        <style>
            body {
                font-family: Arial, sans-serif;
                background: linear-gradient(to right, #6a11cb, #2575fc);
                color: white;
                text-align: center;
                margin: 0;
                padding: 0;
            }
            h1 {
                margin: 20px 0;
                font-size: 2.5em;
            }
            #dateForm {
                margin: 20px 0;
            }
            #dateForm label, #dateForm input, #dateForm button {
                font-size: 1.2em;
                margin: 5px;
            }
            #map {
                height: 800px; /* Increased height */
                width: 100%;
                border: 2px solid white;
                border-radius: 10px;
                box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
            }
            .temperature-label {
                font-size: 12px;
                font-weight: bold;
                color: white;
                background-color: rgba(0, 0, 0, 0.5);
                padding: 2px 5px;
                border-radius: 3px;
            }
        </style>
    </head>
    <body>
        <h1>Carte Thermique des Températures en France</h1>
        <form id="dateForm">
            <label for="date">Sélectionner une date :</label>
            <input type="date" id="date" required>
            <button type="submit">Afficher</button>
        </form>
        <div id="map"></div>

        <script>
            var map = L.map('map').setView([46.603354, 1.888334], 6);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(map);

            var markers = L.markerClusterGroup();

            // Fonction pour récupérer les données météo
            document.getElementById('dateForm').addEventListener('submit', function(event) {
                event.preventDefault();
                let selectedDate = document.getElementById('date').value;
                if (!selectedDate) {
                    alert("Veuillez sélectionner une date.");
                    return;
                }

                fetch(`get_meteo_data.php?date=${encodeURIComponent(selectedDate)}`)
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
                var marker = L.marker([point.lat, point.lon])
                    .bindTooltip(`${point.temp.toFixed(1)} °C`, { permanent: true, direction: 'right', className: 'temperature-label' });
                markers.addLayer(marker);
            });

            map.addLayer(markers);
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