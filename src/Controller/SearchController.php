<?php

namespace App\Meteo\Controller;

require_once __DIR__ . '/../config/Conf.php';

use App\Meteo\Config\Conf;
use PDO;

class SearchController
{
    public static function searchStation($query)
    {
        // Connexion à la base de données
        $pdo = Conf::getPDO();

        $stmt = $pdo->prepare("SELECT nom FROM Station WHERE nom LIKE ?");
        $stmt->execute(["%$query%"]);
        
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $results;
    }
}
?>
