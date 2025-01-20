<?php
require_once '../Model/UserModel.php';
require_once '../Lib/auth.php';

class AuthController {
    public static function login($login, $password) {
        $user = UserModel::getUserByLogin($login);
        if ($user && password_verify($password, $user['mdp'])) {
            startSession($user);
            header("Location: ../View/dashboard/index.php");
            exit();
        } else {
            echo "Login ou mot de passe incorrect.";
        }
    }

    public static function register($login, $password) {
        if (UserModel::createUser($login, password_hash($password, PASSWORD_BCRYPT))) {
            echo "Inscription réussie !";
            header("Location: ../View/auth/login.php");
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