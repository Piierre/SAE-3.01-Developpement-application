<?php
require_once __DIR__ . '/../../../src/Model/MeteothequeModel.php';
$meteotheques = \App\Meteo\Model\MeteothequeModel::getAllMeteotheques();
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
            <h2>Liste des météothèques</h2>
            <ul>
                <?php foreach ($meteotheques as $meteotheque): ?>
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
