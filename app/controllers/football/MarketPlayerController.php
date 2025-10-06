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
    $playerUuids = ["a4c9e3f1b27d45d8a2f6e8c49b31c7a2", "d83f41c8b5a9477cbaee9a47e1b64c13"];

    $players = array_map([$this, 'getPlayerData'], $playerUuids);

    return $this->json($players);
  }

  private function getPlayerData($uuid): mixed
  {
    $players = $this->getAllPlayers();
    return array_find($players, function ($player) use ($uuid) {
      return $player['uuid'] === $uuid;
    }) ?? null;
  }

}