<?php
namespace App\Meteo\Controller;

use App\Meteo\Controller\CarteController;

// Charger les données des stations depuis le contrôleur
$stations = CarteController::getStations();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carte des Stations</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"></script>
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
        }

        h1 {
            margin-top: 20px;
            font-size: 2rem;
            font-weight: bold;
            text-shadow: 2px 2px 5px rgba(0,0,0,0.3);
        }

        #map {
            height: 80vh;
            width: 90%;
            margin: 20px auto;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        }

        footer {
            margin-top: 10px;
            font-size: 0.9rem;
            color: rgba(255, 255, 255, 0.7);
        }

        /* Style des popups Leaflet */
        .leaflet-popup-content {
            font-size: 1rem;
            color: #333;
        }

        /* Bouton retour */
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
        }

    </style>
</head>
<body>
    <h1>Carte des Stations</h1>
    <button class="back-button" onclick="window.location.href='/SAE-3.01-Developpement-application/src/View/home/index.php'">🏠 Accueil</button>
    <button class="back-button" onclick="window.location.href='/SAE-3.01-Developpement-application/src/View/home/index.php';">🏠 Accueil</button>
    <div id="map"></div>
    <footer>
        SAE - Projet 3.01 - Développement d'application
    </footer>

    <script>
        var map = L.map('map').setView([46.8566, 5.3522], 6);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
        }).addTo(map);

        var stations = <?php echo json_encode($stations); ?>;
        stations.forEach(function(station) {
            var marker = L.marker([station.latitude, station.longitude]).addTo(map);
            marker.bindPopup(
                `<b>${station.nom}</b><br>Altitude: ${station.altitude} m<br>
                 <a href="/SAE-3.01-Developpement-application/src/View/station/station.php?name=${encodeURIComponent(station.nom)}">Voir les détails</a>`
            );
        });

        // Ajuster la taille de la carte pour s'adapter à l'écran
        window.addEventListener('resize', function() {
            document.getElementById('map').style.height = window.innerHeight * 0.8 + 'px';
        });
    </script>
</body>
</html>
