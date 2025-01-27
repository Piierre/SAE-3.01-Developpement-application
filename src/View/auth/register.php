<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/SAE-3.01-Developpement-application/web/assets/css/styles.css"> <!-- Lien vers le CSS -->
    <link rel="stylesheet" href="/SAE-3.01-Developpement-application/web/assets/css/auth.css"> <!-- Lien vers le CSS -->
    <title>Inscription</title>
</head>
<body>
    <div id="particles-js"></div>
    <header>
        <h1>Inscription</h1>
        <div class="button-container">
            <button class="btn" onclick="window.location.href='/SAE-3.01-Developpement-application/web/frontController.php'">🏠 Accueil</button>
            <button class="btn" id="darkModeButton" onclick="toggleDarkMode()">🌙 Mode sombre</button>
        </div>
    </header>
    <main>
        <div class="center-content">
            <h2>Créez votre compte</h2>
            <?php
            require_once __DIR__ . '/../../../src/Lib/MessageFlash.php';
            use App\Meteo\Lib\MessageFlash;
            MessageFlash::displayFlashMessages();
            ?>
            <form method="POST" action="/SAE-3.01-Developpement-application/src/Controller/AuthController.php">
                <input type="hidden" name="action" value="register">
                <input type="text" name="login" placeholder="Nom d'utilisateur" required>
                <input type="password" name="password" placeholder="Mot de passe" required>
                <input type="submit" value="S'inscrire" class="btn">
            </form>
            <div class="welcome-buttons">
                <a href="../web/frontController.php?page=login" class="btn">Connexion</a>
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

        // Add animations to elements on load
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

        // Add hover effect to buttons
        const buttons = document.querySelectorAll('.btn');
        buttons.forEach(button => {
            button.addEventListener('mouseover', () => {
                button.style.transform = 'scale(1.1)';
            });
            button.addEventListener('mouseout', () => {
                button.style.transform = 'scale(1)';
            });
        });

        // Particle effect
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
