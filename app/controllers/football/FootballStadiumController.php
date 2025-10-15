<?php

namespace App\Controllers\Football;

use App\Core\Controller;
use App\Core\Response;

class FootballStadiumController extends Controller
{
    public function index(): Response
    {
        $path = MOCK_DIR . "football/stadium.json";
        $stadium = \App\Helpers\Common::getJsonFileData($path);
        return $this->json($stadium);
    }
}
