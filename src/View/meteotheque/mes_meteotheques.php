<?php
require_once __DIR__ . '/../../../src/Lib/auth.php';
require_once __DIR__ . '/../../../src/Model/MeteothequeModel.php';

use App\Meteo\Lib\Auth;
use App\Meteo\Model\MeteothequeModel;

Auth::requireAuth();

if (isset($_SESSION['user'])) {
    $user = $_SESSION['user'];
    $userId = $user['id'];
} else {
    $userId = null;
}

// Récupère les météothèques de l'utilisateur connecté
$mesMeteotheques = MeteothequeModel::getMeteothequesByUser($userId);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes Météothèques</title>
    <link rel="stylesheet" href="/SAE-3.01-Developpement-application/web/assets/css/styles.css">
</head>
<body>
    <header>
        <h1>Mes Météothèques</h1>
    </header>
    <main>
        <section>
            <h2>Vos météothèques</h2>
            <ul>
                <?php foreach ($mesMeteotheques as $meteotheque): ?>
                    <li>
                        <a href="details_meteotheque.php?id=<?= $meteotheque['id'] ?>">
                            <?= htmlspecialchars($meteotheque['titre']) ?>
                        </a>
                        <p><?= htmlspecialchars($meteotheque['description']) ?></p>
                    </li>
                <?php endforeach; ?>
            </ul>
        </section>
    </main>
    <footer>
        <p>&copy; 2025 - Station météo</p>
    </footer>
</body>
</html>
