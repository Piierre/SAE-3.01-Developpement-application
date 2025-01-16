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

            // Exécuter la requête SQL
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
