<?php
require_once __DIR__ . '/../../../src/Model/MeteothequeModel.php';
require_once __DIR__ . '/../../../src/Lib/MessageFlash.php';

use App\Meteo\Model\MeteothequeModel;
use App\Meteo\Lib\MessageFlash;

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    // Rediriger vers la page de connexion si l'utilisateur n'est pas connecté
    header('Location: /SAE-3.01-Developpement-application/web/frontController.php?page=login');
    exit;
}

$userId = $_SESSION['user_id']; // ID de l'utilisateur connecté

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
<body>
    <header>
        <h1>Météothèque</h1>
    </header>
    <main>
        <section>
            <h2>Vos Météothèques</h2>
            <?php MessageFlash::displayFlashMessages(); ?>
            <ul>
                <?php if (!empty($meteotheques)): ?>
                    <?php foreach ($meteotheques as $meteotheque): ?>
                        <li>
                            <strong><?= htmlspecialchars($meteotheque['titre']) ?></strong> :
                            <?= htmlspecialchars($meteotheque['description']) ?>
                            <br>
                            <a href="/SAE-3.01-Developpement-application/web/frontController.php?page=recherche&station_name=<?= urlencode($meteotheque['station_name']) ?>&date=<?= urlencode($meteotheque['search_date']) ?>">Rechercher cette station</a>
                        </li>
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