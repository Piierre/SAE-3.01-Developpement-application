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
            overflow-y: auto; /* Assurer que le corps de la page soit défilable */
        }
    </style>
</head>
<body>
    <div id="particles-js"></div> <!-- Effet de particules en arrière-plan -->
    <header>
        <h1>Connexion</h1>
        <div class="button-home">
        <button class="btn" onclick="window.location.href='/SAE-3.01-Developpement-application/web/frontController.php'">🏠 Accueil</button> <!-- Bouton Accueil -->
        </div>
        <div class="button-container">
            <button class="btn" id="darkModeButton" onclick="toggleDarkMode()">🌙 Mode sombre</button> <!-- Bouton pour basculer en mode sombre -->
        </div>
    </header>
    <main>
        <div class="center-content">
            <h2>Connectez-vous à votre compte</h2>
            <?php
            require_once __DIR__ . '/../../../src/Lib/MessageFlash.php'; // Inclure la bibliothèque MessageFlash
            use App\Meteo\Lib\MessageFlash;
            MessageFlash::displayFlashMessages(); // Afficher les messages flash
            ?>
            <form method="POST" action="/SAE-3.01-Developpement-application/src/Controller/AuthController.php"> <!-- Formulaire de connexion -->
                <input type="hidden" name="action" value="login">
                <input type="text" name="login" placeholder="Nom d'utilisateur" required> <!-- Champ de saisie pour le nom d'utilisateur -->
                <input type="password" name="password" placeholder="Mot de passe" required> <!-- Champ de saisie pour le mot de passe -->
                <input type="submit" value="Se connecter" class="btn"> <!-- Bouton de soumission -->
            </form>
            <div class="welcome-buttons">
                <p style="font-size: 1.2rem;">Vous n'avez pas encore de compte ?</p> <!-- Invitation à s'inscrire -->
                <a href="../web/frontController.php?page=register" class="btn">Inscription</a> <!-- Lien vers l'inscription -->
            </div>
        </div>
    </main>
    <footer>
        <p>© 2025 - Station météo | Design épuré et responsive 🌦️</p> <!-- Contenu du pied de page -->
    </footer>

    <script src="https://cdn.jsdelivr.net/particles.js/2.0.0/particles.min.js"></script> <!-- Bibliothèque Particles.js -->
    <script>
        function toggleDarkMode() {
            document.body.classList.toggle('dark-mode'); // Basculer en mode sombre pour le corps de la page
            document.documentElement.classList.toggle('dark-mode'); // Basculer en mode sombre pour l'élément html
            const darkModeButton = document.getElementById('darkModeButton');
            if (document.body.classList.contains('dark-mode')) {
                darkModeButton.innerHTML = '☀️ Mode clair'; // Changer le texte du bouton en mode clair
            } else {
                darkModeButton.innerHTML = '🌙 Mode sombre'; // Changer le texte du bouton en mode sombre
            }
        }
    </script>
</body>
</html>
