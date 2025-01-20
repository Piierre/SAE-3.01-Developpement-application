<?php
session_start();
session_destroy();
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
    <header>
        <h1>Déconnexion</h1>
    </header>
    <main>
        <section>
            <p>Vous avez été déconnecté avec succès.</p>
            <a href="/SAE-3.01-Developpement-application/src/View/home/index.php">Retour à l'accueil</a>
        </section>
    </main>
    <footer>
        <p>© 2025 - Station météo | Design épuré et responsive 🌦️</p>
    </footer>
</body>
</html>
