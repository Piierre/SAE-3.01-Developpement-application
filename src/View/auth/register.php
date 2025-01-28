<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/SAE-3.01-Developpement-application/web/assets/css/styles.css"> <!-- Lien vers le CSS -->
    <link rel="stylesheet" href="/SAE-3.01-Developpement-application/web/assets/css/auth.css"> <!-- Lien vers le CSS -->
    <title>Inscription</title>
    <style>
        body {
            overflow-y: auto; /* Assurer que le corps de la page soit défilable */
        }
    </style>
</head>
<body>
    <div id="particles-js"></div> <!-- Effet de particules en arrière-plan -->
    <header>
        <h1>Inscription</h1>
        <div class="button-home">
            <button class="btn" onclick="window.location.href='/SAE-3.01-Developpement-application/web/frontController.php'">🏠 Accueil</button> <!-- Bouton Accueil -->
        </div>
        <div class="button-container">
            <button class="btn" id="darkModeButton" onclick="toggleDarkMode()">🌙 Mode sombre</button> <!-- Bouton pour basculer en mode sombre -->
        </div>
    </header>
    <main>
        <div class="center-content">
            <h2>Créez votre compte</h2>
            <?php
            require_once __DIR__ . '/../../../src/Lib/MessageFlash.php'; // Inclure la bibliothèque MessageFlash
            use App\Meteo\Lib\MessageFlash;
            MessageFlash::displayFlashMessages(); // Afficher les messages flash
            ?>
            <form method="POST" action="/SAE-3.01-Developpement-application/src/Controller/AuthController.php"> <!-- Formulaire d'inscription -->
                <input type="hidden" name="action" value="register">
                <input type="text" name="login" placeholder="Nom d'utilisateur" required> <!-- Champ de saisie pour le nom d'utilisateur -->
                <input type="password" name="password" placeholder="Mot de passe" required> <!-- Champ de saisie pour le mot de passe -->
                <input type="submit" value="S'inscrire" class="btn"> <!-- Bouton de soumission -->
            </form>
            <div class="welcome-buttons">
                <a href="../web/frontController.php?page=login" class="btn">Connexion</a> <!-- Lien vers la connexion -->
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

        // Ajouter des animations aux éléments lors du chargement
        document.addEventListener('DOMContentLoaded', function() {
            const elements = document.querySelectorAll('h1, h2, form, .welcome-buttons, footer');
            elements.forEach((el, index) => {
                el.style.opacity = 0;
                el.style.transform = 'translateY(20px)';
                setTimeout(() => {
                    el.style.transition = 'opacity 0.6s ease-out, transform 0.6s ease-out';
                    el.style.opacity = 1;
                    el.style.transform = 'translateY(0)';
                }, index * 200);
            });
        });

        // Ajouter un effet de survol aux boutons
        const buttons = document.querySelectorAll('.btn');
        buttons.forEach(button => {
            button.addEventListener('mouseover', () => {
                button.style.transform = 'scale(1.1)';
            });
            button.addEventListener('mouseout', () => {
                button.style.transform = 'scale(1)';
            });
        });

        // Effet de particules
        particlesJS("particles-js", {
            "particles": {
                "number": {
                    "value": 80,
                    "density": { "enable": true, "value_area": 800 }
                },
                "color": { "value": "#ffffff" },
                "shape": { "type": "circle" },
                "opacity": { "value": 0.5, "random": false },
                "size": { "value": 3, "random": true },
                "line_linked": { "enable": true, "distance": 150, "color": "#ffffff", "opacity": 0.4, "width": 1 },
                "move": { "enable": true, "speed": 3, "direction": "none", "random": false, "straight": false }
            },
            "interactivity": {
                "events": {
                    "onhover": { "enable": true, "mode": "repulse" }
                }
            }
        });
    </script>
</body>
</html>
