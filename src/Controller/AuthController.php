<?php
namespace App\Meteo\Controller;

// Import des classes nécessaires
use App\Meteo\Model\UserModel;
use App\Meteo\Lib\Auth;
use App\Meteo\Lib\MessageFlash;

// Inclusion des fichiers requis
require_once __DIR__ . '/../Lib/auth.php';
require_once '../Model/UserModel.php';
require_once '../Lib/auth.php';
require_once '../Lib/MessageFlash.php';

class AuthController {
    // Méthode de connexion des utilisateurs
    public static function login($login, $password) {
        // Récupération de l'utilisateur par son login
        $user = UserModel::getUserByLogin($login);
        // Vérification du mot de passe et existence de l'utilisateur
        if ($user && password_verify($password, $user['mdp'])) {
            // Vérification du statut du compte
            if ($user['status'] === 'pending') {
                // Compte en attente d'approbation
                MessageFlash::ajouter('warning', 'Votre compte est en attente d\'approbation.');
                header("Location: ../../web/frontController.php?page=login");
                exit();
            } elseif ($user['status'] === 'banned') {
                // Compte banni
                MessageFlash::ajouter('danger', 'Votre compte a été banni.');
                header("Location: ../../web/frontController.php?page=login");
                exit();
            } else {
                // Connexion réussie, démarrage de la session
                Auth::startSession($user);
                header("Location: ../../web/frontController.php?page=index");
                exit();
            }
        } else {
            // Échec de connexion
            MessageFlash::ajouter('danger', 'Login ou mot de passe incorrect.');
            header("Location: ../../web/frontController.php?page=login");
            exit();
        }
    }

    // Méthode d'inscription des utilisateurs
    public static function register($login, $password) {
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        if (UserModel::createUser($login, $hashedPassword)) {
            MessageFlash::ajouter('success', 'Inscription réussie. Vous pouvez maintenant vous connecter.');
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