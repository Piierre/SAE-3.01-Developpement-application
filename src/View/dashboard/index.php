<?php
require '../../src/Lib/auth.php';
requireAuth(); // Vérifie si l'utilisateur est connecté
?>
<h1>Bienvenue sur le tableau de bord</h1>
<a href="../../View/auth/logout.php">Se déconnecter</a>
