<form method="POST" action="../../src/Controller/AuthController.php">
    <input type="hidden" name="action" value="login">
    <input type="text" name="login" placeholder="Nom d'utilisateur" required>
    <input type="password" name="password" placeholder="Mot de passe" required>
    <button type="submit">Se connecter</button>
</form>
