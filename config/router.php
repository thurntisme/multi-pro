<?php

use App\Core\Router;
use App\Controllers\LandingController;
use App\Controllers\AuthController;

$router = new Router();

$router->get('/', [LandingController::class, 'index']);
$router->get('/login', [AuthController::class, 'renderLogin']);
$router->get('/register', [AuthController::class, 'renderRegister']);

return $router;