<?php

namespace App\Meteo\Model;

require_once __DIR__ . '/../config/Conf.php';
use PDO;

use App\Meteo\Config\Conf;
class FavorisModel {
    public static function getFavorisByUser($userId) {
        $pdo = Conf::getPDO();
        $stmt = $pdo->prepare("
            SELECT m.id, m.titre, m.description, m.station_name, m.search_date, u.id AS creator_id, u.login AS creator_login
            FROM Favoris f
            INNER JOIN Meteotheque m ON f.meteotheque_id = m.id
            INNER JOIN Utilisateur u ON m.user_id = u.id
            WHERE f.user_id = ?
        ");
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function addToFavorites($userId, $meteothequeId) {
        $pdo = Conf::getPDO();
        $stmt = $pdo->prepare("INSERT INTO Favoris (user_id, meteotheque_id) VALUES (?, ?)");
        return $stmt->execute([$userId, $meteothequeId]);
    }

    public static function removeFromFavorites($userId, $meteothequeId) {
        $pdo = Conf::getPDO();
        $stmt = $pdo->prepare("DELETE FROM Favoris WHERE user_id = ? AND meteotheque_id = ?");
        return $stmt->execute([$userId, $meteothequeId]);
    }

    public static function isFavori($userId, $meteothequeId) {
        $pdo = Conf::getPDO();
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM Favoris WHERE user_id = ? AND meteotheque_id = ?");
        $stmt->execute([$userId, $meteothequeId]);
        return $stmt->fetchColumn() > 0;
    }
}
