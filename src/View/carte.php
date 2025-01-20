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
        #map {
            height: 600px;
            width: 100%;
        }
    </style>
</head>
<body>
    <h1>Carte des Stations</h1>
    <div id="map"></div>

    <script>
        var map = L.map('map').setView([48.8566, 2.3522], 6);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
        }).addTo(map);

        var stations = <?php echo json_encode($stations); ?>;
        stations.forEach(function(station) {
            var marker = L.marker([station.latitude, station.longitude]).addTo(map);
            marker.bindPopup(
    `<b>${station.nom}</b><br>Altitude: ${station.altitude} m<br>
     <a href="/SAE-3.01-Developpement-application/src/View/station.php?name=${encodeURIComponent(station.nom)}">Voir les détails</a>`
);
        });
    </script>
</body>
</html>
