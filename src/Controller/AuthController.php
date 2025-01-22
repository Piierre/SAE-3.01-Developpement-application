<?php
namespace App\Meteo\Controller;

use App\Meteo\Model\UserModel;
use App\Meteo\Lib\Auth;
require_once __DIR__ . '/../Lib/auth.php';
require_once '../Model/UserModel.php';
require_once '../Lib/auth.php';

class AuthController {
    public static function login($login, $password) {
        $user = UserModel::getUserByLogin($login);
        if ($user && password_verify($password, $user['mdp'])) {
            Auth::startSession($user);
            header("Location: ../../web/frontcontroller.php?page=dashboard");
            exit();
        } else {
            echo "Login ou mot de passe incorrect.";
        }
    }

    public static function register($login, $password) {
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        if (UserModel::createUser($login, $hashedPassword)) {
            header("Location: ../../web/frontcontroller.php?page=login");
            exit();
        } else {
            echo "Erreur : Nom d'utilisateur déjà pris.";
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];
        switch ($action) {
            case 'login':
                AuthController::login($_POST['login'], $_POST['password']);
                break;
            case 'register':
                AuthController::register($_POST['login'], $_POST['password']);
                break;
        }
    }
}
?>