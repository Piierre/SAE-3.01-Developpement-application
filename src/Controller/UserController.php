<?php
namespace App\Meteo\Controller;

require_once __DIR__ . '/../Model/UserModel.php';
use App\Meteo\Model\UserModel;

// Vérifie si la méthode de la requête est POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Vérifie si les paramètres 'action' et 'user_id' sont définis
    if (isset($_POST['action']) && isset($_POST['user_id'])) {
        $action = $_POST['action'];
        $userId = $_POST['user_id'];

        // Exécute l'action appropriée en fonction de la valeur de 'action'
        switch ($action) {
            case 'approve':
                UserModel::approveUser($userId);
                break;
            case 'reject':
                UserModel::rejectUser($userId);
                break;
            case 'ban':
                UserModel::banUser($userId);
                break;
        }
    }
    // Redirige vers la page de gestion des utilisateurs
    header("Location: /SAE-3.01-Developpement-application/web/frontController.php?page=manage_users");
    exit();
}