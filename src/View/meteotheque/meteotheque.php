<?php
require_once __DIR__ . '/../../../src/Model/MeteothequeModel.php';
require_once __DIR__ . '/../../../src/Model/FavorisModel.php';
require_once __DIR__ . '/../../../src/Lib/MessageFlash.php';

use App\Meteo\Model\MeteothequeModel;
use App\Meteo\Model\FavorisModel;
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

/**
 * Fonction pour générer une couleur hexadécimale aléatoire.
 *
 * @return string Couleur hexadécimale aléatoire.
 */
function genererCouleurAleatoire() {
    return sprintf('#%06X', mt_rand(0, 0xFFFFFF));
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Météothèque</title>
    <link rel="stylesheet" href="/SAE-3.01-Developpement-application/web/assets/css/styles.css">
    <link rel="stylesheet" href="/SAE-3.01-Developpement-application/web/assets/css/meteotheque.css">
</head>
<body>
    <header>
        <h1>Météothèque</h1>
        <?php if (isset($_SESSION['login'])): ?>
        <div class="dropdown">
            <button class="dropbtn">Météothèque</button>
            <div class="dropdown-content">
                <a href="/SAE-3.01-Developpement-application/web/frontController.php?page=all_meteotheques">Général</a>
                <a href="/SAE-3.01-Developpement-application/web/frontController.php?page=favoris">Favoris</a>
            </div>
        </div>
        <?php endif; ?>
        <button class="back-button" onclick="window.location.href='/SAE-3.01-Developpement-application/web/frontController.php';">🏠 Accueil</button>
    </header>
    <main>
        <section>
            <h2>Vos Météothèques</h2>
            <?php MessageFlash::displayFlashMessages(); ?>
            <div class="grid">
                <?php if (!empty($meteotheques)): ?>
                    <?php foreach ($meteotheques as $meteotheque): ?>
                        <?php $couleur = genererCouleurAleatoire(); ?>
                        <div class="cube" style="background-color: <?= $couleur ?>;">
                            <strong>Météothèque : <?= htmlspecialchars($meteotheque['titre']) ?></strong>
                            <p>Date de recherche : <?= htmlspecialchars($meteotheque['search_date']) ?></p>
                            <?php if (!empty($meteotheque['station_name']) && !empty($meteotheque['search_date'])): ?>
                                <a href="/SAE-3.01-Developpement-application/src/View/map/search.php?station_name=<?= urlencode($meteotheque['station_name']) ?>&date=<?= urlencode($meteotheque['search_date']) ?>&redirect=true">🔍 Rechercher cette station</a>
                            <?php else: ?>
                                <em>Informations de recherche incomplètes.</em>
                            <?php endif; ?>
                            <!-- Formulaire d'ajout ou de suppression des favoris -->
                            <?php if (FavorisModel::isFavori($userId, $meteotheque['id'])): ?>
                                <form method="post" action="/SAE-3.01-Developpement-application/src/View/meteotheque/remove_from_favorites.php">
                                    <input type="hidden" name="meteotheque_id" value="<?= htmlspecialchars($meteotheque['id']) ?>">
                                    <button type="submit" class="remove-button">❌ Supprimer des favoris</button>
                                </form>
                            <?php else: ?>
                                <form method="post" action="/SAE-3.01-Developpement-application/src/View/meteotheque/add_to_favorites.php">
                                    <input type="hidden" name="meteotheque_id" value="<?= htmlspecialchars($meteotheque['id']) ?>">
                                    <button type="submit" class="favoris-button">⭐ Ajouter aux favoris</button>
                                </form>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>Aucune météothèque trouvée.</p>
                <?php endif; ?>
            </div>
        </section>
    </main>
    <footer>
        <p>&copy; 2025 - Station météo</p>
    </footer>
</body>
</html>