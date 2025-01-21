<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/SAE-3.01-Developpement-application/web/assets/css/auth.css"> <!-- Lien vers le CSS -->
    <title>Inscription</title>
</head>
<body>
    <header>
        <h1>Inscription</h1>
    </header>
    <main>
        <section>
            <form method="POST" action="../../Controller/AuthController.php">
                <input type="hidden" name="action" value="register">
                <input type="text" id="login" name="login" placeholder="Nom d'utilisateur" required>
                <input type="password" id="password" name="password" placeholder="Mot de passe" required>
                <input type="submit" value="S'inscrire">
            </form>
        </section>
    </main>
    <footer>
        <p>© 2025 - Station météo | Design épuré et responsive 🌦️</p>
    </footer>
</body>
</html>
