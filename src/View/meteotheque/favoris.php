<?php
require_once __DIR__ . '/../../../src/Lib/auth.php';
require_once __DIR__ . '/../../../src/Model/FavorisModel.php';

use App\Meteo\Lib\Auth;
use App\Meteo\Model\FavorisModel;

session_start();

// Vérifie que l'utilisateur est connecté
Auth::requireAuth();

// Vérifie si l'utilisateur est connecté et récupère son ID
if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];
} else {
    $userId = null;
}

// Récupère les favoris de l'utilisateur
$favoris = FavorisModel::getFavorisByUser($userId);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Favoris</title>
    <link rel="stylesheet" href="/SAE-3.01-Developpement-application/web/assets/css/styles.css">
    <link rel="stylesheet" href="/SAE-3.01-Developpement-application/web/assets/css/meteotheque.css">
</head>
<body>
    <header>
        <h1>Favoris</h1>
        <?php if (isset($_SESSION['login'])): ?>
        <!-- Menu déroulant pour naviguer dans les différentes sections de la météothèque -->
        <div class="dropdown">
            <button class="dropbtn">Météothèque</button>
            <div class="dropdown-content">
                <a href="/SAE-3.01-Developpement-application/web/frontController.php?page=all_meteotheques">Général</a>
                <a href="/SAE-3.01-Developpement-application/web/frontController.php?page=meteotheque">Vos Météothèques</a>
                <a href="/SAE-3.01-Developpement-application/web/frontController.php?page=favoris">Favoris</a>
            </div>
        </div>
        <?php endif; ?>
        <!-- Bouton pour retourner à l'accueil -->
        <button class="back-button" onclick="window.location.href='/SAE-3.01-Developpement-application/web/frontController.php';">🏠 Accueil</button>
    </header>
    <main>
        <section>
            <h2>Vos météothèques en favoris</h2>
            <?php if (!empty($favoris)): ?>
                <div class="favoris-list">
                    <?php foreach ($favoris as $favori): ?>
                        <div class="favori-item">
                            <!-- Lien vers les détails de la météothèque favorite -->
                            <a href="/SAE-3.01-Developpement-application/web/frontController.php?page=details_meteotheque&id=<?= htmlspecialchars($favori['id']) ?>">
                                <?= htmlspecialchars($favori['titre']) ?>
                            </a>
                            <p>Créateur: <?= htmlspecialchars($favori['creator_login']) ?> (ID: <?= htmlspecialchars($favori['creator_id']) ?>)</p>
                            <!-- Formulaire pour supprimer la météothèque des favoris -->
                            <form method="post" action="/SAE-3.01-Developpement-application/src/View/meteotheque/remove_from_favorites.php">
                                <input type="hidden" name="meteotheque_id" value="<?= htmlspecialchars($favori['id']) ?>">
                                <button type="submit" class="remove-button">❌ Supprimer des favoris</button>
                            </form>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p>Aucun favori trouvé. Ajoutez des météothèques à vos favoris pour les retrouver ici !</p>
            <?php endif; ?>
        </section>
    </main>
    <footer>
        <p>&copy; 2025 - Station météo</p>
    </footer>
</body>
</html>
