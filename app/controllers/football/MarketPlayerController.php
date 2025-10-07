<?php

namespace App\Controllers\Football;

use App\Core\Controller;
use App\Core\Response;

class MarketPlayerController extends Controller
{
  private static $files = ["legend", "classic-70s", "classic-80s", "classic-90s", "classic-2000s", "classic-2010s", "modern", "future-star", "world-cup", "euro"];

  public function index(): Response
  {
    $players = array_map(function ($player) {
      return $this->getPlayerDataInClub($player);
    }, $this->getAllPlayers());
    return $this->json($players);
  }

  private function getAllPlayers(): array
  {
    $players = [];

    foreach (self::$files as $file) {
      $path = MOCK_DIR . "football/{$file}-players.json";
      $players = array_merge($players, \App\Helpers\Common::getJsonFileData($path));
    }
    return $players;
  }

  public function generatePlayer(): Response
  {
    $player = [
      'uuid' => \App\Helpers\Football::generatePlayerUUID(),
      'avatarUrl' => ''
    ];

    return $this->json([$player]);
  }

  public function getClubPlayers(): Response
  {
    $playerUuids = ["a4c9e3f1b27d45d8a2f6e8c49b31c7a2"];

    $players = array_map([$this, 'getPlayerData'], $playerUuids);

    return $this->json($players);
  }

  private function getTeamPlayers(): array
  {
    $playerUuids = [
      "f6a4b3c1e9d84a72b5c7d9f1a2e3b8c4",
      "b1c2d3e4f5g6h7i8j9k0l1m2n3o4p5q6",
      "c7a4e1b9d3f6a8c2b5e1d4f7a9b3c6d8",
      "d4b9f2c8a1e7b3c5d6f8a9e2b4c1d7f3",
      "f3a7c9e1b2d4f6a8c0e9d1b3a5f7c2e4",
      "c9f2a1b7d4e6f3c8a2b1e9d7f6a4c3b2",
      "d1f7a9b3c5e2d4a6b8f9c3a1e7b2d5f4",
      "d8f1c3e7a9b2f6d4c0a5e1b3f7c2d9a6",
      "l1m2n3o4p5q6r7s8t9u0v1w2x3y4z5a6",
      "a7c3f2d9b5e6a1c8d4f9b2e7c6a3d8f5",
      "d4f7a2b9c3e6a1d8b5f9c2e7a6b3d8f4"
    ];

    return array_map([$this, 'getPlayerData'], $playerUuids);
  }

  public function getTeamInfo(): Response
  {
    $team = [
      'id' => "a4c9e3f1b27d45d8a2f6e8c49b31c7a2",
      'name' => 'FC Barcelona',
      'city' => 'Barcelona',
      'country' => 'Spain',
      'founded' => 1902,
      'stadium' => 'Camp Nou',
      'capacity' => 99354,
      'manager' => 'Pep Guardiola',
      'formation' => '3-5-2',
      'players' => $this->getTeamPlayers()
    ];
    return $this->json($team);
  }

  private function getPlayerData($uuid): mixed
  {
    $players = $this->getAllPlayers();
    $playerFound = array_find($players, function ($player) use ($uuid) {
      return $player['uuid'] === $uuid;
    });
    if ($playerFound) {
      return $this->getPlayerDataInClub($playerFound);
    }
    return null;
  }

  private function getPlayerDataInClub($player): mixed
  {
    // generate dummy data for player in club
    $playerInClub = [
      'id' => \App\Helpers\Football::generatePlayerUUID(),
      'clubId' => "a4c9e3f1b27d45d8a2f6e8c49b31c7a2",
      'shirtNumber' => 10,
      'playerIndex' => 1,
      'nationalTeam' => [
        'name' => 'Brazil',
        'callUp' => true,
        'nextMatch' => '2023-12-12',
        'paymentReceived' => 1000000,
        'internationalCaps' => 10
      ],
      'transferStatus' => 'transfer-listed',
      'loan' => [
        'fee' => 1000000,
        'duration' => '1 year',
        'wage' => 1000000
      ],
      'level' => 5,
      'role' => 'Striker',
      'morale' => 'High',
      'status' => [
        'type' => 'Injured',
        'details' => 'Broken leg',
        'until' => '2023-12-12'
      ],
      'stats' => [
        'matches' => 10,
        'goals' => 10,
        'assists' => 10,
        'yellowCards' => 10,
        'redCards' => 10,
        'cleanSheets' => 10,
        'minutesPlayed' => 10,
        'rating' => 10
      ],
      'trainingPerformance' => 'Excellent'
    ];
    return array_merge($player, $playerInClub);
  }
}
