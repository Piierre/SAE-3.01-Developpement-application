<?php
namespace App\Meteo\Controller;

use App\Meteo\Model\MeteothequeModel;
use App\Meteo\Model\FavorisModel;
use App\Meteo\Lib\Auth;

class DashboardController {
    public static function showDashboard() {
        Auth::requireAuth(); // Vérifie que l'utilisateur est connecté

        $userId = $_SESSION['id'];
        $role = $_SESSION['role'];

        // Récupère les données
        $mesMeteotheques = MeteothequeModel::getMeteothequesByUser($userId);
        $mesFavoris = FavorisModel::getFavorisByUser($userId);

        // Si admin, récupère toutes les météothèques
        $toutesMeteotheques = ($role === 'admin') ? MeteothequeModel::getAllMeteotheques() : null;

        require __DIR__ . '/../View/dashboard/dashboard.php';
    }
}
