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
        $match = $this->matchService->getUpcomingMatch();
        $team = $this->matchService->getTeam($match["id"]);
        $result['match'] = $match;
        $result['team'] = $team;

        return $this->json($result);
    }
}
