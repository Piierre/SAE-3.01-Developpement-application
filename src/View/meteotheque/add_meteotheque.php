<?php
require_once __DIR__ . '/../../../src/Model/MeteothequeModel.php';
require_once __DIR__ . '/../../../src/Lib/MessageFlash.php';

use App\Meteo\Model\MeteothequeModel;
use App\Meteo\Lib\MessageFlash;

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Vérifie si l'utilisateur est connecté
if (!isset($_SESSION['id'])) {
    MessageFlash::ajouter('danger', 'Vous devez être connecté pour ajouter une météothèque.');
    header('Location: /SAE-3.01-Developpement-application/web/frontController.php?page=login');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre = isset($_POST['titre']) ? trim($_POST['titre']) : null;
    $description = isset($_POST['description']) ? trim($_POST['description']) : null;
    $stationName = isset($_POST['station_name']) ? trim($_POST['station_name']) : null;
    $searchDate = isset($_POST['search_date']) ? trim($_POST['search_date']) : null;
    $redirect = isset($_POST['redirect']) ? true : false;

    // Vérifie si les champs station et date sont remplis
    if (empty($stationName) || empty($searchDate)) {
        MessageFlash::ajouter('danger', 'Veuillez spécifier une station et une date valides.');
        header('Location: /SAE-3.01-Developpement-application/web/frontController.php?page=recherche');
        exit;
    }

    $userId = $_SESSION['id'];

    // Insérer les données dans la base
    $result = MeteothequeModel::createMeteotheque($userId, $titre, $description, $stationName, $searchDate);

    if ($result) {
        MessageFlash::ajouter('success', 'Météothèque ajoutée avec succès !');

        // Redirection vers la page des graphiques si demandé
        if ($redirect) {
            header("Location: /SAE-3.01-Developpement-application/web/frontController.php?page=recherche&station_name=" . urlencode($stationName) . "&date=" . urlencode($searchDate));
            exit;
        }
    } else {
        MessageFlash::ajouter('danger', 'Erreur lors de l\'ajout à la météothèque.');
    }

    header('Location: /SAE-3.01-Developpement-application/web/frontController.php?page=meteotheque');
    exit;
}
?>