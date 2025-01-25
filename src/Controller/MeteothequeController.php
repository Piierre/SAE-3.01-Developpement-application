<?php

namespace App\Meteo\Controller;

use App\Meteo\Model\MeteothequeModel;

class MeteothequeController
{
    public function listMeteotheques()
    {
        $meteotheques = MeteothequeModel::getAllMeteotheques();
        require __DIR__ . '/../View/meteotheque/list_meteotheques.php';
    }

    public function addMeteotheque($userId, $titre, $description) {
        MeteothequeModel::createMeteotheque($userId, $titre, $description);
    }
}