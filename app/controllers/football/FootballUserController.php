<?php

namespace App\Controllers\Football;

use App\Core\Controller;
use App\Core\Response;
use App\Services\FootballUserService;

class FootballUserController extends Controller
{
    private $userService;

    public function __construct()
    {
        $this->userService = new FootballUserService();
    }

    public function index(): Response
    {
        $user = $this->userService->getCurrentUser();
        return $this->json($user);
    }
}
