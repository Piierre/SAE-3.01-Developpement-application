<?php
// Inclure le fichier de connexion
require_once 'db_connection.php';

// URL de base de l'API
$apiBaseUrl = 'https://public.opendatasoft.com/api/explore/v2.1/catalog/datasets/donnees-synop-essentielles-omm/records';
$limit = 100; // Nombre de résultats par requête (maximum supporté par l'API)
$offset = 0;  // Décalage initial

try {
    // Préparer la requête SQL pour insérer ou mettre à jour les stations
    $stationQuery = $pdo->prepare("
        INSERT INTO Station (id, nom, latitude, longitude, altitude, code_comm, code_dep, code_reg)
        VALUES (:id, :nom, :latitude, :longitude, :altitude, :code_comm, :code_dep, :code_reg)
        ON DUPLICATE KEY UPDATE 
            nom = VALUES(nom), latitude = VALUES(latitude), longitude = VALUES(longitude),
            altitude = VALUES(altitude), code_comm = VALUES(code_comm), 
            code_dep = VALUES(code_dep), code_reg = VALUES(code_reg)
    ");

    // Préparer la requête SQL pour insérer ou mettre à jour les départements
    $depQuery = $pdo->prepare("
        INSERT INTO Dep (code_dep, nom_dep, code_reg)
        VALUES (:code_dep, :nom_dep, :code_reg)
        ON DUPLICATE KEY UPDATE 
            nom_dep = VALUES(nom_dep), code_reg = VALUES(code_reg)
    ");

    // Préparer la requête SQL pour insérer ou mettre à jour les communes
    $communeQuery = $pdo->prepare("
        INSERT INTO Commune (code_comm, nom_commune, code_dep)
        VALUES (:code_comm, :nom_commune, :code_dep)
        ON DUPLICATE KEY UPDATE 
            nom_commune = VALUES(nom_commune), code_dep = VALUES(code_dep)
    ");

    // Préparer la requête SQL pour insérer ou mettre à jour les régions
    $regQuery = $pdo->prepare("
        INSERT INTO Reg (code_reg, nom_reg)
        VALUES (:code_reg, :nom_reg)
        ON DUPLICATE KEY UPDATE 
            nom_reg = VALUES(nom_reg)
    ");

    // Préparer la requête SQL pour insérer ou mettre à jour les mesures
    $mesureQuery = $pdo->prepare("
    INSERT INTO Mesure (id_sta, date, pression, temperature, humidite, temp_min, temp_max, precip6, precip24)
    VALUES (:id_sta, :date, :pression, :temperature, :humidite, :temp_min, :temp_max, :precip6, :precip24)
    ON DUPLICATE KEY UPDATE 
        pression = VALUES(pression), temperature = VALUES(temperature), 
        humidite = VALUES(humidite), temp_min = VALUES(temp_min),
        temp_max = VALUES(temp_max), precip6 = VALUES(precip6),
        precip24 = VALUES(precip24)
    ");

    // Préparer la requête SQL pour vérifier si l'utilisateur admin existe déjà
    $checkAdminQuery = $pdo->prepare("
        SELECT COUNT(*) FROM Utilisateur WHERE login = :login
    ");

    // Préparer la requête SQL pour insérer un utilisateur admin
    $adminQuery = $pdo->prepare("
        INSERT INTO Utilisateur (login, mdp, role, status)
        VALUES (:login, :password, :role, :status)
    ");

    // Vérifier si l'utilisateur admin existe déjà
    $checkAdminQuery->execute([':login' => 'admin']);
    $adminExists = $checkAdminQuery->fetchColumn();

    if ($adminExists == 0) {
        // Hacher le mot de passe de l'utilisateur admin
        $hashedPassword = password_hash('admin', PASSWORD_DEFAULT);

        // Exécuter la requête pour insérer un utilisateur admin
        $adminQuery->execute([
            ':login' => 'admin',
            ':password' => $hashedPassword, // Utiliser le mot de passe haché
            ':role' => 'admin',
            ':status' => 'active' // Activer le compte
        ]);

        echo "Utilisateur admin inséré avec succès.<br>";
    } else {
        echo "L'utilisateur admin existe déjà.<br>";
    }

    // Parcourir toutes les pages
    while (true) {
        // Construire l'URL avec l'offset et la limite
        $apiUrl = "$apiBaseUrl?limit=$limit&offset=$offset";

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

        // Vérifier si 'results' existe et contient des données
        if (!isset($data['results']) || empty($data['results'])) {
            break; // Fin de la pagination
        }

        // Insérer chaque enregistrement dans la base de données
        foreach ($data['results'] as $record) {
            $fields = $record;

            // Vérifier si 'numer_sta' existe
            if (empty($fields['numer_sta'])) {
                continue; // Ignorer les stations sans ID
            }

            // Exécuter la requête SQL pour la station
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

            echo "Station insérée ou mise à jour : {$fields['numer_sta']}<br>";
            
            // Vérifier si 'numer_sta' existe
            if (empty($fields['code_dep'])) {
                continue; // Ignorer les stations sans ID
            }

            // Exécuter la requête SQL pour la station
            $depQuery->execute([
                ':code_dep' => $fields['code_dep'],
                ':nom_dep' => $fields['nom_dept'],
                ':code_reg' => $fields['code_reg']
            ]);

            // Vérifier si 'numer_sta' existe
            if (empty($fields['codegeo'])) {
                continue; // Ignorer les stations sans ID
            }
            $communeQuery->execute([
                ':code_comm' => $fields['codegeo'],
                ':nom_commune' => $fields['libgeo'],
                ':code_dep' => $fields['code_dep']
            ]);

            // Vérifier si 'numer_sta' existe
            if (empty($fields['code_dep'])) {
                continue; // Ignorer les stations sans ID
            }
            // Exécuter la requête SQL pour la station
            $depQuery->execute([
                ':code_dep' => $fields['code_dep'],
                ':nom_dep' => $fields['nom_dept'],
                ':code_reg' => $fields['code_reg']
            ]);
            
             // Vérifier si 'numer_sta' existe
             if (empty($fields['code_reg'])) {
                continue; // Ignorer les stations sans ID
            }
            $regQuery->execute([
                ':code_reg' => $fields['code_reg'],
                ':nom_reg' => $fields['nom_reg']
            ]);

            // Vérifier si 'numer_sta' existe
            if (empty($fields['code_dep'])) {
                continue; // Ignorer les stations sans ID
            }

            $mesureQuery->execute([
                ':id_sta' => $fields['numer_sta'],
                ':date' => $fields['date'],
                ':pression' => $fields['pres'] ?? null,
                ':temperature' => $fields['t'] ?? null,
                ':humidite' => $fields['u'] ?? null,
                ':temp_min' => $fields['tn12'] ?? $fields['tn24'] ?? null,
                ':temp_max' => $fields['tx12'] ?? $fields['tx24'] ?? null,
                ':precip6' => $fields['rr6'] ?? null,
                ':precip24' => $fields['rr24'] ?? null
            ]);
           
        }

        // Incrémenter l'offset pour récupérer la prochaine page
        $offset += $limit;

        // Optionnel : Afficher la progression
        echo "Page suivante, offset : $offset<br>";
    }

    echo "Données insérées avec succès sans doublons.";
} catch (PDOException $e) {
    die("Erreur lors de l'insertion : " . $e->getMessage());
} catch (Exception $e) {
    die("Erreur : " . $e->getMessage()); 
}
?>
