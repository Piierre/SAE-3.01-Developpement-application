<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/SAE-3.01-Developpement-application/web/assets/css/styles.css"> <!-- Lien vers le CSS -->
    <title>Connexion</title>
</head>
<body>
    <header>
        <h1>Connexion</h1>
        <nav>
            <ul>
                <li><a href="../web/frontController.php">Accueil</a></li>
                <li><a href="../web/frontController.php?page=register">Inscription</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <section id="welcome">
            <div class="welcome-content">
                <h2>Connectez-vous à votre compte</h2>
                <?php
                require_once __DIR__ . '/../../../src/Lib/MessageFlash.php';
                use App\Meteo\Lib\MessageFlash;
                MessageFlash::displayFlashMessages();
                ?>
                <form method="POST" action="/SAE-3.01-Developpement-application/src/Controller/AuthController.php">
                    <input type="hidden" name="action" value="login">
                    <input type="text" name="login" placeholder="Nom d'utilisateur" required>
                    <input type="password" name="password" placeholder="Mot de passe" required>
                    <input type="submit" value="Se connecter" class="btn">
                </form>
                <div class="welcome-buttons">
                    <a href="../web/frontController.php?page=register" class="btn">Inscription</a>
                </div>
            </div>
        </section>
    </main>
    <footer>
        <p>© 2025 - Station météo | Design épuré et responsive 🌦️</p>
    </footer>
</body>
</html>
