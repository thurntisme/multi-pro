<?php

namespace App\Controllers\Football;

use App\Core\Controller;
use App\Core\Response;

class MarketPlayerController extends Controller
{
  private static $files = ["legend-players"];

  public function index(): Response
  {
    $players = $this->getAllPlayers();
    return $this->json($players);
  }

  private function getAllPlayers(): array
  {
    $players = [];

    foreach (self::$files as $file) {
      $path = MOCK_DIR . "football/{$file}.json";
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
      'id' => "1234",
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