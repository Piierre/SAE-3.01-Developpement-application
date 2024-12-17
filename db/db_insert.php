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

    // Créer un tableau temporaire pour stocker les données uniques
    $stations = [];

    foreach ($data['results'] as $record) {
        $fields = $record;

        if (empty($fields['numer_sta'])) {
            continue; // Ignorer les stations sans ID
        }

        // Utiliser l'ID comme clé pour éviter les doublons
        $stations[$fields['numer_sta']] = $fields;
    }

    // Préparer les requêtes SQL pour insérer les données dans la base
    $stationQuery = $pdo->prepare("\n        INSERT INTO Station (id, nom, latitude, longitude, altitude, code_comm, code_dep, code_reg) \n        VALUES (:id, :nom, :latitude, :longitude, :altitude, :code_comm, :code_dep, :code_reg) \n        ON DUPLICATE KEY UPDATE \n            nom = VALUES(nom), latitude = VALUES(latitude), longitude = VALUES(longitude), \n            altitude = VALUES(altitude), code_comm = VALUES(code_comm), \n            code_dep = VALUES(code_dep), code_reg = VALUES(code_reg)\n    ");

    foreach ($stations as $id => $fields) {
        // Insérer les données uniques dans la base
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

        echo "Station insérée ou mise à jour : $id<br>";
    }

    echo "Données insérées avec succès sans doublons.";
} catch (PDOException $e) {
    die("Erreur lors de l'insertion : " . $e->getMessage());
} catch (Exception $e) {
    die("Erreur : " . $e->getMessage());
}
