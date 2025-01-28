<?php
session_start(); // Démarrer la session
session_destroy(); // Détruire la session
header("Location: login.php"); // Rediriger vers la page de connexion
exit(); // Terminer le script
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/SAE-3.01-Developpement-application/web/assets/css/auth.css"> <!-- Lien vers le CSS -->
    <title>Déconnexion</title>
</head>
<body>
    <p>Vous avez été déconnecté. <a href="login.php">Se connecter à nouveau</a></p> <!-- Message de déconnexion et lien pour se reconnecter -->
</body>
</html>