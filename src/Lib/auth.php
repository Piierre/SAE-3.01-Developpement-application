<?php
namespace App\Meteo\Lib;

class Auth {
    // Démarre une session pour l'utilisateur
    public static function startSession($user) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['login'] = $user['login'];
        $_SESSION['role'] = $user['role'];
    }

    // Vérifie si l'utilisateur est authentifié et a le rôle requis
    public static function requireAuth($role = null) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Vérifie si l'utilisateur est connecté
        if (!isset($_SESSION['login'])) {
            header("Location: ../../View/auth/login.php");
            exit();
        }

        // Vérifie si un rôle spécifique est requis
        if ($role && $_SESSION['role'] !== $role) {
            header("Location: ../../View/auth/error/404.php"); // Redirige vers une page d'erreur
            exit();
        }
    }
}
?>
