<?php

namespace App\Controllers\Football;

use App\Core\Controller;
use App\Core\Response;

class FootballTrainingController extends Controller
{
    public function team(): Response
    {
        $path = MOCK_DIR . "football/training-team.json";
        $staff = \App\Helpers\Common::getJsonFileData($path);
        return $this->json($staff);
    }

    public function player(): Response
    {
        $path = MOCK_DIR . "football/training-player.json";
        $staff = \App\Helpers\Common::getJsonFileData($path);
        return $this->json($staff);
    }
}
