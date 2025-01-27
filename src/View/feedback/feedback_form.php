<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feedback</title>
</head>
<body>
    <header>
        <button class="back-button" onclick="window.location.href='/SAE-3.01-Developpement-application/web/frontController.php';">🏠 Accueil</button>
    </header>
    <h1>Donnez votre avis</h1>
    <?php if (isset($_GET['success'])): ?>
        <p style="color: green;">Votre feedback a été soumis avec succès !</p>
    <?php elseif (isset($_GET['error'])): ?>
        <p style="color: red;">Veuillez remplir tous les champs du formulaire.</p>
    <?php endif; ?>

    <form action="/SAE-3.01-Developpement-application/web/frontController.php?action=submitFeedback" method="POST">
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
</body>
</html>
