<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carte Thermique des Températures</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet.heat/dist/leaflet-heat.js"></script>
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
                    if (data.error) {
                        alert(data.error);
                        return;
                    }

                    // Supprimer les couches existantes
                    if (window.heatLayer) {
                        map.removeLayer(window.heatLayer);
                    }

                    let heatData = data.map(station => [
                        station.lat,
                        station.lon,
                        station.temp
                    ]);

                    window.heatLayer = L.heatLayer(heatData, {
                        radius: 40,
                        blur: 55,
                        maxZoom: 10,
                        gradient: {
                            0.2: 'blue',
                            0.4: 'cyan',
                            0.6: 'yellow',
                            0.8: 'orange',
                            1.0: 'red'
                        }
                    }).addTo(map);

                    data.forEach(point => {
                        L.tooltip({
                            permanent: true,
                            direction: 'right',
                            className: 'temperature-label'
                        })
                        .setLatLng([point.lat, point.lon])
                        .setContent(`${point.temp.toFixed(1)} °C`)
                        .addTo(map);
                    });
                })
                .catch(error => {
                    console.error("Erreur lors du chargement des données :", error);
                    alert("Une erreur est survenue, veuillez réessayer.");
                });
        });
    </script>
</body>
</html>
