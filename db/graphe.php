<?php
// Exemple de données dynamiques générées par PHP
$data = [
    "labels" => ["Janvier", "Février", "Mars", "Avril", "Mai"],
    "datasets" => [
        [
            "label" => "Ventes 2025",
            "backgroundColor" => "rgba(75, 192, 192, 0.2)",
            "borderColor" => "rgba(75, 192, 192, 1)",
            "borderWidth" => 1,
            "data" => [120, 150, 180, 220, 260]
        ]
    ]
];

// Encode les données en JSON pour les transmettre au JavaScript
$jsonData = json_encode($data);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Graphique avec Chart.js</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div style="width: 60%; margin: auto;">
        <canvas id="myChart"></canvas>
    </div>

    <script>
        // Récupère les données PHP encodées en JSON
        const chartData = <?php echo $jsonData; ?>;

        // Configuration du graphique
        const config = {
            type: 'bar', // Type de graphique : bar, line, pie, etc.
            data: chartData,
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        };

        // Création du graphique
        const ctx = document.getElementById('myChart').getContext('2d');
        new Chart(ctx, config);
    </script>
</body>
</html>
