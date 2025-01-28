<?php
// Inclure le fichier de connexion
require_once 'db_connection.php';

try {
    // Création de la base de données
    $dbName = 'MeteoDB';
    $pdo->exec("CREATE DATABASE IF NOT EXISTS $dbName CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    $pdo->exec("USE $dbName");
    echo "Base de données '$dbName' créée ou existante.<br>";

    // Liste des requêtes pour créer les tables
    $queries = [

        // Table Region
        "CREATE TABLE IF NOT EXISTS Reg (
            code_reg VARCHAR(10) PRIMARY KEY,
            nom_reg VARCHAR(255) NOT NULL
        )",

        // Table Departement
        "CREATE TABLE IF NOT EXISTS Dep (
            code_dep VARCHAR(10) PRIMARY KEY,
            nom_dep VARCHAR(255) NOT NULL,
            code_reg VARCHAR(10),
            FOREIGN KEY (code_reg) REFERENCES Reg(code_reg)
        )",

        // Table Commune
        "CREATE TABLE IF NOT EXISTS Commune (
            code_comm VARCHAR(10) PRIMARY KEY,
            nom_commune VARCHAR(255) NOT NULL,
            code_dep VARCHAR(10),
            FOREIGN KEY (code_dep) REFERENCES Dep(code_dep)
        )",

        // Table Station
        "CREATE TABLE IF NOT EXISTS Station (
            id VARCHAR(10) PRIMARY KEY,
            nom VARCHAR(255),
            latitude FLOAT,
            longitude FLOAT,
            altitude FLOAT,
            code_comm VARCHAR(10),
            code_dep VARCHAR(10),
            code_reg VARCHAR(10),
            FOREIGN KEY (code_comm) REFERENCES Commune(code_comm),
            FOREIGN KEY (code_dep) REFERENCES Dep(code_dep),
            FOREIGN KEY (code_reg) REFERENCES Reg(code_reg)
        )",

        // Table Mesure pour faire des tests
        "CREATE TABLE IF NOT EXISTS Mesure (
            id_sta VARCHAR(10),
            date DATE,
            pression INT,
            temperature FLOAT,
            humidite FLOAT,
            temp_min FLOAT,
            temp_max FLOAT,
            precip6 FLOAT,
            precip24 FLOAT,
            PRIMARY KEY (id_sta, date),
            FOREIGN KEY (id_sta) REFERENCES Station(id)
        )",

        // Table Utilisateur
        "CREATE TABLE IF NOT EXISTS Utilisateur (
            id INT AUTO_INCREMENT PRIMARY KEY,
            login VARCHAR(50) UNIQUE NOT NULL,
            mdp VARCHAR(255) NOT NULL,
            role ENUM('admin', 'user') DEFAULT 'user',  -- Rôle pour gérer les permissions
            status ENUM('pending', 'active', 'banned') DEFAULT 'pending', -- Statut du compte (en attente, actif, banni)
            date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP -- Date de création du compte
        )",

        // Table Meteotheque
        "CREATE TABLE IF NOT EXISTS Meteotheque (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT NOT NULL,
            titre VARCHAR(255) NOT NULL,
            description TEXT,
            station_name VARCHAR(255),
            search_date DATE, 
            date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES Utilisateur(id) ON DELETE CASCADE
        )",

        // Table Favoris
        "CREATE TABLE IF NOT EXISTS Favoris (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT NOT NULL, 
            meteotheque_id INT NOT NULL, 
            date_ajout TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES Utilisateur(id) ON DELETE CASCADE,
            FOREIGN KEY (meteotheque_id) REFERENCES Meteotheque(id) ON DELETE CASCADE
        )",

        // Table Feedback 
        "CREATE TABLE IF NOT EXISTS Feedback (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            email VARCHAR(255) NOT NULL,
            message TEXT NOT NULL,
            rating INT NOT NULL,
            status ENUM('pending', 'approved') DEFAULT 'pending',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )"
    ];

    // Exécution des requêtes
    foreach ($queries as $query) {
        $pdo->exec($query);
        echo "Requête exécutée : " . strtok($query, " ") . "<br>";
    }

    // Vérification des tables créées
    $stmt = $pdo->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    echo "Tables existantes dans la base de données '$dbName': " . implode(", ", $tables) . "<br>";
} catch (PDOException $e) {
    die("Erreur : " . $e->getMessage());
}

?>
