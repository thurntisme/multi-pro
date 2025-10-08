<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Response;

class FootballController extends Controller
{
    public function index(): Response
    {
        $data = [
            'title' => 'Football Player'
        ];
        return $this->view('football-player', $data);
    }
}