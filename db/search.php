<?php
require_once 'db_connection.php';

// URL de base de l'API
$apiBaseUrl = 'https://public.opendatasoft.com/api/explore/v2.1/catalog/datasets/donnees-synop-essentielles-omm/records';
$limit = 100; // Nombre de résultats par requête

// Recherche des stations par nom
if (isset($_GET['query'])) {
    $query = $_GET['query'] . '%';
    $stmt = $pdo->prepare("SELECT id, nom FROM Station WHERE nom LIKE ? ORDER BY nom LIMIT 10");
    $stmt->execute([$query]);

    if ($stmt->rowCount() > 0) {
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo '<div onclick="showMeasures(\'' . $row['id'] . '\')">';
            echo htmlspecialchars($row['nom']);
            echo '</div>';
        }
    } else {
        echo '<p>Aucune station trouvée.</p>';
    }
    exit;
}

// Récupération des mesures pour une station spécifique
if (isset($_GET['station_id'])) {
    $stationId = $_GET['station_id'];
    $offset = 0; // Initialisation de l'offset

    try {
        // Préparer la requête SQL pour insérer ou mettre à jour les mesures
        $mesureQuery = $pdo->prepare("
            INSERT INTO Mesure (id_sta, date, pression, temperature, humidite, temp_min, temp_max, precip6, precip24)
            VALUES (:id_sta, :date, :pression, :temperature, :humidite, :temp_min, :temp_max, :precip6, :precip24)
            ON DUPLICATE KEY UPDATE 
                pression = VALUES(pression), 
                temperature = VALUES(temperature), 
                humidite = VALUES(humidite), 
                temp_min = VALUES(temp_min), 
                temp_max = VALUES(temp_max), 
                precip6 = VALUES(precip6), 
                precip24 = VALUES(precip24)
        ");

        // Parcourir toutes les pages pour récupérer les mesures dynamiquement
        while (true) {
            $apiUrl = "$apiBaseUrl?refine.numer_sta=$stationId&limit=$limit&offset=$offset";
            $response = file_get_contents($apiUrl);

            if (!$response) {
                echo "<p>Erreur : Impossible de récupérer les données de l'API.</p>";
                break;
            }

            $data = json_decode($response, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                echo "<p>Erreur de décodage JSON : " . json_last_error_msg() . "</p>";
                break;
            }

            // Vérifier si des résultats existent
            if (!isset($data['records']) || empty($data['records'])) {
                break; // Fin de la pagination
            }

            // Insérer ou mettre à jour les mesures
            foreach ($data['records'] as $record) {
                $fields = $record['fields'];
                if (empty($fields['numer_sta']) || empty($fields['date'])) {
                    continue; // Ignorer les enregistrements incomplets
                }

                // Debug: Afficher les dates récupérées
                echo "<p>Date récupérée: " . htmlspecialchars($fields['date']) . "</p>";

                $mesureQuery->execute([
                    ':id_sta' => $fields['numer_sta'],
                    ':date' => $fields['date'],
                    ':pression' => $fields['pres'] ?? null,
                    ':temperature' => $fields['t'] ?? null,
                    ':humidite' => $fields['u'] ?? null,
                    ':temp_min' => $fields['tn12'] ?? $fields['tn24'] ?? null,
                    ':temp_max' => $fields['tx12'] ?? $fields['tx24'] ?? null,
                    ':precip6' => $fields['rr6'] ?? null,
                    ':precip24' => $fields['rr24'] ?? null,
                ]);
            }

            // Incrémenter l'offset pour récupérer la page suivante
            $offset += $limit;
        }

        // Afficher les données depuis la base de données
        $stmt = $pdo->prepare("SELECT * FROM Mesure WHERE id_sta = ? ORDER BY date DESC LIMIT 1000");
        $stmt->execute([$stationId]);

        if ($stmt->rowCount() > 0) {
            echo '<h2>Mesures de la station ID ' . htmlspecialchars($stationId) . '</h2>';
            echo '<table border="1">
                    <tr>
                        <th>Date</th>
                        <th>Pression</th>
                        <th>Température</th>
                        <th>Humidité</th>
                        <th>Temp. Min</th>
                        <th>Temp. Max</th>
                        <th>Précip. 6h</th>
                        <th>Précip. 24h</th>
                    </tr>';
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo '<tr>';
                echo '<td>' . htmlspecialchars($row['date']) . '</td>';
                echo '<td>' . htmlspecialchars($row['pression'] ?? 'N/A') . '</td>';
                echo '<td>' . htmlspecialchars($row['temperature'] ?? 'N/A') . '</td>';
                echo '<td>' . htmlspecialchars($row['humidite'] ?? 'N/A') . '</td>';
                echo '<td>' . htmlspecialchars($row['temp_min'] ?? 'N/A') . '</td>';
                echo '<td>' . htmlspecialchars($row['temp_max'] ?? 'N/A') . '</td>';
                echo '<td>' . htmlspecialchars($row['precip6'] ?? 'N/A') . '</td>';
                echo '<td>' . htmlspecialchars($row['precip24'] ?? 'N/A') . '</td>';
                echo '</tr>';
            }
            echo '</table>';
        } else {
            echo '<p>Aucune mesure trouvée pour cette station.</p>';
        }
    } catch (PDOException $e) {
        echo "<p>Erreur PDO : " . $e->getMessage() . "</p>";
    } catch (Exception $e) {
        echo "<p>Erreur : " . $e->getMessage() . "</p>";
    }
    exit;
}
?>