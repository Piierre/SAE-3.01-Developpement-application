<?php
// Télécharger le fichier GeoJSON
$geoJsonData = file_get_contents('https://france-geojson.gregoiredavid.fr/repo/regions.geojson');

// Assurez-vous que le fichier a été correctement téléchargé
if ($geoJsonData === false) {
    die('Impossible de télécharger le fichier GeoJSON');
}

// Décoder les données GeoJSON
$geoJson = json_decode($geoJsonData, true);

// Vérifier si le décodage a réussi
if (json_last_error() !== JSON_ERROR_NONE) {
    die('Erreur lors du décodage du JSON : ' . json_last_error_msg());
}

// Passer les données GeoJSON à JavaScript pour l'affichage avec Leaflet
$geoJsonForJs = json_encode($geoJson);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Carte des régions françaises</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <style>
        #map { height: 600px; }
    </style>
</head>
<body>
    <div id="map"></div>
    <script>
        var map = L.map('map').setView([46.603354, 1.888334], 6);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        var geoJsonData = <?php echo $geoJsonForJs; ?>;
        L.geoJSON(geoJsonData, {
            style: function(feature) {
                return {
                    fillColor: 'blue',
                    weight: 2,
                    opacity: 1,
                    color: 'white',
                    fillOpacity: 0.7
                };
            }
        }).addTo(map);
    </script>
</body>
</html>
