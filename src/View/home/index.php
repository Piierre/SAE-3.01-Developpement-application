<?php
session_start();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Données Météorologiques SYNOP</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;400;700&display=swap">
    <link rel="stylesheet" href="/SAE-3.01-Developpement-application/web/assets/css/styles.css">
</head>
<body>
    <div id="particles-js"></div>
    
    <header>
        <h1>Données Météorologiques SYNOP</h1>
        <nav>
            <ul>
                <li><a href="#welcome">Accueil</a></li>
                <li><a href="../web/frontController.php?page=carte">Carte Interactive</a></li>
                <li><a href="../web/frontController.php?page=recherche">Recherche</a></li>
                <?php if (isset($_SESSION['login'])): ?>
                    <li><a href="../web/frontController.php?page=logout">Déconnexion (<?= htmlspecialchars($_SESSION['login']) ?>)</a></li>
                    <?php if ($_SESSION['role'] === 'admin'): ?>
                        <li><a href="../web/frontController.php?page=manage_users">Gérer les utilisateurs</a></li>
                    <?php endif; ?>
                <?php else: ?>
                    <li><a href="../web/frontController.php?page=login">Connexion</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>

    <main>
        <section id="welcome">
            <div class="welcome-content">
                <h2>Bienvenue</h2>
                <p class="typing-effect"></p>
                <p>Explorez nos données météorologiques SYNOP de manière interactive.</p>
                <p>Faites défiler pour découvrir nos fonctionnalités.</p>

                <div class="welcome-buttons">
                    <a href="../web/frontController.php?page=carte" class="btn">Découvrir la carte</a>
                    <a href="../web/frontController.php?page=recherche" class="btn">Faire une recherche</a>
                </div>
            </div>
        </section>

        <div class="container">
            <div class="section map" onclick="window.location.href='../web/frontController.php?page=carte'">
                <div class="background">
                    <img src="img/carte_france.png" alt="Carte Météo">
                </div>
                <span class="icon">🗺️</span>
                Carte Interactive
            </div>
            <div class="section search" onclick="window.location.href='../web/frontController.php?page=recherche'">
                <div class="background">
                    <img src="img/undraw_world_bdnk.svg" alt="Recherche Météo">
                </div>
                <span class="icon">🔍</span>
                Recherche
            </div>
            <div class="section chart" onclick="window.location.href='../web/frontController.php?page=graphique'">
                <div class="background graph-bg"></div>
                <div class="background graph-bars">
                    <div class="bar"></div>
                    <div class="bar"></div>
                    <div class="bar"></div>
                    <div class="bar"></div>
                </div>
                <div class="background">
                    <img src="img/graphique_fond.png" alt="Graphiques Météo">
                </div>
                <span class="icon">📊</span>
                Graphiques
            </div>
        </div>

        <div class="stats">
            <div>
                <span id="stations-count">0</span>
                <p>Stations Météo</p>
            </div>
            <div>
                <span id="reports-count">0</span>
                <p>Relevés</p>
            </div>
        </div>
    </main>

    <!-- Back-to-top button -->
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

        const textArray = ["Explorez nos données...", "Analysez les tendances météo...", "Préparez vos prévisions !"];
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
            animateCounter("stations-count", 62, 100);
            animateCounter("reports-count", 29200, 100);
        };

        // Remove auto-scroll functionality
        // let currentIndex = 0;
        // const sectionsArray = document.querySelectorAll('.section');

        // function autoScroll() {
        //     sectionsArray[currentIndex].scrollIntoView({ behavior: 'smooth' });
        //     currentIndex = (currentIndex + 1) % sectionsArray.length;
        // }

        // setInterval(autoScroll, 5000);

        function setThemeByTime() {
            const hours = new Date().getHours();
            if (hours >= 18 || hours < 6) {
                document.body.classList.add('dark-mode');
            } else {
                document.body.classList.remove('dark-mode');
            }
        }
        setThemeByTime();

        // Défilement fluide pour les liens internes
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                document.querySelector(this.getAttribute('href')).scrollIntoView({
                    behavior: 'smooth'
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
                    section.style.opacity = '1';
                    section.style.transform = 'translateY(0)';
                }
            });
        });

        // Animation du header au scroll
        window.addEventListener('scroll', () => {
            const header = document.querySelector('header');
            if (window.scrollY > 50) {
                header.style.background = "rgba(0, 0, 0, 0.9)";
            } else {
                header.style.background = "rgba(0, 0, 0, 0.8)";
            }
        });

        // Smooth scroll back-to-top function
        function scrollToTop() {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        // Show/hide back-to-top button
        window.addEventListener('scroll', () => {
            const backToTop = document.getElementById('back-to-top');   
            if (window.scrollY > 200) {
                backToTop.style.display = 'block';
            } else {
                backToTop.style.display = 'none';
            }
        });

        // Parallax effect for background images
        window.addEventListener('scroll', () => {
            document.querySelectorAll('.background img').forEach(img => {
                const speed = img.getAttribute('data-speed');
                const yPos = -(window.scrollY * speed / 100);
                img.style.transform = `translateY(${yPos}px)`;
            });
        });

        document.addEventListener('mousemove', (e) => {
            const icons = document.querySelector('.weather-icons');
            const x = (e.clientX / window.innerWidth) * 100 - 50;
            const y = (e.clientY / window.innerHeight) * 100 - 50;
            icons.style.transform = `translate(${x}px, ${y}px)`;
        });
    </script>
</body>
</html>
