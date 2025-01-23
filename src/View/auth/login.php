<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/SAE-3.01-Developpement-application/web/assets/css/auth.css"> <!-- Lien vers le CSS -->
    <title>Connexion</title>
</head>
<body>
    <header>
        <h1>Connexion</h1>
    </header>
    <main>
        <section>
            <form method="POST" action="/SAE-3.01-Developpement-application/src/Controller/AuthController.php">
                <input type="hidden" name="action" value="login">
                <input type="text" name="login" placeholder="Nom d'utilisateur" required>
                <input type="password" name="password" placeholder="Mot de passe" required>
                <input type="submit" value="Se connecter">
            </form>
            <div class="register-container">
                <a href="../web/frontController.php?page=register" class="button">INSCRIPTION</a>
            </div>
        </section>
    </main>
    <footer>
        <p>© 2025 - Station météo | Design épuré et responsive 🌦️</p>
    </footer>
</body>
</html>
