<?php
// Inclure les fichiers nécessaires
require_once 'config.php';
require_once 'Model/Database.php';
require_once 'Controller/Controller.php';

// Créer une instance du contrôleur
$controller = new Controller();

// Définir le contenu de la page d'accueil
$content = '
<h2>Bienvenue sur le site des données météorologiques SYNOP</h2>
<p>Ce site vous permet de consulter les données météorologiques mesurées sur le territoire français de 2010 à 2025.</p>
<p>Utilisez le menu de navigation pour accéder aux différentes fonctionnalités :</p>
<ul>
    <li>Recherche de données par date, région ou type de mesure</li>
    <li>Visualisation des données sur une carte interactive</li>
    <li>Informations sur le projet et l\'équipe</li>
</ul>
';