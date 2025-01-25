<?php

namespace App\Meteo\Model;

require_once __DIR__ . '/../config/Conf.php';
use PDO;

use App\Meteo\Config\Conf;
class FavorisModel {
    public static function getFavorisByUser($userId) {
        $pdo = Conf::getPDO();
        $stmt = $pdo->prepare("
            SELECT m.* 
            FROM Favoris f
            JOIN Meteotheque m ON f.meteotheque_id = m.id
            WHERE f.user_id = ?");
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function addToFavorites($userId, $meteothequeId) {
        $pdo = Conf::getPDO();
        $stmt = $pdo->prepare("INSERT INTO Favoris (user_id, meteotheque_id) VALUES (?, ?)");
        return $stmt->execute([$userId, $meteothequeId]);
    }
}
