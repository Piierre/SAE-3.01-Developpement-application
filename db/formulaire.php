<?php
// Récupérer les données de l'API
$apiUrl = "https://public.opendatasoft.com/api/records/1.0/search/?dataset=donnees-synop-essentielles-omm&rows=100";
$response = file_get_contents($apiUrl);
$data = json_decode($response, true);

// Vérifier si des données ont été récupérées
$records = [];
if ($data && isset($data['records'])) {
    // Récupérer les records
    $records = $data['records'];
}

// Filtrer les résultats en fonction des critères du formulaire
$filteredResults = [];
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $date = $_POST['date'];
    $measure = $_POST['measure'];
    $station = $_POST['station'];

    foreach ($records as $record) {
        // Filtrage par date et station
        if ($record['fields']['date'] == $date && strtolower($record['fields']['nom']) == strtolower($station)) {
            // Ajout des résultats filtrés
            $filteredResults[] = $record['fields'];
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Résultats de la Recherche</title>
</head>
<body>
    <h1>Résultats de la Recherche</h1>

    <!-- Formulaire de recherche -->
    <form action="" method="POST">
        <label for="date">Date (AAAA-MM-JJ) :</label>
        <input type="date" id="date" name="date" required><br><br>

        <label for="measure">Mesure :</label>
        <select id="measure" name="measure" required>
            <option value="temperature">Température</option>
            <option value="precip6">Précipitations sur 6h</option>
            <option value="precip24">Précipitations sur 24h</option>
        </select><br><br>

        <label for="station">Station :</label>
        <input type="text" id="station" name="station" required><br><br>

        <button type="submit">Rechercher</button>
    </form>

    <!-- Affichage des résultats -->
    <?php if (!empty($filteredResults)): ?>
        <table border="1">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Station</th>
                    <th><?php echo ucfirst($measure); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($filteredResults as $row): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['date']); ?></td>
                        <td><?php echo htmlspecialchars($row['nom']); ?></td>
                        <td><?php echo htmlspecialchars($row[$measure]); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Aucune donnée trouvée pour cette recherche.</p>
    <?php endif; ?>
</body>
</html>
