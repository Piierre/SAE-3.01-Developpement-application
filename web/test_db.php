<?php
require_once __DIR__ . '/../src/config/Conf.php';

try {
    $pdo = Conf::getPDO();
    echo "Connexion réussie à la base de données.";
} catch (Exception $e) {
    echo "Erreur de connexion : " . $e->getMessage();
}
?>
