<?php
namespace App\Meteo\Controller;

use App\Meteo\Config\Conf;
use PDO;

class CarteController {

    public static function getStations() {
        try {
            // Connexion à la base de données
            $dsn = "mysql:host=" . Conf::getHostname() . ";dbname=" . Conf::getDatabase();
            $pdo = new PDO($dsn, Conf::getLogin(), Conf::getPassword());
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Requête pour récupérer les stations
            $sql = "SELECT nom, latitude, longitude, altitude FROM station";
            $stmt = $pdo->query($sql);

            // Retourner les données sous forme d'un tableau associatif
            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (\Exception $e) {
            // Gestion des erreurs
            echo "Erreur : " . $e->getMessage();
            return [];
        }
    }
}
