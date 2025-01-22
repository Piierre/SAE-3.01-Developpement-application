<?php
use App\Meteo\Controller\CarteController;
use App\Meteo\Lib\Auth;

require_once __DIR__ . '/../../../src/Lib/auth.php';
require_once __DIR__ . '/../../../src/Controller/CarteController.php'; // Ensure this path is correct
Auth::requireAuth();

// Charger les données des stations depuis CarteController
$stations = CarteController::getStations();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard météo</title>
    <link rel="stylesheet" href="../../assets/css/dashboard.css"> <!-- Lien vers le CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <header>
        <h1>Dashboard météo</h1>
        <nav>
            <ul>
                <li><a href="/SAE-3.01-Developpement-application/web/frontcontroller.php">Accueil</a></li>
                <li><a href="/SAE-3.01-Developpement-application/web/frontController.php?page=carte">Carte</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <!-- Section de bienvenue -->
        <section id="welcome-container">
            <h2>Bienvenue, <?= htmlspecialchars($_SESSION['login']) ?> sur le tableau de bord</h2>
            <a href="/SAE-3.01-Developpement-application/web/frontController.php?page=logout" class="logout-link">Se déconnecter</a>
        </section>

        <!-- Section carte -->
        <section id="map-container">
            <?php include __DIR__ . '/../../../src/View/map/carte.php'; ?>
        </section>

        <!-- Section graphiques -->
        <section id="charts-container">
            <?php include __DIR__ . '/../../../src/View//search.php'; ?>
        </section>
    </main>

    <footer>
        <p>&copy; 2025 - Dashboard météo - Inspiré par les données SYNOP</p>
    </footer>
</body>
</html>
