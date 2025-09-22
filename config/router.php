<?php

use App\Core\Router;
use App\Controllers\LandingController;
use App\Controllers\AuthController;
use App\Controllers\DashboardController;

$router = new Router();

$router->get('/', [LandingController::class, 'index']);
$router->get('/login', [AuthController::class, 'renderLogin']);
$router->get('/register', [AuthController::class, 'renderRegister']);

// Dashboard routes
$router->get('/dashboard', [DashboardController::class, 'index']);

return $router;