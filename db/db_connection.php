<?php
// Paramètres de connexion à la base de données
$host = 'localhost';
$username = 'root';
$password = 'butinfo';
$dbname = 'MeteoDB';

try {
    // Connexion à MySQL avec la base sélectionnée
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);

    // Configuration des options PDO
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

    echo "Connexion réussie à la base de données.<br>";
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}
?>
