<?php
// Inclure le fichier de connexion
require_once 'db_connection.php';


$apiUrl = 'https://public.opendatasoft.com/api/explore/v2.1/catalog/datasets/donnees-synop-essentielles-omm/records?limit=63';

try {
    // Récupération des données depuis l'API
    $response = file_get_contents($apiUrl);
    if (!$response) {
        die("Impossible de récupérer les données de l'API.");
    }

    // Décoder le JSON en tableau PHP
    $data = json_decode($response, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        die("Erreur de décodage JSON : " . json_last_error_msg());
    }

    // Vérifier si 'results' existe
    if (!isset($data['results'])) {
        die("Format de données inattendu : 'results' non trouvé.");
    }

    $numStations = count($data['results']);
    echo "<h3>Nombre de stations récupérées : $numStations</h3>";

    // Préparer les requêtes SQL pour les tables
    $regionQuery = $pdo->prepare("
        INSERT INTO Reg (code_reg, nom_reg) 
        VALUES (:code_reg, :nom_reg)
        ON DUPLICATE KEY UPDATE nom_reg = VALUES(nom_reg)
    ");
    $departementQuery = $pdo->prepare("
        INSERT INTO Dep (code_dep, nom_dep, code_reg) 
        VALUES (:code_dep, :nom_dep, :code_reg)
        ON DUPLICATE KEY UPDATE nom_dep = VALUES(nom_dep), code_reg = VALUES(code_reg)
    ");
    $communeQuery = $pdo->prepare("
        INSERT INTO Commune (code_comm, nom_commune, code_dep) 
        VALUES (:code_comm, :nom_commune, :code_dep)
        ON DUPLICATE KEY UPDATE nom_commune = VALUES(nom_commune), code_dep = VALUES(code_dep)
    ");
    $stationQuery = $pdo->prepare("
        INSERT INTO Station (id, nom, latitude, longitude, altitude, code_comm, code_dep, code_reg) 
        VALUES (:id, :nom, :latitude, :longitude, :altitude, :code_comm, :code_dep, :code_reg)
        ON DUPLICATE KEY UPDATE 
            nom = VALUES(nom), latitude = VALUES(latitude), longitude = VALUES(longitude), 
            altitude = VALUES(altitude), code_comm = VALUES(code_comm), 
            code_dep = VALUES(code_dep), code_reg = VALUES(code_reg)
    ");
    $mesureQuery = $pdo->prepare("
        INSERT INTO Mesure (id_sta, date, pression, temperature, humidite, temp_min, temp_max, precip6, precip24) 
        VALUES (:id_sta, :date, :pression, :temperature, :humidite, :temp_min, :temp_max, :precip6, :precip24)
        ON DUPLICATE KEY UPDATE 
            pression = VALUES(pression), temperature = VALUES(temperature), 
            humidite = VALUES(humidite), temp_min = VALUES(temp_min), 
            temp_max = VALUES(temp_max), precip6 = VALUES(precip6), precip24 = VALUES(precip24)
    ");

    foreach ($data['results'] as $record) {
        

        $fields = $record; //['fields'] ?? null;

        if (!$fields) {
            echo "Station ignorée : données non trouvées.<br>";
            continue;
        }

        if (empty($fields['numer_sta'])) {
            echo "Station ignorée : ID 'numer_sta' manquant ou vide.<br>";
            continue;
        }

        // Insérer dans la table Reg
        if (!empty($fields['code_reg'])) {
            $regionQuery->execute([
                ':code_reg' => $fields['code_reg'],
                ':nom_reg' => $fields['nom_reg'] ?? 'Inconnu'
            ]);
        }

        // Insérer dans la table Dep
        if (!empty($fields['code_dep'])) {
            $departementQuery->execute([
                ':code_dep' => $fields['code_dep'],
                ':nom_dep' => $fields['nom_dept'] ?? 'Inconnu',
                ':code_reg' => $fields['code_reg'] ?? null
            ]);
        }

        // Insérer dans la table Commune
        if (!empty($fields['codegeo'])) {
            $communeQuery->execute([
                ':code_comm' => $fields['codegeo'],
                ':nom_commune' => $fields['libgeo'] ?? 'Inconnu',
                ':code_dep' => $fields['code_dep'] ?? null
            ]);
        }

        // Insérer dans la table Station
        $stationQuery->execute([
            ':id' => $fields['numer_sta'],
            ':nom' => $fields['nom'] ?? 'Inconnu',
            ':latitude' => $fields['latitude'] ?? null,
            ':longitude' => $fields['longitude'] ?? null,
            ':altitude' => $fields['altitude'] ?? null,
            ':code_comm' => $fields['codegeo'] ?? null,
            ':code_dep' => $fields['code_dep'] ?? null,
            ':code_reg' => $fields['code_reg'] ?? null
        ]);

        // Insérer dans la table Mesure
        $mesureQuery->execute([
            ':id_sta' => $fields['numer_sta'],
            ':date' => isset($fields['date']) ? date('Y-m-d', strtotime($fields['date'])) : null,
            ':pression' => $fields['pres'] ?? null,
            ':temperature' => $fields['t'] ?? null,
            ':humidite' => $fields['u'] ?? null,
            ':temp_min' => $fields['tn12'] ?? null,
            ':temp_max' => $fields['tx12'] ?? null,
            ':precip6' => $fields['rr1'] ?? null,
            ':precip24' => $fields['rr24'] ?? null
        ]);

        echo "Données insérées ou mises à jour pour la station : " . $fields['numer_sta'] . "<br>";
    }

    echo "Données insérées avec succès.";
} catch (PDOException $e) {
    die("Erreur lors de l'insertion : " . $e->getMessage());
} catch (Exception $e) {
    die("Erreur : " . $e->getMessage());
}
?>
