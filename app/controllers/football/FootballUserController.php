<?php

namespace App\Controllers\Football;

use App\Core\Controller;
use App\Core\Response;

class FootballUserController extends Controller
{
    public function index(): Response
    {
        $user = [
            'fullname' => 'John Doe',
            'email' => 'john.doe@example.com',
            'plan' => 'premium',
            'budget' => 1000000,
            'coin' => 5000,
            'messages' => [],
            'notifications' => [],
            'level' => 1,
            'avatar' => 'https://example.com/avatar.jpg',
            'server' => 'europe-west',
            'settings' => []
        ];
        return $this->json($user);
    }
}