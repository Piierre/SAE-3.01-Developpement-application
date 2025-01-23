<?php
namespace App\Meteo\Controller;

require_once __DIR__ . '/../Model/UserModel.php';
use App\Meteo\Model\UserModel;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action']) && isset($_POST['user_id'])) {
        $action = $_POST['action'];
        $userId = $_POST['user_id'];

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
    header("Location: /SAE-3.01-Developpement-application/web/frontController.php?page=manage_users");
    exit();
}