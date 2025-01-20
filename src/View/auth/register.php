<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/SAE-3.01-Developpement-application/web/assets/css/auth.css"> <!-- Lien vers le CSS -->
    <title>Inscription</title>
</head>
<body>
    <h1>Inscription</h1>
    <form method="POST" action="../../Controller/AuthController.php">
        <input type="hidden" name="action" value="register">
        <label for="login">Nom d'utilisateur :</label>
        <input type="text" id="login" name="login" placeholder="Nom d'utilisateur" required>
        <br>
        <label for="password">Mot de passe :</label>
        <input type="password" id="password" name="password" placeholder="Mot de passe" required>
        <br>
        <button type="submit">S'inscrire</button>
    </form>
</body>
</html>
