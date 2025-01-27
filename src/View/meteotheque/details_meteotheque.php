<?php
require_once __DIR__ . '/../../../src/Model/MeteothequeModel.php';
require_once __DIR__ . '/../../../src/Lib/MessageFlash.php';

use App\Meteo\Model\MeteothequeModel;
use App\Meteo\Lib\MessageFlash;

session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: /SAE-3.01-Developpement-application/web/frontController.php?page=login');
    exit;
}

$userId = $_SESSION['user_id'];

if (!isset($_GET['id'])) {
    MessageFlash::ajouter('danger', 'Aucune météothèque spécifiée.');
    header('Location: /SAE-3.01-Developpement-application/web/frontController.php?page=favoris');
    exit;
}

$meteothequeId = intval($_GET['id']);
$meteotheque = MeteothequeModel::getMeteothequeById($meteothequeId);

if (!$meteotheque) {
    MessageFlash::ajouter('danger', 'Météothèque non trouvée.');
    header('Location: /SAE-3.01-Developpement-application/web/frontController.php?page=favoris');
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails de la Météothèque</title>
    <link rel="stylesheet" href="/SAE-3.01-Developpement-application/web/assets/css/styles.css">
    <link rel="stylesheet" href="/SAE-3.01-Developpement-application/web/assets/css/meteotheque.css">
</head>
<body>
    <header>
        <h1>Détails de la Météothèque</h1>
        <button class="back-button" onclick="window.location.href='/SAE-3.01-Developpement-application/web/frontController.php?page=favoris';">🔙 Retour aux favoris</button>
    </header>
    <main>
        <div class="details">
            <h2><?= htmlspecialchars($meteotheque['titre']) ?></h2>
            <p><strong>Description :</strong> <?= htmlspecialchars($meteotheque['description']) ?></p>
            <p><strong>Station :</strong> <?= htmlspecialchars($meteotheque['station_name']) ?></p>
            <p><strong>Date de création :</strong> <?= htmlspecialchars($meteotheque['search_date']) ?></p>
            <a href="/SAE-3.01-Developpement-application/src/View/map/search.php?station_name=<?= urlencode($meteotheque['station_name']) ?>&date=<?= urlencode($meteotheque['search_date']) ?>&redirect=true">🔍 Rechercher cette station</a>
        </div>
    </main>
    <footer>
        <p>&copy; 2025 - Station météo</p>
    </footer>
</body>
</html>
