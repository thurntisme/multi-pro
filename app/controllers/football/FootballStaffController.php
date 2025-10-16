<?php

namespace App\Controllers\Football;

use App\Core\Controller;
use App\Core\Request;
use App\Core\Response;

class FootballStaffController extends Controller
{
    public function available(): Response
    {
        $path = MOCK_DIR . "football/available-staff.json";
        $staff = \App\Helpers\Common::getJsonFileData($path);
        return $this->json($staff);
    }

    public function own(): Response
    {
        $path = MOCK_DIR . "football/available-staff.json";
        $staff = \App\Helpers\Common::getJsonFileData($path);
        return $this->json($staff);
    }

}
