<?php

namespace App\Services\Football;

use App\Core\Service;

class FootballMatchService extends Service
{
    protected FootballTeamService $footballTeamService;
    protected FootballLeagueService $footballLeagueService;

    public function __construct()
    {
        $this->footballTeamService = new FootballTeamService();
        $this->footballLeagueService = new FootballLeagueService();
    }

    public function getUpcomingMatch()
    {
        $result = [];
        $match = $this->findUpcomingMatch($this->footballTeamService->getCurrentTeamId());
        $league = $this->footballLeagueService->getLeagueData($match['leagueId']);
        $stadium = $this->footballTeamService->getTeamStadium($match['homeTeamId']);
        $homeTeam = $this->footballTeamService->getTeamData($match['homeTeamId']);
        $awayTeam = $this->footballTeamService->getTeamData($match['awayTeamId']);
        $odds = $this->guessOdds();

        $result = [
            'id' => $match['id'],
            'season' => $league['season'],
            'competition' => $league['name'],
            'homeTeam' => $this->getTeamData($homeTeam),
            'awayTeam' => $this->getTeamData($awayTeam),
            'date' => $match['date'],
            'time' => $match['time'],
            'stadium' => $stadium['name'],
            'odds' => $odds,
        ];

        return $result;
    }

    public function findUpcomingMatch($teamId)
    {
        // In real app, we should query database to get next match of team
        $nextMatch = [
            'id' => 2,
            'leagueId' => 3,
            'homeTeamId' => 4,
            'awayTeamId' => 5,
            'date' => '2023-08-17',
            'time' => '18:00',
        ];
        return $nextMatch;
    }

    public function getTeamData($teamData)
    {
        $team = [
            'id' => $teamData['id'],
            'name' => $teamData['name'],
            'shortName' => $teamData['shortName'],
            'logo' => $teamData['logo'],
            'position' => $teamData['position'],
        ];
        return $team;
    }

    public function guessOdds()
    {
        $odds = [
            'win' => 1.5,
            'draw' => 3.5,
            'lose' => 4.0,
        ];
        return $odds;
    }
}
