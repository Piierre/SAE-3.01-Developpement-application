<?php

namespace App\Meteo\Controller;

require_once __DIR__ . '/../Model/MeteothequeModel.php';

use App\Meteo\Model\MeteothequeModel;

class MeteothequeController
{
    public function listMeteotheques()
    {
        $meteotheques = MeteothequeModel::getAllMeteotheques();
        require __DIR__ . '/../View/meteotheque/list_meteotheques.php';
    }

    public function addMeteotheque($userId, $titre, $description, $stationName, $searchDate) {
        MeteothequeModel::createMeteotheque($userId, $titre, $description, $stationName, $searchDate);
    }

    public function listAllMeteotheques() {
        $meteotheques = MeteothequeModel::getAllMeteotheques();
        require __DIR__ . '/../View/meteotheque/all_meteotheques.php';
    }
}
?>