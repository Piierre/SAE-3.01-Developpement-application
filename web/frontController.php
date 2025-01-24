<?php
// Définir la racine du projet
define('ROOT', dirname(__DIR__));

// Chargement de l'autoloader (si utilisé)
require_once(ROOT . '/src/Lib/Psr4AutoloaderClass.php');

// Charger les fichiers nécessaires
require_once(ROOT . '/src/config/Conf.php');
require_once(ROOT . '/src/Controller/CarteController.php');
require_once(ROOT . '/src/Controller/StationController.php');
require_once(ROOT . '/src/Controller/SearchController.php');
require_once(ROOT . '/src/View/station/station_data.php');

use App\Meteo\Controller\SearchController;

// Gestion des actions AJAX
if (isset($_GET['action']) && $_GET['action'] === 'search' && isset($_GET['query'])) {
    $query = $_GET['query'];
    $results = SearchController::searchStation($query);

    header('Content-Type: application/json');
    echo json_encode($results);
    exit;
}

// Router simple
$page = $_GET['page'] ?? 'home';

switch ($page) {
    case 'carte_regions':
        require(ROOT . '/src/View/map/carte_regions.php');
        break;    
    case 'carte':
        require(ROOT . '/src/View/map/carte.php');
        break;
    case 'station':
        require(ROOT . '/src/View/station/station.php');
        break;
    case 'recherche':
        require(ROOT . '/src/View/home/recherche.php');
        break;
    case 'login':
        require(ROOT . '/src/View/auth/login.php');
        break;
    case 'register':
        require(ROOT . '/src/View/auth/register.php');
        break;
    case 'logout':
        // Détruire la session pour déconnecter l'utilisateur
        session_start();
        session_unset();
        session_destroy();
        header("Location: /SAE-3.01-Developpement-application/web/frontController.php");
        exit();
    case 'dashboard':
        require(ROOT . '/src/View/dashboard/dashboard.php');
        break;
    case 'manage_users':
        require(ROOT . '/src/View/admin/manage_users.php');
        break;
    case 'meteotheque':
        require(ROOT . '/src/View/meteotheque/meteotheque.php');
        break;
    default:
        require(ROOT . '/src/View/home/index.php');
        break;
}
?>