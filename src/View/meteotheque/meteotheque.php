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

        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
        }

        .cube {
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
    </style>
</head>
<body>
    <header>
        <h1>Météothèque</h1>
        <?php if (isset($_SESSION['login'])): ?>
        <div class="dropdown">
            <button class="dropbtn">Météothèque</button>
            <div class="dropdown-content">
                <a href="/SAE-3.01-Developpement-application/web/frontController.php?page=all_meteotheques">Général</a>
                <a href="/SAE-3.01-Developpement-application/web/frontController.php?page=meteotheque">Vos Météothèques</a>
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
                            <!-- Formulaire de suppression de la météothèque -->
                            <form method="post" action="/SAE-3.01-Developpement-application/web/frontController.php?action=deleteMeteotheque">
                                <input type="hidden" name="meteotheque_id" value="<?= htmlspecialchars($meteotheque['id']) ?>">
                                <button type="submit" class="remove-button">❌ Supprimer</button>
                            </form>
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