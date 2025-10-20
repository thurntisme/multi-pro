<?php

namespace App\Services\Football;

use App\Core\Service;

class FootballTeamService extends Service
{
    private static $player1Uuids = [
        "e6b2a7d3c4f1a9b5d8e3c6f1a7b4d2c9",
        "d8f3a7b6c2e9a1f4b5d7c3e8a9f2b6d4",
        "c7e9a3b5d8f2a1c4b6f9d3e7a2b5c8f1",
        "b6e3f9a2d7c1a8e4f5b9c2d3a7e8f4b1",
        "f4b11a8e4f5b96e3f9ab7ce82dc2d3a7",
        "e7b3c9a5f2d6a1b8c4e9d5f7a3b2c6d1",
        "d2a7b9c5e3f8a1d6b4c9e7f3a5b8c2d9",
        "d9a3c6e7b1f2a4c5e8b7d3f1a6e9b4c2",
        "f4b9a2c7d8e1b3f6a9c2d5e7b1f8a3c6",
        "a7d4f2b9c6e3a1d8b5f9c2e7a4b3d9f6",
        "d8f2a7b4c3e9a1d6b5f4c2e8a9b7d3f1",
        "b7f3a9c2d6e1b5a8c4f9d3e7a2b8f6c1",
        "e4b9a1d7c3f6b8a2d5e9c7a4f2b1d6c8",
        "c3f8b7a1d9e4a6b2f5c9d7e3a8b4f2d6",
        "a6d3f9b2c1e7a8d4b5f2c9e3d7a1b8f6",
        "b7e2a9d4c3f8b1a6d9e5c4a7f2b8d3e9",
        "e4d7b9a2c6f1d8a3b5e9c2f7a4d3b8e6",
        "d9b3a6f1c4e7a8b2f5c9d3e6a1b7f4c2",
        "b4f1d7a2c8e6a3b5d9f2c7e1a6b8d3f5",
        "f7c1a9d3e6b4a2c5d8f3e1b7a6c4d2f8",
        "d3f1a7c8b6e2a4d9f5c8b2e7a1d6c3f4",
        "e7c2a9d3b6f1a4c5d8e3b2f7a1c6d4e9",
        "f4c2a7d1e6b3c5a9d8f2b1e7a6c3d4f5"
    ];
    private static $player2Uuids = [
        "f6a4b3c1e9d84a72b5c7d9f1a2e3b8c4",
        "q1r2s3t4u5v6w7x8y9z0a1b2c3d4e5f6",
        "b4e9d2f7a1c3e8b5d9a6f2c4e1b7a3d8",
        "e2b6c8d9a3f7b1e4c9a8d5f6b2e7a4c3",
        "h1i2j3k4l5m6n7o8p9q0r1s2t3u4v5w6",
        "a7d3f9b2c1e8a4d6b9f2c5a3d7e1f4c8",
        "a3f9c8b5d2e6a1b7c4f8d9e2a6b3f7c5",
        "f1c8d2a7b3e9a4c6d8f2b7a5c9e3d4f6",
        "t1u2v3w4x5y6z7a8b9c0d1e2f3g4h5i6",
        "j1k2l3m4n5o6p7q8r9s0t1u2v3w4x5y6",
        "c7a4e1b9f2d6438a9c1d7e3f6b5a2c8d",
        "c1d2e3f4g5h6i7j8k9l0m1n2o3p4q5r6",
        "b3a7c9e2d4f6a1b8c2e5f9d7a4b6c3e8",
        "e7a4b2c9d1f8a6b3c5e9d2a1f7b4c8e6",
        "c7f4a9b1d2e8f3a6b5c1d9a7f2b4e6c3",
        "b7e3c9a5d2f6a1c8b4f9e7d3a6b2c8f4",
        "d8c4b1e7a3f9c2d5e6b7a9f4c1e2d3b8"
    ];

    public function __construct() {}

    public static function getCurrentTeamId()
    {
        return 1;
    }

    public static function getTeamFormation($teamId)
    {
        return $teamId === 4 ? '3-5-2' : '4-4-2';
    }

    public function getTeamData($teamId)
    {
        // In real app, we should query database to get team data
        $team1 = [
            'id' => $teamId,
            'name' => 'Manchester United',
            'shortName' => 'Man Utd',
            'logo' => 'https://example.com/manchester-united-logo.png',
            'position' => 2,
            'formation' => self::getTeamFormation($teamId),
            'players' => self::getPlayersInMatch($teamId),
        ];
        $team2 = [
            'id' => $teamId,
            'name' => 'Manchester City',
            'shortName' => 'Man City',
            'logo' => 'https://example.com/manchester-city-logo.png',
            'position' => 1,
            'formation' => self::getTeamFormation($teamId),
            'players' => self::getPlayersInMatch($teamId),
        ];
        return $teamId === 4 ? $team1 : $team2;
    }

    public static function getAllPlayers(int $teamId)
    {
        $playerUuids = $teamId === 4 ? self::$player1Uuids : self::$player2Uuids;
        $players = array_map(['\App\Services\Football\FootballPlayerService', 'getPlayerData'], $playerUuids);
        return $players;
    }

    public static function getPlayersInMatch(int $teamId)
    {
        $allPlayers = self::getAllPlayers($teamId);
        return array_slice($allPlayers, 0, 16);
    }

    public function getTeamStadium(int $teamId)
    {
        // In real app, we should query database to get stadium of team
        $stadium = [
            'id' => 1,
            'name' => 'Old Trafford',
            'capacity' => 60000,
        ];
        return $stadium;
    }
}
