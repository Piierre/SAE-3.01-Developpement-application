<?php
// Paramètres de connexion à la base de données
$host = 'localhost';
$username = 'root';
$password = '123';
$dbname = 'MeteoDB';

try {
    // Connexion à MySQL avec la base sélectionnée
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);

    // Configuration des options PDO
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Affiche les erreurs sous forme d'exceptions
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC); // Définit le mode de récupération par défaut en tableau associatif
    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false); // Désactive l'émulation des requêtes préparées

    echo "Connexion réussie à la base de données.<br>"; // Message de succès de connexion
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage()); // Message d'erreur en cas d'échec de connexion
}
?>
