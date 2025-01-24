<?php
namespace App\Meteo\Controller;

use App\Meteo\Model\UserModel;
use App\Meteo\Lib\Auth;
use App\Meteo\Lib\MessageFlash;

require_once __DIR__ . '/../Lib/auth.php';
require_once '../Model/UserModel.php';
require_once '../Lib/auth.php';
require_once '../Lib/MessageFlash.php';

class AuthController {
    public static function login($login, $password) {
        $user = UserModel::getUserByLogin($login);
        if ($user && password_verify($password, $user['mdp'])) {
            if ($user['status'] === 'pending') {
                MessageFlash::ajouter('warning', 'Votre compte est en attente d\'approbation.');
            } elseif ($user['status'] === 'banned') {
                MessageFlash::ajouter('danger', 'Votre compte a été banni.');
            } else {
                Auth::startSession($user);
                header("Location: ../../web/frontController.php?page=index");
                exit();
            }
        } else {
            MessageFlash::ajouter('danger', 'Login ou mot de passe incorrect.');
        }
        header("Location: ../../web/frontController.php?page=login");
        exit();
    }

    public static function register($login, $password) {
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        if (UserModel::createUser($login, $hashedPassword)) {
            header("Location: ../../web/frontController.php?page=login");
            exit();
        } else {
            MessageFlash::ajouter('danger', 'Erreur : Nom d\'utilisateur déjà pris.');
            header("Location: ../../web/frontController.php?page=register");
            exit();
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