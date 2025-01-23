<?php
// Dimensions de l'image
$width = 800;
$height = 600;

// Création d'une image vierge
$image = imagecreatetruecolor($width, $height);

// Couleur de fond (blanc)
$backgroundColor = imagecolorallocate($image, 255, 255, 255);
imagefill($image, 0, 0, $backgroundColor);

// Points de données pour la carte thermique
$dataPoints = [
    ['x' => 100, 'y' => 150, 'intensity' => 50],
    ['x' => 200, 'y' => 300, 'intensity' => 80],
    ['x' => 400, 'y' => 250, 'intensity' => 60],
    ['x' => 600, 'y' => 400, 'intensity' => 100],
    ['x' => 700, 'y' => 150, 'intensity' => 30],
];

// Fonction pour dessiner un point flou
function drawHeatPoint($image, $x, $y, $intensity, $maxRadius = 50)
{
    for ($radius = $maxRadius; $radius > 0; $radius--) {
        // Conversion explicite en entier pour éviter les avertissements
        $alpha = intval(127 - (127 * ($intensity / 100) * ($radius / $maxRadius)));
        $color = imagecolorallocatealpha($image, 255, 0, 0, $alpha);
        imagefilledellipse($image, $x, $y, $radius, $radius, $color);
    }
}

// Dessiner les points de données
foreach ($dataPoints as $point) {
    drawHeatPoint($image, $point['x'], $point['y'], $point['intensity']);
}

// Envoyer l'image au navigateur
header('Content-Type: image/png');
imagepng($image);

// Libérer la mémoire
imagedestroy($image);
?>
