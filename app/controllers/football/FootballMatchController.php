<?php

namespace App\Controllers\Football;

use App\Core\Controller;
use App\Core\Response;
use App\Services\Football\FootballMatchService;
use App\Services\Football\FootballTeamService;

class FootballMatchController extends Controller
{
    private $matchService;
    private $teamService;

    public function __construct()
    {
        $this->matchService = new FootballMatchService();
        $this->teamService = new FootballTeamService();
    }

    public function upcoming(): Response
    {
        $result = [];
        $result['match'] = $this->matchService->getUpcomingMatch();
        $result['team'] = [
            'formation' => $this->teamService->getTeamFormation(),
            'players' => $this->teamService->getPlayersInMatch(),
        ];

        return $this->json($result);
    }
}
