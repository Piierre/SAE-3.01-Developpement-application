<?php

if (isset($_GET['station_name']) && isset($_GET['date'])) {
    $stationName = $_GET['station_name'];
    $date = $_GET['date'];

    // Conversion de la date au format requis par l'API (YYYY/MM/DD)
    $formattedDate = date("Y/m/d", strtotime($date));

    // URL de l'API OpenDataSoft
    $apiUrl = "https://public.opendatasoft.com/api/explore/v2.1/catalog/datasets/donnees-synop-essentielles-omm/records?";
    $apiUrl .= "limit=20&refine=date%3A%22" . urlencode($formattedDate) . "%22";
    $apiUrl .= "&refine=nom%3A%22" . urlencode($stationName) . "%22";

    // Récupération des données de l'API
    $response = file_get_contents($apiUrl);

    if (!$response) {
        echo json_encode(["error" => "Impossible de récupérer les données de l'API."]);
        exit;
    }

    $data = json_decode($response, true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        echo json_encode(["error" => "Erreur de décodage JSON : " . json_last_error_msg()]);
        exit;
    }

    echo json_encode($data); // Renvoie uniquement du JSON
} else {
    echo json_encode(["error" => "Veuillez spécifier un nom de station et une date."]);
}
