<?php

namespace App\Controllers\Football;

use App\Core\Controller;
use App\Core\Response;

class FootballLeagueController extends Controller
{
    public function standing(): Response
    {
        $path = MOCK_DIR . "football/league-standing.json";
        $standing = \App\Helpers\Common::getJsonFileData($path);
        $league = $this->getLeague($standing);

        return $this->json(['standing' => $standing, 'league' => $league]);
    }

    public function schedule(): Response
    {
        $schedule = [
            'upcoming' => $this->upcoming(),
            'result' => $this->result(),
        ];
        $league = $this->getSeason();

        return $this->json(['schedule' => $schedule, 'league' => $league]);
    }

    private function upcoming()
    {
        $path = MOCK_DIR . "football/league-schedule-upcoming.json";
        return \App\Helpers\Common::getJsonFileData($path);
    }

    private function result()
    {
        $path = MOCK_DIR . "football/league-match-result.json";
        return \App\Helpers\Common::getJsonFileData($path);
    }

    private function getLeague($standing)
    {
        $best_team = $standing[0];
        $user_team = array_find($standing, function ($item) {
            return $item['name'] == 'Your Team';
        });
        $user_team['probability'] = [
            'win' => 35,
            'on_top' => 92,
        ];
        return [
            'title' => 'Premier League',
            'season' => '2024/25',
            'current_match' => '10',
            'total_matches' => '38',
            'best_team' => $best_team,
            'user_team' => $user_team,
        ];
    }

    private function getSeason()
    {
        return [
            'title' => 'Premier League',
            'season' => '2024/25',
            'current_match' => '10',
            'total_matches' => '38',
            'next_match' => [
                'opponent' => 'Manchester United FC',
                'date' => '03/02/2025',
                'time' => '15:00',
                'venue' => 'Home',
                'stadium' => 'United Arena',
                'position' => '4',
                'form' => ["W", "W", "W", "W", "D"],
            ],
            'probability' => [
                'win' => 35,
                'on_top' => 92,
            ],
        ];
    }
}
