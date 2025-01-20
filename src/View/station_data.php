<?php
use App\Meteo\Controller\StationController;

if (isset($_GET['station_name']) && isset($_GET['date'])) {
    $stationName = $_GET['station_name'];
    $date = $_GET['date'];

    $data = StationController::getStationData($stationName, $date);
    
    if (empty($data)) {
        echo "Aucune donnée disponible.";
        exit;
    }

    // Fonction pour générer un graphique avec GD
    function generateGraph($values, $title, $color) {
        $width = 600;
        $height = 300;
        $image = imagecreate($width, $height);

        // Couleurs
        $background = imagecolorallocate($image, 255, 255, 255);
        $lineColor = imagecolorallocate($image, $color[0], $color[1], $color[2]);
        $black = imagecolorallocate($image, 0, 0, 0);

        // Dessiner les axes
        imageline($image, 50, 20, 50, $height - 30, $black); // Axe Y
        imageline($image, 50, $height - 30, $width - 20, $height - 30, $black); // Axe X

        // Afficher les valeurs sur l'axe X
        $step = ($width - 70) / count($values);
        $maxValue = max($values);
        $minValue = min($values);

        for ($i = 0; $i < count($values); $i++) {
            $x = 50 + $i * $step;
            $y = $height - 30 - ($values[$i] - $minValue) / ($maxValue - $minValue + 1) * ($height - 50);
            imagestring($image, 2, $x, $height - 25, $i + 1, $black);
            imagefilledellipse($image, $x, $y, 5, 5, $lineColor);
        }

        // Titre du graphique
        imagestring($image, 5, $width / 2 - 50, 5, $title, $black);

        // Afficher le graphique
        header("Content-Type: image/png");
        imagepng($image);
        imagedestroy($image);
    }

    // Générer les graphiques pour chaque paramètre
    if (isset($_GET['type'])) {
        switch ($_GET['type']) {
            case 'temperature':
                $values = array_column($data, 'temperature');
                generateGraph($values, 'Température (°C)', [255, 0, 0]);
                break;
            case 'humidite':
                $values = array_column($data, 'humidite');
                generateGraph($values, 'Humidité (%)', [0, 0, 255]);
                break;
            case 'vent':
                $values = array_column($data, 'vent');
                generateGraph($values, 'Vitesse du vent (m/s)', [0, 255, 0]);
                break;
            case 'precipitations':
                $values = array_column($data, 'precipitations');
                generateGraph($values, 'Précipitations (mm)', [128, 0, 128]);
                break;
        }
    }
}
?>
