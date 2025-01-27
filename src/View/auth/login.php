<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/SAE-3.01-Developpement-application/web/assets/css/styles.css"> <!-- Lien vers le CSS -->
    <title>Connexion</title>
    <style>
        body, html {
            height: 100%;
            font-family: Arial, sans-serif;
            background: linear-gradient(to right, #a8acaf, #00f2fe);
            color: #fff;
            text-align: center;
        }

        .button-container {
            position: absolute;
            top: 20px;
            right: 20px;
            display: flex;
            gap: 10px;
        }

        .btn {
            padding: 10px 20px;
            font-size: 1rem;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }

        .btn:hover {
            background-color: #218838;
        }

        nav ul {
            list-style: none;
            padding: 0;
            display: flex;
            justify-content: center;
            gap: 20px;
        }

        nav ul li {
            display: inline;
        }

        nav ul li a {
            text-decoration: none;
            color: #fff;
            font-size: 1.2rem;
            padding: 10px 20px;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        nav ul li a:hover {
            background-color: rgba(0, 0, 0, 0.2);
        }

        body.dark-mode, html.dark-mode {
            background: linear-gradient(to right, #2c3e50, #4ca1af);
            color: #ddd;
        }

        body.dark-mode h1, body.dark-mode footer {
            color: #ddd;
        }

        body.dark-mode .btn {
            background-color: #444;
            color: #ddd;
        }

        body.dark-mode .btn:hover {
            background-color: #555;
        }

        body.dark-mode nav ul li a {
            color: #ddd;
        }

        body.dark-mode nav ul li a:hover {
            background-color: rgba(255, 255, 255, 0.2);
        }
    </style>
</head>
<body>
    <header>
        <h1>Connexion</h1>
        <div class="button-container">
            <button class="btn" onclick="window.location.href='/SAE-3.01-Developpement-application/web/frontController.php'">🏠 Accueil</button>
            <button class="btn" id="darkModeButton" onclick="toggleDarkMode()">🌙 Mode sombre</button>
        </div>

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

    <script>
        function toggleDarkMode() {
            document.body.classList.toggle('dark-mode');
            document.documentElement.classList.toggle('dark-mode');
        }
    </script>
</body>
</html>
