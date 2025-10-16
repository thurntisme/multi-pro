<?php

namespace App\Controllers\Football;

use App\Core\Controller;
use App\Core\Response;

class FootballOnlineUserController extends Controller
{
    public function index(): Response
    {
        $path = MOCK_DIR . "football/online-users.json";
        $users = \App\Helpers\Common::getJsonFileData($path);
        $result = [
            'community' => $users,
            'friend' => $users,
        ];
        return $this->json($result);
    }

    public function leaderboard(): Response
    {
        $path = MOCK_DIR . "football/online-users.json";
        $leaderboard = \App\Helpers\Common::getJsonFileData($path);
        $leaderboard = array_values(array_slice($leaderboard, 0, 6));

        return $this->json($leaderboard);
    }

    public function history(): Response
    {
        $path = MOCK_DIR . "football/online-history.json";
        $history = \App\Helpers\Common::getJsonFileData($path);
        return $this->json($history);
    }
}
