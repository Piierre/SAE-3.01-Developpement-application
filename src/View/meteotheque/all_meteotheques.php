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

// Récupère toutes les météothèques
$meteotheques = MeteothequeModel::getAllMeteotheques();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Toutes les Météothèques</title>
    <link rel="stylesheet" href="/SAE-3.01-Developpement-application/web/assets/css/styles.css">
    <link rel="stylesheet" href="/SAE-3.01-Developpement-application/web/assets/css/auth.css">
    <style>
        body {
            overflow-y: auto; /* Assure que le corps de la page est défilable */
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
        }

        .cube {
            background-color: #007bff;
            color: white;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            transition: transform 0.3s;
        }

        .cube:hover {
            transform: scale(1.05);
        }

        .cube a {
            display: block;
            margin-top: 10px;
            padding: 10px;
            background-color: #0056b3;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .cube a:hover {
            background-color: #003f7f;
        }

        .favoris-button {
            margin-top: 10px;
            padding: 10px;
            background-color: #ffc107;
            color: black;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .favoris-button:hover {
            background-color: #e0a800;
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

        .delete-button {
            margin-top: 10px;
            padding: 10px;
            background-color: #dc3545;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .delete-button:hover {
            background-color: #c82333;
        }

        header {
            padding: 10px; /* Réduction du padding */
            background: rgba(43, 42, 42, 0.8);
            position: fixed;
            width: 100%;
            top: 0;
            left: 0;
            z-index: 1000;
            transition: all 0.3s ease-in-out;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        }
    </style>
</head>
<body>
    <div id="particles-js"></div>
    <header>
        <h1>Toutes les Météothèques</h1>
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
        <div class="button-home">
            <button class="btn" onclick="window.location.href='/SAE-3.01-Developpement-application/web/frontController.php'">🏠 Accueil</button>
        </div>
        <!-- Bouton pour activer/désactiver le mode sombre -->
        <div class="button-container">
            <button class="btn" id="darkModeButton" onclick="toggleDarkMode()">🌙 Mode sombre</button>
        </div>
    </header>
    <main>
        <section>
            <?php MessageFlash::displayFlashMessages(); ?>
            <div class="grid">
                <?php if (!empty($meteotheques)): ?>
                    <?php foreach ($meteotheques as $meteotheque): ?>
                        <div class="cube">
                            <strong>Météothèque :<br> <?= htmlspecialchars($meteotheque['titre']) ?></strong>
                            <p>Date : <?= htmlspecialchars($meteotheque['date_creation']) ?></p>
                            <p>Créateur: <?= htmlspecialchars($meteotheque['creator_login']) ?> </p>
                            <!-- Lien pour rechercher cette station -->
                            <a href="/SAE-3.01-Developpement-application/src/View/map/search.php?station_name=<?= urlencode($meteotheque['station_name']) ?>&date=<?= urlencode($meteotheque['search_date']) ?>&redirect=true">🔍 Rechercher cette station</a>
                            <?php if (isset($_SESSION['login'])): ?>
                                <!-- Vérifie si la météothèque est dans les favoris -->
                                <?php if (FavorisModel::isFavori($_SESSION['user_id'], $meteotheque['id'])): ?>
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
                                <!-- Formulaire pour supprimer la météothèque -->
                                <form method="post" action="/SAE-3.01-Developpement-application/web/frontController.php?action=deleteMeteotheque">
                                    <input type="hidden" name="meteotheque_id" value="<?= htmlspecialchars($meteotheque['id']) ?>">
                                    <button type="submit" class="delete-button">❌ Supprimer</button>
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
    <script src="https://cdn.jsdelivr.net/particles.js/2.0.0/particles.min.js"></script>
    <script>
        function toggleDarkMode() {
            document.body.classList.toggle('dark-mode');
            document.documentElement.classList.toggle('dark-mode');
            const darkModeButton = document.getElementById('darkModeButton');
            if (document.body.classList.contains('dark-mode')) {
                darkModeButton.innerHTML = '☀️ Mode clair';
            } else {
                darkModeButton.innerHTML = '🌙 Mode sombre';
            }
        }
    </script>
</body>
</html>
