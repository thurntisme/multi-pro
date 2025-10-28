<?php

namespace App\Services\Football;

use App\Core\Service;
use App\Helpers\Common;

class FootballPlayerService extends Service
{
    private static $files = ["legend", "classic-70s", "classic-80s", "classic-90s", "classic-2000s", "classic-2010s", "classic-2020s", "modern", "future-star", "world-cup", "euro"];

    public function __construct() {}

    private static function getAllPlayerJsonData(): array
    {
        $players = [];

        foreach (self::$files as $file) {
            $path = MOCK_DIR . "football/players/{$file}-players.json";
            $players = array_merge($players, Common::getJsonFileData($path));
        }
        return $players;
    }

    public static function getPlayerData($uuid): mixed
    {
        $players = self::getAllPlayerJsonData();
        $playerFound = array_find($players, function ($player) use ($uuid) {
            return $player['uuid'] === $uuid;
        });
        if ($playerFound) {
            return self::getPlayerDataInDB($playerFound);
        }
        return null;
    }

    private static function getPlayerDataInDB($player): mixed
    {
        $status = ["injured", "unhappy", "transfer", "suspended", "fit"];
        $randStatus = $status[array_rand($status)];
        $morale = ["high", "normal", "low"];

        // TODO: convert player's attributes to string and save on db
        $attributes = json_encode($player['attributes']);

        $playerInClub = [
            'id' => \App\Helpers\Football::generatePlayerUUID(),
            'clubId' => \App\Helpers\Football::generatePlayerUUID(),
            'shirtNumber' => 1,
            'playerIndex' => 1,
            'nationalTeam' => [
                'name' => $player['nationality'],
                'callUp' => false,
                'nextMatch' => '',
                'paymentReceived' => 0,
                'internationalCaps' => 0
            ],
            'transferStatus' => 'not-listed',
            'loan' => [
                'fee' => 0,
                'duration' => '',
                'wage' => 0
            ],
            'level' => 1,
            'role' => '',
            'morale' => $morale[array_rand($morale)],
            'status' => [[
                'type' => $randStatus,
                'details' => '',
                'until' => ''
            ]],
            'stats' => [
                'matches' => 0,
                'goals' => 0,
                'assists' => 0,
                'yellowCards' => 0,
                'redCards' => 0,
                'cleanSheets' => 0,
                'minutesPlayed' => 0,
                'rating' => 0
            ],
            'trainingPerformance' => '',
            'exp' => rand(541, 10132025),
            'level' => rand(1, 99),
            'attributes' => json_decode($attributes, true),
            'attributeBonus' => rand(0, 4),
            'remainingMatches' => rand(0, 10),
        ];
        return array_merge($player, $playerInClub);
    }
}
