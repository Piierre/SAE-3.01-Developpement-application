<?php
require_once __DIR__ . '/../../../src/Lib/auth.php';
require_once __DIR__ . '/../../../src/Model/FavorisModel.php';

use App\Meteo\Lib\Auth;
use App\Meteo\Model\FavorisModel;

Auth::requireAuth();

if (isset($_SESSION['user'])) {
    $user = $_SESSION['user'];
    $userId = $user['id'];
} else {
    $userId = null;
}

$favoris = FavorisModel::getFavorisByUser($userId);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Favoris</title>
    <link rel="stylesheet" href="/SAE-3.01-Developpement-application/web/assets/css/styles.css">
</head>
<body>
    <header>
        <h1>Favoris</h1>
    </header>
    <main>
        <section>
            <h2>Vos météothèques en favoris</h2>
            <ul>
                <?php foreach ($favoris as $favori): ?>
                    <li>
                        <a href="details_meteotheque.php?id=<?= $favori['meteotheque_id'] ?>">
                            <?= htmlspecialchars($favori['titre']) ?>
                        </a>
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
