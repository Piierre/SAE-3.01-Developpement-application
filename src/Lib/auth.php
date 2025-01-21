<?php
namespace App\Meteo\Lib;
function startSession($user) {
    session_start();
    $_SESSION['login'] = $user['login'];
    $_SESSION['role'] = $user['role'];
}

function requireAuth($role = null) {
    if (!isset($_SESSION['login'])) {
        header("Location: ../../View/auth/login.php");
        exit();
    }

    if ($role && $_SESSION['role'] !== $role) {
        header("Location: ../../View/auth/403.php");
        exit();
    }
}
?>
