<?php
require_once __DIR__ . '/../../../src/Lib/auth.php';
require_once __DIR__ . '/../../../src/Model/FavorisModel.php';

use App\Meteo\Lib\Auth;
use App\Meteo\Model\FavorisModel;

session_start();

// Vérifie que l'utilisateur est connecté
Auth::requireAuth();

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
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f9f9f9;
        }

        header {
            text-align: center;
            margin-bottom: 20px;
        }

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
        }

        .back-button:hover {
            background-color: #218838;
        }

        .favoris-list {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .favori-item {
            background-color: #007bff;
            color: white;
            padding: 15px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            transition: transform 0.3s, background-color 0.3s;
            text-align: center;
        }

        .favori-item:hover {
            transform: scale(1.05);
            background-color: #0056b3;
        }

        .favori-item a {
            color: white;
            text-decoration: none;
            font-weight: bold;
        }

        .favori-item a:hover {
            text-decoration: underline;
        }

        .remove-button {
            margin-top: 10px;
            padding: 10px;
            background-color: #dc3545;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .remove-button:hover {
            background-color: #c82333;
        }
    </style>
</head>
<body>
    <header>
        <h1>Favoris</h1>
        <button class="back-button" onclick="window.location.href='/SAE-3.01-Developpement-application/web/frontController.php';">🏠 Accueil</button>
    </header>
    <main>
        <section>
            <h2>Vos météothèques en favoris</h2>
            <?php if (!empty($favoris)): ?>
                <div class="favoris-list">
                    <?php foreach ($favoris as $favori): ?>
                        <div class="favori-item">
                            <a href="/SAE-3.01-Developpement-application/web/frontController.php?page=details_meteotheque&id=<?= htmlspecialchars($favori['id']) ?>">
                                <?= htmlspecialchars($favori['titre']) ?>
                            </a>
                            <p>Créateur: <?= htmlspecialchars($favori['creator_login']) ?> (ID: <?= htmlspecialchars($favori['creator_id']) ?>)</p>
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
