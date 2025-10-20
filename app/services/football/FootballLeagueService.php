<?php

namespace App\Services\Football;

use App\Core\Service;

class FootballLeagueService extends Service
{

    public function __construct() {}

    public function getLeagueData($leagueId)
    {
        // In real app, we should query database to get stadium of team
        $league = [
            'id' => 1,
            'name' => 'English Premier League',
            'season' => '2023-2024',
        ];
        return $league;
    }
}
