<?php
// Définir la racine du projet
define('ROOT', dirname(__DIR__));

// Charger les fichiers nécessaires
require_once(ROOT . '/src/config/Conf.php');
require_once(ROOT . '/src/Controller/CarteController.php');

// Router simple
$page = $_GET['page'] ?? 'home';

switch ($page) {
    case 'carte':
        require(ROOT . '/src/View/Carte.php');
        break;
    default:
        echo "<h1>Bienvenue sur l'application météo !</h1>";
        echo "<p><a href='?page=carte'>Voir la carte météo</a></p>";
        break;
}
