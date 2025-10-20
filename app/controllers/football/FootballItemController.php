<?php

namespace App\Controllers\Football;

use App\Core\Controller;
use App\Core\Response;

class FootballItemController extends Controller
{
    public function shop(): Response
    {
        $path = MOCK_DIR . "football/shop-items.json";
        $stadium = \App\Helpers\Common::getJsonFileData($path);
        return $this->json($stadium);
    }

    public function inventory(): Response
    {
        $path = MOCK_DIR . "football/inventory-items.json";
        $stadium = \App\Helpers\Common::getJsonFileData($path);
        return $this->json($stadium);
    }
}
