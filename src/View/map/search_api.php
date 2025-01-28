<?php

// Vérifie si les paramètres 'station_name' et 'date' sont définis dans l'URL
if (isset($_GET['station_name']) && isset($_GET['date'])) {
    $stationName = $_GET['station_name'];
    $date = $_GET['date'];

    // Conversion de la date au format requis par l'API (YYYY/MM/DD)
    $formattedDate = date("Y/m/d", strtotime($date));

    // URL de l'API OpenDataSoft avec les paramètres de requête
    $apiUrl = "https://public.opendatasoft.com/api/explore/v2.1/catalog/datasets/donnees-synop-essentielles-omm/records?";
    $apiUrl .= "limit=20&refine=date%3A%22" . urlencode($formattedDate) . "%22";
    $apiUrl .= "&refine=nom%3A%22" . urlencode($stationName) . "%22";

    // Récupération des données de l'API
    $response = file_get_contents($apiUrl);

    // Vérifie si la réponse de l'API est valide
    if (!$response) {
        echo json_encode(["error" => "Impossible de récupérer les données de l'API."]);
        exit;
    }

    // Décodage des données JSON
    $data = json_decode($response, true);

    // Vérifie si le décodage JSON a échoué
    if (json_last_error() !== JSON_ERROR_NONE) {
        echo json_encode(["error" => "Erreur de décodage JSON : " . json_last_error_msg()]);
        exit;
    }

    // Renvoie les données JSON
    echo json_encode($data);
} else {
    // Renvoie une erreur si les paramètres requis ne sont pas spécifiés
    echo json_encode(["error" => "Veuillez spécifier un nom de station et une date."]);
}
