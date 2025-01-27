<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/SAE-3.01-Developpement-application/web/assets/css/styles.css"> 
    <link rel="stylesheet" href="/SAE-3.01-Developpement-application/web/assets/css/auth.css"> <!-- Lien vers le CSS -->
    <title>Feedback</title>
    <style>
        .fade-in {
            opacity: 0;
            animation: fadeIn 1s forwards;
        }

        @keyframes fadeIn {
            to {
                opacity: 1;
            }
        }

        form {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 20px;
        }

        form label {
            font-size: 1.2rem;
        }

        form input[type="text"],
        form textarea,
        form select {
            width: 80%;
            padding: 15px;
            font-size: 1.2rem;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        form button {
            padding: 15px 30px;
            font-size: 1.2rem;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        form button:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>
    <div id="particles-js"></div>
    <header>
        <h1>Donnez votre avis</h1>
        <div class="button-home">
            <button class="btn" onclick="window.location.href='/SAE-3.01-Developpement-application/web/frontController.php'">🏠 Accueil</button>
        </div>
        <div class="button-container">
            <button class="btn" id="darkModeButton" onclick="toggleDarkMode()">🌙 Mode sombre</button>
        </div>
    </header>
    <main>
        <?php if (isset($_GET['success'])): ?>
            <p class="fade-in" style="color: green;">Votre feedback a été soumis avec succès !</p>
        <?php elseif (isset($_GET['error'])): ?>
            <p class="fade-in" style="color: red;">Veuillez remplir tous les champs du formulaire.</p>
        <?php endif; ?>

        <form action="/SAE-3.01-Developpement-application/web/frontController.php?action=submitFeedback" method="POST" class="fade-in">
            <label for="username">Nom d'utilisateur (optionnel) :</label>
            <input type="text" name="username" id="username">

            <label for="message">Votre message :</label>
            <textarea name="message" id="message" rows="5" cols="40" required></textarea>

            <label for="rating">Note (1 à 5) :</label>
            <select name="rating" id="rating">
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
                <option value="5">5</option>
            </select>

            <button type="submit">Envoyer</button>
        </form>
    </main>
    <footer>
        <!-- ...existing code... -->
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
