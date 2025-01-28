<?php

// Espace de noms pour l'organisation du code
namespace App\Meteo\Controller;

// Inclusion du fichier de configuration de la base de données
require_once __DIR__ . '/../config/Conf.php';

// Import des classes nécessaires
use App\Meteo\Config\Conf;
use PDO;

// Contrôleur gérant les fonctionnalités de recherche
class SearchController
{
    // Méthode de recherche de stations météo par nom
    public static function searchStation($query)
    {
        // Établissement de la connexion à la base de données
        $pdo = Conf::getPDO();

        // Préparation et exécution de la requête SQL avec recherche partielle
        $stmt = $pdo->prepare("SELECT nom FROM Station WHERE nom LIKE ?");
        $stmt->execute(["%$query%"]);
        
        // Récupération des résultats sous forme de tableau associatif
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $results;
    }
}
?>
