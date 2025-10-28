<?php

namespace App\Controllers\Football;

use App\Core\Controller;
use App\Core\Response;

class FootballClubController extends Controller
{
    public function index(): Response
    {
        $club = [
            'id' => '12345',
            'userId' => '67890',
            'name' => 'Best Club',
            'budget' => 1000000000,
            'stadium' => [
                'name' => 'Best Stadium',
                'capacity' => 50000
            ],
            'players' => [], // players in team
            'availablePlayers' => [], // available players
            'staff' => [] // staff in club
        ];
        return $this->json($club);
    }
}