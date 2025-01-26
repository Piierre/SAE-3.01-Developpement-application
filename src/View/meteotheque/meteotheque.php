<?php
require_once __DIR__ . '/../../../src/Model/MeteothequeModel.php';
require_once __DIR__ . '/../../../src/Lib/MessageFlash.php';

use App\Meteo\Model\MeteothequeModel;
use App\Meteo\Lib\MessageFlash;

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header('Location: /SAE-3.01-Developpement-application/web/frontController.php?page=login');
    exit;
}

$userId = $_SESSION['user_id']; 
$meteotheques = MeteothequeModel::getMeteothequesByUser($userId);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Météothèque</title>
    <link rel="stylesheet" href="/SAE-3.01-Developpement-application/web/assets/css/styles.css">
</head>
<style>
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
    position: absolute;
    top: 20px;
    right: 20px;
}

.back-button:hover {
    background-color: #218838;
    box-shadow: 0 5px 15px rgba(0,0,0,0.3);
}

.meteotheque-item {
    margin-bottom: 15px;
}

.meteotheque-item a {
    display: inline-block;
    margin-right: 15px;
    padding: 8px 15px;
    background-color: #007bff;
    color: white;
    text-decoration: none;
    border-radius: 5px;
    transition: background-color 0.3s;
}

.meteotheque-item a:hover {
    background-color: #0056b3;
}
</style>

<body>
    <header>
        <h1>Météothèque</h1>
        <button class="back-button" onclick="window.location.href='/SAE-3.01-Developpement-application/web/frontController.php';">🏠 Accueil</button>
    </header>
    <main>
        <section>
            <h2>Vos Météothèques</h2>
            <?php MessageFlash::displayFlashMessages(); ?>
            <ul>
    <?php if (!empty($meteotheques)): ?>
        <?php foreach ($meteotheques as $meteotheque): ?>
            <?php if (!empty($meteotheque['station_name']) && !empty($meteotheque['search_date'])): ?>
                <li>
                    <strong><?= htmlspecialchars($meteotheque['titre']) ?></strong> :
                    <?= htmlspecialchars($meteotheque['description']) ?>
                    <br>
                    <a href="/SAE-3.01-Developpement-application/src/View/map/search.php?station_name=<?= urlencode($meteotheque['station_name']) ?>&date=<?= urlencode($meteotheque['search_date']) ?>">🔍 Rechercher cette station</a>
                    <a href="/SAE-3.01-Developpement-application/web/frontController.php?page=station&station_name=<?= urlencode($meteotheque['station_name']) ?>&date=<?= urlencode($meteotheque['search_date']) ?>">📊 Voir les graphiques</a>
                </li>
            <?php else: ?>
                <li>
                    <strong><?= htmlspecialchars($meteotheque['titre']) ?></strong> :
                    <em>Informations de recherche incomplètes.</em>
                </li>
            <?php endif; ?>
        <?php endforeach; ?>
    <?php else: ?>
        <li>Aucune météothèque trouvée.</li>
    <?php endif; ?>
</ul>






        </section>
    </main>
    <footer>
        <p>&copy; 2025 - Station météo</p>
    </footer>
</body>
</html>
