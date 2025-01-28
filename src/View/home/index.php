<?php
session_start(); // Démarrer la session
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Données Météorologiques SYNOP</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;400;700&display=swap">
    <link rel="stylesheet" href="/SAE-3.01-Developpement-application/web/assets/css/styles.css">
    <style>
        /* Ajoutez les styles CSS ici */
        nav ul li a {
            font-size: 1rem; /* Taille de police uniforme */
            color: #fff; /* Couleur du texte */
            text-decoration: none; /* Pas de soulignement */
            padding: 10px 20px; /* Espacement interne */
            display: inline-block; /* Affichage en ligne */
        }

        nav ul li a:hover {
            background-color: #007bff; /* Couleur de fond au survol */
            color: #fff; /* Couleur du texte au survol */
        }

        nav ul li.dropdown .dropbtn {
            font-size: 1rem; /* Taille de police uniforme */
            color: #fff; /* Couleur du texte */
            text-decoration: none; /* Pas de soulignement */
            padding: 10px 20px; /* Espacement interne */
            display: inline-block; /* Affichage en ligne */
        }

        nav ul li.dropdown .dropbtn:hover {
            background-color: #007bff; /* Couleur de fond au survol */
            color: #fff; /* Couleur du texte au survol */
        }
    </style>
</head>
<body>
    <div id="particles-js"></div> <!-- Conteneur pour l'effet de particules -->

    <header>
        <h1>Données Météorologiques SYNOP</h1>
        <nav>
            <ul id="nav-list">
                <li><a href="#welcome">🏠 Accueil</a></li>
                <li><a href="../web/frontController.php?page=all_meteotheques">🗃️ Météothèque</a></li>
                <?php if (isset($_SESSION['login'])): ?>
                    <li class="dropdown">
                        <a href="javascript:void(0)" class="dropbtn">🔧 Fonctionnalités</a>
                        <div class="dropdown-content">
                            <a href="../web/frontController.php?page=recherche">🔍 Recherche</a>
                            <a href="../web/frontController.php?page=carte_thermique">🗺️ Carte Thermique</a>
                            <a href="../web/frontController.php?page=carte">🗺️ Carte Interactive</a>
                            <?php if ($_SESSION['role'] === 'admin'): ?>
                                <a href="../web/frontController.php?page=list_feedback">📝 Liste des feedbacks</a>
                                <a href="../web/frontController.php?page=manage_users">👥 Gérer les utilisateurs</a>
                            <?php else: ?>
                                <a href="../web/frontController.php?page=feedback">📝 Feedback</a>
                            <?php endif; ?>
                        </div>
                    </li>
                    <li><a href="../web/frontController.php?page=logout">🚪 Déconnexion (<?= htmlspecialchars($_SESSION['login']) ?>)</a></li>
                <?php else: ?>
                    <li><a href="../web/frontController.php?page=login">🔑 Connexion</a></li>
                <?php endif; ?>
            </ul>
        </nav>
        <button class="toggle-dark-mode" id="darkModeButton" onclick="toggleDarkMode()">🌙 Mode sombre</button> <!-- Bouton pour basculer le mode sombre -->
    </header>

    <main>
        <section id="welcome">
            <div class="welcome-content">
                <h2>Bienvenue</h2>
                <p class="typing-effect"></p> <!-- Effet de texte en cours de frappe -->
                <p>Explorez nos données météorologiques SYNOP de manière interactive.</p>
                <p>Faites défiler pour découvrir nos fonctionnalités.</p>

                <?php if (isset($_SESSION['login'])): ?>
                    <div class="welcome-buttons">
                        <a href="../web/frontController.php?page=carte" class="btn">Découvrir la carte</a>
                        <a href="../web/frontController.php?page=recherche" class="btn">Faire une recherche</a>
                    </div>
                <?php endif; ?>
            </div>
        </section>

        <div class="container">
            <?php if (isset($_SESSION['login'])): ?>
                <div class="section map" onclick="window.location.href='../web/frontController.php?page=carte'">
                    <div class="background">
                        <img src="img/carte_france.png" alt="Carte Météo"> <!-- Image de la carte météo -->
                    </div>
                    <span class="icon">🗺️</span>
                    Carte Interactive
                </div>
                <div class="section search" onclick="window.location.href='../web/frontController.php?page=recherche'">
                    <div class="background">
                        <img src="img/undraw_world_bdnk.svg" alt="Recherche Météo"> <!-- Image de recherche météo -->
                    </div>
                    <span class="icon">🔍</span>
                    Recherche
                </div>
                <div class="section chart" onclick="window.location.href='../web/frontController.php?page=all_meteotheques'">
                    <div class="background graph-bg"></div>
                    <div class="background graph-bars">
                        <div class="bar"></div>
                        <div class="bar"></div>
                        <div class="bar"></div>
                    </div>
                    <span class="icon">🗃️</span>
                    Météothèque
                </div>
            <?php else: ?>
                <style>
                    .container {
                        display: none; /* Masquer le conteneur si l'utilisateur n'est pas connecté */
                    }
                </style>
            <?php endif; ?>
        </div>

        <div class="stats">
            <div>
                <span id="stations-count">0</span> <!-- Compteur de stations météo -->
                <p>Stations Météo</p>
            </div>
            <div>
                <span id="reports-count">0</span> <!-- Compteur de relevés météo -->
                <p>Relevés</p>
            </div>
        </div>
    </main>

    <!-- Bouton retour en haut -->
    <div id="back-to-top" onclick="scrollToTop()">↑</div>

    <footer>
        <p>© 2025 - SAÉ 3.01 | Application météorologique 🌦️</p>
        <p><a href="#welcome">Retour en haut</a></p>
    </footer>

    <script src="https://cdn.jsdelivr.net/particles.js/2.0.0/particles.min.js"></script>
    <script src="/SAE-3.01-Developpement-application/web/assets/js/scripts.js"></script>
    <script>
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

        const textArray = ["Explorez l'historique météo...", "Analysez les tendances passées...", "Revivez les données climatiques..."];
        let textIndex = 0;
        let charIndex = 0;
        const typingElement = document.querySelector('.typing-effect');

        function typeText() {
            if (charIndex < textArray[textIndex].length) {
                typingElement.innerHTML += textArray[textIndex].charAt(charIndex);
                charIndex++;
                setTimeout(typeText, 100);
            } else {
                setTimeout(() => {
                    charIndex = 0;
                    typingElement.innerHTML = "";
                    textIndex = (textIndex + 1) % textArray.length;
                    typeText();
                }, 2000);
            }
        }

        typeText();

        function animateCounter(elementId, endValue, duration) {
            let startValue = 0;
            let increment = Math.ceil(endValue / duration);
            let counter = setInterval(() => {
                startValue += increment;
                if (startValue >= endValue) {
                    startValue = endValue;
                    clearInterval(counter);
                }
                document.getElementById(elementId).textContent = startValue;
            }, 50);
        }

        window.onload = function () {
            animateCounter("stations-count", 62, 100); // Animer le compteur des stations météo
            animateCounter("reports-count", 29200, 100); // Animer le compteur des relevés
        };

        function setThemeByTime() {
            const hours = new Date().getHours();
            if (hours >= 18 || hours < 6) {
                document.body.classList.add('dark-mode'); // Activer le mode sombre le soir et la nuit
            } else {
                document.body.classList.remove('dark-mode'); // Désactiver le mode sombre le jour
            }
        }
        setThemeByTime();

        function toggleDarkMode() {
            document.body.classList.toggle('dark-mode'); // Basculer le mode sombre
            document.documentElement.classList.toggle('dark-mode');
            const darkModeButton = document.getElementById('darkModeButton');
            if (document.body.classList.contains('dark-mode')) {
                darkModeButton.innerHTML = '☀️ Mode clair'; // Changer le texte du bouton en fonction du mode
            } else {
                darkModeButton.innerHTML = '🌙 Mode sombre';
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            const darkModeButton = document.getElementById('darkModeButton');
            if (document.body.classList.contains('dark-mode')) {
                darkModeButton.innerHTML = '☀️ Mode clair'; // Initialiser le texte du bouton en fonction du mode
            } else {
                darkModeButton.innerHTML = '🌙 Mode sombre';
            }
        });

        function toggleNav() {
            const navList = document.getElementById('nav-list');
            navList.classList.toggle('active'); // Basculer l'affichage du menu de navigation
        }

        // Défilement fluide pour les liens internes
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                document.querySelector(this.getAttribute('href')).scrollIntoView({
                    behavior: 'smooth' // Défilement fluide
                });
            });
        });

        // Ajout d'un effet d'entrée pour les sections au scroll
        const sections = document.querySelectorAll('.section');
        window.addEventListener('scroll', () => {
            sections.forEach(section => {
                const position = section.getBoundingClientRect().top;
                const windowHeight = window.innerHeight;
                if (position < windowHeight - 100) {
                    section.style.opacity = '1'; // Rendre la section visible
                    section.style.transform = 'translateY(0)'; // Réinitialiser la position
                }
            });
        });

        // Animation du header au scroll
        window.addEventListener('scroll', () => {
            const header = document.querySelector('header');
            if (window.scrollY > 50) {
                header.style.background = "rgba(0, 0, 0, 0.9)"; // Changer la couleur de fond du header
            } else {
                header.style.background = "rgba(0, 0, 0, 0.8)";
            }
        });

        function scrollToTop() {
            window.scrollTo({ top: 0, behavior: 'smooth' }); // Défilement fluide vers le haut
        }

        window.addEventListener('scroll', () => {
            const backToTop = document.getElementById('back-to-top');   
            if (window.scrollY > 200) {
                backToTop.style.display = 'block'; // Afficher le bouton retour en haut
            } else {
                backToTop.style.display = 'none'; // Masquer le bouton retour en haut
            }
        });

        window.addEventListener('scroll', () => {
            document.querySelectorAll('.background img').forEach(img => {
                const speed = img.getAttribute('data-speed');
                const yPos = -(window.scrollY * speed / 100);
                img.style.transform = `translateY(${yPos}px)`; // Déplacer l'image en fonction du défilement
            });
        });

        document.addEventListener('mousemove', (e) => {
            const icons = document.querySelector('.weather-icons');
            const x = (e.clientX / window.innerWidth) * 100 - 50;
            const y = (e.clientY / window.innerHeight) * 100 - 50;
            icons.style.transform = `translate(${x}px, ${y}px)`; // Déplacer les icônes en fonction de la souris
        });
    </script>
</body>
</html>
