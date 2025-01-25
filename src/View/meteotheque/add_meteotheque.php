<?php
use App\Meteo\Model\MeteothequeModel;
use App\Meteo\Lib\MessageFlash;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre = $_POST['titre'];
    $description = $_POST['description'];
    $userId = $_SESSION['id']; // ID de l'utilisateur connecté

    // Appelle une méthode dans le modèle pour insérer la météothèque
    $result = MeteothequeModel::createMeteotheque($userId, $titre, $description);
    if ($result) {
        // Message flash de succès
        MessageFlash::ajouter('success', 'Météothèque créée avec succès !');
        header('Location: meteotheque.php');
    } else {
        MessageFlash::ajouter('danger', 'Erreur lors de la création de la météothèque.');
    }
}
