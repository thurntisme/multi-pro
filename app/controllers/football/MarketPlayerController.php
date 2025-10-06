<?php

namespace App\Controllers\Football;

use App\Core\Controller;
use App\Core\Response;

class MarketPlayerController extends Controller
{
  public function index(): Response
  {
    $path = MOCK_DIR . 'football/legend-players.json';
    $players = \App\Helpers\Common::getJsonFileData($path);
    return $this->json(['data' => $players]);
  }

  public function generatePlayer(): Response
  {
    $player = [
      'uuid' => \App\Helpers\Football::generatePlayerUUID(),
      'avatarUrl' => ''
    ];

    return $this->json([$player]);
  }
}