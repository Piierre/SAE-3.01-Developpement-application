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
    <link rel="stylesheet" href="/SAE-3.01-Developpement-application/web/assets/css/styles.css"> <!-- Lien vers le CSS -->
    <link rel="stylesheet" href="/SAE-3.01-Developpement-application/web/assets/css/auth.css"> <!-- Lien vers le CSS -->
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
            overflow: auto; /* Ensure the body is scrollable in both directions */
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
            position: relative;
            z-index: 1; /* Ensure these elements are above the particles */
        }

        footer {
            margin-top: 10px;
            font-size: 0.9rem;
            color: rgba(255, 255, 255, 0.7);
            position: relative;
            z-index: 1; /* Ensure these elements are above the particles */
        }

        /* Style des popups Leaflet */
        .leaflet-popup-content {
            font-size: 1rem;
            color: #333;
        }

        /* Bouton retour */
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

        body.dark-mode, html.dark-mode {
            background: linear-gradient(to right, #2c3e50, #4ca1af);
            color: #ddd;
        }

        body.dark-mode h1, body.dark-mode footer {
            color: #ddd;
        }

        body.dark-mode .leaflet-popup-content {
            color: #ddd;
        }

        body.dark-mode .back-button {
            background-color: #444;
            color: #ddd;
        }

        body.dark-mode .back-button:hover {
            background-color: #555;
        }
    </style>
</head>
<body>
    <div id="particles-js"></div>
    <header>
        <h1>Carte des Stations</h1>
        <div class="button-home">
            <button class="btn" onclick="window.location.href='/SAE-3.01-Developpement-application/web/frontController.php'">🏠 Accueil</button>
        </div>
        <div class="button-container">
            <button class="btn" id="darkModeButton" onclick="toggleDarkMode()">🌙 Mode sombre</button>
        </div>
    </header>
    <main>
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

        var map = L.map('map').setView([46.8566, 5.3522], 6);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
        }).addTo(map);

        var stations = <?php echo json_encode($stations); ?>;

        function getWeather(lat, lon, callback) {
            var apiKey = '75f79aeac04ab3de89f8045bc648d492';
            var url = `https://api.openweathermap.org/data/2.5/weather?lat=${lat}&lon=${lon}&appid=${apiKey}&units=metric`;

            fetch(url)
                .then(response => response.json())
                .then(data => callback(data))
                .catch(error => console.log("Erreur météo :", error));
        }

        function translateWeather(description) {
            if (description.includes("clear")) return "Ciel dégagé";
            if (description.includes("clouds")) return "Nuageux";
            if (description.includes("rain")) return "Pluie";
            if (description.includes("snow")) return "Neige";
            if (description.includes("thunderstorm")) return "Orage";
            if (description.includes("mist") || description.includes("fog")) return "Brume";
            return description;
        }

        function getWeatherEmoji(description) {
            if (description.includes("clear")) return "☀️";
            if (description.includes("clouds")) return "☁️";
            if (description.includes("rain")) return "🌧️";
            if (description.includes("snow")) return "❄️";
            if (description.includes("thunderstorm")) return "⛈️";
            if (description.includes("mist") || description.includes("fog")) return "🌫️";
            return "";
        }

        stations.forEach(function(station) {
            getWeather(station.latitude, station.longitude, function(weatherData) {
                var temperature = Math.floor(weatherData.main.temp);
                var weatherDescription = translateWeather(weatherData.weather[0].description);
                var weatherInfo = `Température: ${temperature}°C<br>Conditions: ${weatherDescription}`;
                var weatherEmoji = getWeatherEmoji(weatherData.weather[0].description);
                var marker = L.marker([station.latitude, station.longitude]).addTo(map);
                marker.bindPopup(
                    `<b>${station.nom}</b><br>Altitude: ${station.altitude} m<br>${weatherInfo} ${weatherEmoji}<br>
                     <a href="/SAE-3.01-Developpement-application/web/frontController.php?page=station&name=${encodeURIComponent(station.nom)}">Voir les détails</a>`
                );
            });
        });

        // Ajuster la taille de la carte pour s'adapter à l'écran
        window.addEventListener('resize', function() {
            document.getElementById('map').style.height = window.innerHeight * 0.8 + 'px';
        });
    </script>
</body>
</html>
