<?php
namespace App\Meteo\Controller;

use App\Meteo\Controller\CarteController;

// Charger les données des stations
$stations = CarteController::getStations();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MeteoVision - Informations Régionales</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(to right, #a8acaf, #00f2fe);
            color: #333;
        }

        header, footer {
            text-align: center;
            padding: 20px;
            background: #004080;
            color: white;
        }

        h1 {
            margin: 0;
        }

        .main-container {
            padding: 20px;
        }

        .search-container {
            text-align: center;
            margin: 20px 0;
        }

        .search-container input {
            padding: 10px;
            width: 300px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .search-container button {
            padding: 10px 20px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .search-container button:hover {
            background-color: #218838;
        }

        .region {
            margin: 20px 0;
            padding: 10px;
            background: #f0f0f0;
            border-radius: 5px;
            box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);
        }

        .region h2 {
            margin: 0 0 10px;
        }

        .stations-list {
            list-style-type: none;
            padding: 0;
        }

        .stations-list li {
            margin: 5px 0;
            padding: 10px;
            background: white;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .stations-list li span {
            display: block;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>
    <header>
        <h1>Bienvenue sur MeteoVision</h1>
    </header>

    <main class="main-container">
        <div class="search-container">
            <input type="text" id="regionInput" placeholder="Rechercher une région ou une station">
            <button id="searchButton">Rechercher</button>
        </div>

        <div id="regionsContainer">
            <?php foreach ($stations as $region => $stationList): ?>
                <div class="region" data-region="<?= htmlspecialchars($region) ?>">
                    <h2><?= htmlspecialchars($region) ?></h2>
                    <ul class="stations-list">
                        <?php foreach ($stationList as $station): ?>
                            <li>
                                <strong><?= htmlspecialchars($station['ville']) ?></strong>
                                <span>Température : <?= htmlspecialchars($station['temp']) ?>°C</span>
                                <span>Humidité : <?= htmlspecialchars($station['humidity']) ?>%</span>
                                <span>Vent : <?= htmlspecialchars($station['windSpeed']) ?> km/h</span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endforeach; ?>
        </div>
    </main>

    <footer>
        SAE 3.01 - Projet de Développement d'Application
    </footer>

    <script>
        document.getElementById('searchButton').addEventListener('click', function () {
            const searchInput = document.getElementById('regionInput').value.trim().toLowerCase();
            const regions = document.querySelectorAll('.region');

            regions.forEach(region => {
                const regionName = region.getAttribute('data-region').toLowerCase();
                region.style.display = regionName.includes(searchInput) ? 'block' : 'none';
            });
        });
    </script>
</body>
</html>
