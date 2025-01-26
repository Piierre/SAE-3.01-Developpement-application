<?php
require_once __DIR__ . '/../../../src/Model/FavorisModel.php';
require_once __DIR__ . '/../../../src/Lib/MessageFlash.php';

use App\Meteo\Model\FavorisModel;
use App\Meteo\Lib\MessageFlash;

session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: /SAE-3.01-Developpement-application/web/frontController.php?page=login');
    exit;
}

$userId = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['meteotheque_id'])) {
    // Assurer que l'ID de la météothèque est un entier
    $meteothequeId = intval($_POST['meteotheque_id']);

    // Ajouter aux favoris via le modèle
    if (FavorisModel::addToFavorites($userId, $meteothequeId)) {
        // Ajouter un message de succès
        MessageFlash::ajouter('success', 'La météothèque a été ajoutée à vos favoris.');
    } else {
        // Ajouter un message d'erreur
        MessageFlash::ajouter('error', 'Erreur lors de l\'ajout aux favoris.');
    }

    // Rediriger vers la page précédente après l'ajout
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit;
}
?>
