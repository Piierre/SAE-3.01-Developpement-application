<?php
require_once __DIR__ . '/../../../src/Lib/auth.php';
require_once __DIR__ . '/../../../src/Model/MeteothequeModel.php';

use App\Meteo\Lib\Auth;
use App\Meteo\Model\MeteothequeModel;

Auth::requireAuth();

$meteotheques = MeteothequeModel::getAllMeteotheques();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Météothèques</title>
    <link rel="stylesheet" href="/SAE-3.01-Developpement-application/web/assets/css/styles.css">
</head>
<body>
    <header>
        <h1>Liste des Météothèques</h1>
    </header>
    <main>
        <section>
            <h2>Toutes les météothèques</h2>
            <ul>
                <?php if (!empty($meteotheques)): ?>
                    <?php foreach ($meteotheques as $meteotheque): ?>
                        <li>
                            <a href="details_meteotheque.php?id=<?= $meteotheque['id'] ?>">
                                <?= htmlspecialchars($meteotheque['titre']) ?>
                            </a>
                            <p><?= htmlspecialchars($meteotheque['description']) ?></p>
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

