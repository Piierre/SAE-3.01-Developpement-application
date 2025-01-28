<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/SAE-3.01-Developpement-application/web/assets/css/styles.css"> <!-- Lien vers le CSS -->
    <link rel="stylesheet" href="/SAE-3.01-Developpement-application/web/assets/css/auth.css"> <!-- Lien vers le CSS -->
    <title>Connexion</title>
    <style>
        body {
            overflow-y: auto; /* Ensure the body is scrollable */
        }
    </style>
</head>
<body>
    <div id="particles-js"></div>
    <header>
        <h1>Connexion</h1>
        <div class="button-home">
        <button class="btn" onclick="window.location.href='/SAE-3.01-Developpement-application/web/frontController.php'">🏠 Accueil</button>
        </div>
        <div class="button-container">
            <button class="btn" id="darkModeButton" onclick="toggleDarkMode()">🌙 Mode sombre</button>
        </div>
    </header>
    <main>
        <div class="center-content">
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
                <p style="font-size: 1.2rem;">Vous n'avez pas encore de compte ?</p>
                <a href="../web/frontController.php?page=register" class="btn">Inscription</a>
            </div>
        </div>
    </main>
    <footer>
        <p>© 2025 - Station météo | Design épuré et responsive 🌦️</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/particles.js/2.0.0/particles.min.js"></script>
    <script>
        function toggleDarkMode() {
            document.body.classList.toggle('dark-mode');
            document.documentElement.classList.toggle('dark-mode');
            const darkModeButton = document.getElementById('darkModeButton');
            if (document.body.classList.contains('dark-mode')) {
                darkModeButton.innerHTML = '☀️ Mode clair';
            } else {
                darkModeButton.innerHTML = '🌙 Mode sombre';
            }
        }
    </script>
</body>
</html>
