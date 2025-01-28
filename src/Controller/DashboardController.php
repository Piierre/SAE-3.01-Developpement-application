<?php
// Définition du namespace pour le contrôleur
namespace App\Meteo\Controller;

// Import des modèles et bibliothèques nécessaires
use App\Meteo\Model\MeteothequeModel;
use App\Meteo\Model\FavorisModel;
use App\Meteo\Lib\Auth;

// Contrôleur gérant l'affichage et la logique du tableau de bord
class DashboardController {
    // Méthode pour afficher le tableau de bord de l'utilisateur
    public static function showDashboard() {
        Auth::requireAuth(); // Vérifie que l'utilisateur est authentifié avant d'accéder au dashboard

        // Récupération des informations de session de l'utilisateur
        $userId = $_SESSION['id'];
        $role = $_SESSION['role'];

        // Récupération des données personnelles de l'utilisateur
        $mesMeteotheques = MeteothequeModel::getMeteothequesByUser($userId);
        $mesFavoris = FavorisModel::getFavorisByUser($userId);

        // Récupération de toutes les météothèques si l'utilisateur est administrateur
        $toutesMeteotheques = ($role === 'admin') ? MeteothequeModel::getAllMeteotheques() : null;

        // Affichage de la vue du tableau de bord
        require __DIR__ . '/../View/dashboard/dashboard.php';
    }
}
