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
require_once(ROOT . '/src/Controller/DashboardController.php');
require_once(ROOT . '/src/Controller/MeteothequeController.php');
require_once(ROOT . '/src/Controller/FeedbackController.php'); 
require_once(ROOT . '/src/Model/FeedbackModel.php'); 
require_once(ROOT . '/src/View/station/station_data.php');

use App\Meteo\Controller\SearchController;
use App\Meteo\Controller\MeteothequeController;
use App\Meteo\Controller\FeedbackController;

// Gestion des actions AJAX
if (isset($_GET['action']) && $_GET['action'] === 'search' && isset($_GET['query'])) {
    $query = $_GET['query'];
    $results = SearchController::searchStation($query);

    header('Content-Type: application/json');
    echo json_encode($results);
    exit;
}

// Ajout d'une météothèque
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['action']) && $_GET['action'] === 'addMeteotheque') {
    session_start();
    $userId = $_SESSION['user_id']; // Assurez-vous que l'utilisateur est connecté
    $titre = $_POST['titre'];
    $description = $_POST['description'];
    $stationName = $_POST['station_name'];
    $searchDate = $_POST['search_date'];

    $meteothequeController = new MeteothequeController();
    $meteothequeController->addMeteotheque($userId, $titre, $description, $stationName, $searchDate);

    header('Location: /SAE-3.01-Developpement-application/web/frontController.php?page=meteotheque');
    exit;
}

// Suppression d'une météothèque
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['action']) && $_GET['action'] === 'deleteMeteotheque') {
    $meteothequeId = $_POST['meteotheque_id'];

    $meteothequeController = new MeteothequeController();
    $meteothequeController->deleteMeteotheque($meteothequeId);

    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit;
}

// Gestion des soumissions de feedback
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['action']) && $_GET['action'] === 'submitFeedback') {
    $username = $_POST['username'] ?? 'Anonyme'; // Nom de l'utilisateur (ou Anonyme si non connecté)
    $message = $_POST['message'] ?? '';          // Message du feedback
    $rating = (int)($_POST['rating'] ?? 0);      // Note (par exemple, sur 5)

    if (!empty($message)) {
        $feedbackController = new FeedbackController();
        $feedbackController->submitFeedback($username, $message, $rating);

        header('Location: /SAE-3.01-Developpement-application/web/frontController.php?page=feedback&success=1');
    } else {
        header('Location: /SAE-3.01-Developpement-application/web/frontController.php?page=feedback&error=1');
    }
    exit;
}

// Gestion de l'approbation des feedbacks
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['action']) && $_GET['action'] === 'approveFeedback') {
    $feedbackId = intval($_POST['feedback_id']);

    $feedbackController = new FeedbackController();
    $feedbackController->approveFeedback($feedbackId);

    header('Location: /SAE-3.01-Developpement-application/web/frontController.php?page=list_feedback');
    exit();
}

// Gestion de la désapprobation des feedbacks
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['action']) && $_GET['action'] === 'disapproveFeedback') {
    $feedbackId = intval($_POST['feedback_id']);

    $feedbackController = new FeedbackController();
    $feedbackController->disapproveFeedback($feedbackId);

    header('Location: /SAE-3.01-Developpement-application/web/frontController.php?page=list_feedback');
    exit;
}

// Router simple
$page = $_GET['page'] ?? 'home';

switch ($page) {
    case 'favoris':
        require(ROOT . '/src/View/meteotheque/favoris.php');
        break;
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
    case 'carte_thermique':
        require(ROOT . '/src/View/map/carte_thermique.php');
        break;
    case 'all_meteotheques':
        $meteothequeController = new MeteothequeController();
        $meteothequeController->listAllMeteotheques();
        break;
    case 'details_meteotheque':
        require(ROOT . '/src/View/meteotheque/details_meteotheque.php');
        break;
    case 'feedback': // Nouvelle page pour le feedback
        require(ROOT . '/src/View/feedback/feedback_form.php');
        break;
    case 'list_feedback':
        require(ROOT . '/src/View/feedback/feedback_list.php');
        break;
    /**/ 
    default:
        require(ROOT . '/src/View/home/index.php');
        break;
}
?>