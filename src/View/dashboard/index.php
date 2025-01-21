<?php
use App\Meteo\Lib\Auth;
require_once __DIR__ . '/../../../src/Lib/auth.php';
Auth::requireAuth();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord</title>
</head>
<body>
    <h1>Bienvenue, <?= htmlspecialchars($_SESSION['login']) ?> sur le tableau de bord</h1>
    <a href="../../View/auth/logout.php">Se déconnecter</a>
</body>
</html>
