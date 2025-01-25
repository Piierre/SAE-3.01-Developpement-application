<?php
use App\Meteo\Model\FavorisModel;
use App\Meteo\Lib\MessageFlash;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $meteothequeId = $_POST['meteotheque_id'];
    $userId = $_SESSION['id'];

    $result = FavorisModel::addToFavorites($userId, $meteothequeId);
    if ($result) {
        MessageFlash::ajouter('success', 'Ajouté aux favoris !');
    } else {
        MessageFlash::ajouter('danger', 'Erreur lors de l\'ajout aux favoris.');
    }
    header('Location: list_meteotheques.php');
}
