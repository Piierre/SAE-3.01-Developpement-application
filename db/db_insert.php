<?php
require_once 'db_connection.php';

$apiUrl = 'https://public.opendatasoft.com/api/explore/v2.1/catalog/datasets/donnees-synop-essentielles-omm/records';
$limit = 100; // Limite par page
$offset = 0; // Offset initial

try {
    // Préparer la requête SQL
    $stationQuery = $pdo->prepare("
        INSERT INTO Station (id, nom, latitude, longitude, altitude, code_comm, code_dep, code_reg)
        VALUES (:id, :nom, :latitude, :longitude, :altitude, :code_comm, :code_dep, :code_reg)
        ON DUPLICATE KEY UPDATE
            nom = VALUES(nom), latitude = VALUES(latitude), longitude = VALUES(longitude),
            altitude = VALUES(altitude), code_comm = VALUES(code_comm),
            code_dep = VALUES(code_dep), code_reg = VALUES(code_reg)
    ");

    while (true) {
        // Récupérer les données de l'API avec offset et limit
        $response = file_get_contents($apiUrl . "?limit=$limit&offset=$offset");
        if (!$response) {
            die("Impossible de récupérer les données de l'API.");
        }

        $data = json_decode($response, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            die("Erreur de décodage JSON : " . json_last_error_msg());
        }

        if (!isset($data['results']) || empty($data['results'])) {
            break; // Arrêter si aucune donnée n'est renvoyée
        }

        // Insérer les données dans la base
        foreach ($data['results'] as $fields) {
            if (empty($fields['numer_sta'])) {
                continue; // Ignorer les stations sans ID
            }

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
        }

        // Avancer vers la page suivante
        $offset += $limit;
        echo "Offset actuel : $offset<br>";
    }

    echo "Toutes les données ont été insérées ou mises à jour avec succès.";
} catch (PDOException $e) {
    die("Erreur lors de l'insertion : " . $e->getMessage());
} catch (Exception $e) {
    die("Erreur : " . $e->getMessage());
}
?>
