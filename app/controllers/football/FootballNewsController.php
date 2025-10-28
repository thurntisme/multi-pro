<?php

namespace App\Controllers\Football;

use App\Core\Controller;
use App\Core\Response;

class FootballNewsController extends Controller
{
    public function index(): Response
    {
        $path = MOCK_DIR . "football/news.json";
        $news = \App\Helpers\Common::getJsonFileData($path);
        return $this->json($news);
    }
}
