<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/SAE-3.01-Developpement-application/web/assets/css/styles.css"> 
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
    </style>
</head>
<body>
    <header class="fade-in">
        <button class="back-button" onclick="window.location.href='/SAE-3.01-Developpement-application/web/frontController.php';">🏠 Accueil</button>
        <button class="toggle-dark-mode" onclick="toggleDarkMode()">🌙 Mode sombre</button>
    </header>
    <h1 class="fade-in">Donnez votre avis</h1>
    <?php if (isset($_GET['success'])): ?>
        <p class="fade-in" style="color: green;">Votre feedback a été soumis avec succès !</p>
    <?php elseif (isset($_GET['error'])): ?>
        <p class="fade-in" style="color: red;">Veuillez remplir tous les champs du formulaire.</p>
    <?php endif; ?>

    <form action="/SAE-3.01-Developpement-application/web/frontController.php?action=submitFeedback" method="POST" class="fade-in">
        <label for="username">Nom d'utilisateur (optionnel) :</label><br>
        <input type="text" name="username" id="username"><br><br>

        <label for="message">Votre message :</label><br>
        <textarea name="message" id="message" rows="5" cols="40" required></textarea><br><br>

        <label for="rating">Note (1 à 5) :</label><br>
        <select name="rating" id="rating">
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3">3</option>
            <option value="4">4</option>
            <option value="5">5</option>
        </select><br><br>

        <button type="submit">Envoyer</button>
    </form>
    <script>
        function toggleDarkMode() {
            document.body.classList.toggle('dark-mode');
        }
    </script>
</body>
</html>
