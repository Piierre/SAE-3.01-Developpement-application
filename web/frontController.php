<?php
// Définir la racine du projet
define('ROOT', dirname(__DIR__));

// Charger les fichiers nécessaires
require_once(ROOT . '/src/config/Conf.php');
require_once(ROOT . '/src/Controller/CarteController.php');
require_once(ROOT . '/src/Controller/StationController.php');

use App\Meteo\Controller\StationController;

$page = $_GET['page'] ?? 'home';

switch ($page) {
    case 'carte':
        require(ROOT . '/src/View/Carte.php');
        break;

    case 'station':
        $stationName = $_GET['name'] ?? null;
        if (!$stationName) {
            echo "Nom de station manquant.";
            exit;
        }
        require(ROOT . '/src/View/station.php');
        break;

    default:
        echo "<h1>Bienvenue sur l'application météo !</h1>";
        echo "<p><a href='?page=carte'>Voir la carte météo</a></p>";
        break;
}
