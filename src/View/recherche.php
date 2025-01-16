<?php
require_once 'config.php';
require_once 'Model/Database.php';
require_once 'Controller/Controller.php';

$controller = new Controller();

$content = '
<h2>Recherche de données météorologiques</h2>
<form action="recherche.php" method="POST">
    <label for="date_debut">Date de début :</label>
    <input type="date" id="date_debut" name="date_debut" required>

    <label for="date_fin">Date de fin :</label>
    <input type="date" id="date_fin" name="date_fin" required>

    <label for="region">Région :</label>
    <select id="region" name="region">
        <option value="">Toutes les régions</option>
        ' . $controller->getRegionsOptions() . '
    </select>

    <label for="type_mesure">Type de mesure :</label>
    <select id="type_mesure" name="type_mesure">
        <option value="">Toutes les mesures</option>
        <option value="temperature">Température</option>
        <option value="pression">Pression atmosphérique</option>
        <option value="humidite">Humidité</option>
        <option value="vent">Vent</option>
    </select>

    <input type="submit" value="Rechercher">
</form>
';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $resultats = $controller->rechercherDonnees($_POST);
    $content .= '<h3>Résultats de la recherche</h3>';
    $content .= $controller->afficherResultats($resultats);
}