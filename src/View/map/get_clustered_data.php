<?php
require_once __DIR__ . '/../../config/Conf.php';
use App\Meteo\Config\Conf;
header('Content-Type: application/json');

// Vérification des paramètres
if (!isset($_GET['date']) || empty($_GET['date'])) {
    echo json_encode(["error" => "Veuillez fournir une date valide."]);
    exit;
}

$date = $_GET['date'];

// Fonction de clustering naïve (regroupement selon proximité)
function clusterStations($stations, $distanceThreshold = 0.2) {
    $clusters = [];
    foreach ($stations as $station) {
        $added = false;
        foreach ($clusters as &$cluster) {
            $dist = sqrt(pow($cluster['lat'] - $station['latitude'], 2) + pow($cluster['lon'] - $station['longitude'], 2));
            if ($dist < $distanceThreshold) {
                $cluster['temp_sum'] += $station['temperature'];
                $cluster['count']++;
                $added = true;
                break;
            }
        }
        if (!$added) {
            $clusters[] = [
                'lat' => $station['latitude'], 
                'lon' => $station['longitude'], 
                'temp_sum' => $station['temperature'], 
                'count' => 1
            ];
        }
    }
    foreach ($clusters as &$cluster) {
        $cluster['avg_temp'] = $cluster['temp_sum'] / $cluster['count'];
        unset($cluster['temp_sum'], $cluster['count']);
    }
    return $clusters;
}

try {
    $pdo = Conf::getPDO();
    $stmt = $pdo->prepare("
        SELECT s.latitude, s.longitude, m.temperature 
        FROM mesure m
        JOIN station s ON m.id_sta = s.id
        WHERE m.date = :date
    ");
    $stmt->bindParam(':date', $date, PDO::PARAM_STR);
    $stmt->execute();
    $stations = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($stations)) {
        echo json_encode(["error" => "Aucune donnée trouvée pour cette date."]);
        exit;
    }

    $clusters = clusterStations($stations);
    echo json_encode($clusters);
} catch (PDOException $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
?>
