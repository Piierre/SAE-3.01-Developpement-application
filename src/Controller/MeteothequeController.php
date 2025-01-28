<?php

// Déclaration de l'espace de noms du contrôleur
namespace App\Meteo\Controller;

// Inclusion du modèle Météothèque
require_once __DIR__ . '/../Model/MeteothequeModel.php';

use App\Meteo\Model\MeteothequeModel;

// Contrôleur gérant les opérations liées aux météothèques
class MeteothequeController
{
    // Méthode pour afficher la liste des météothèques
    public function listMeteotheques()
    {
        $meteotheques = MeteothequeModel::getAllMeteotheques();
        require __DIR__ . '/../View/meteotheque/list_meteotheques.php';
    }

    // Méthode pour ajouter une nouvelle météothèque
    public function addMeteotheque($userId, $titre, $description, $stationName, $searchDate) {
        MeteothequeModel::createMeteotheque($userId, $titre, $description, $stationName, $searchDate);
    }

    // Méthode pour lister toutes les météothèques disponibles
    public function listAllMeteotheques() {
        $meteotheques = MeteothequeModel::getAllMeteotheques();
        require __DIR__ . '/../View/meteotheque/all_meteotheques.php';
    }

    // Méthode pour supprimer une météothèque spécifique
    public function deleteMeteotheque($meteothequeId) {
        MeteothequeModel::deleteMeteotheque($meteothequeId);
    }
}
?>