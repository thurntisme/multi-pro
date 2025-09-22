<?php

use App\Core\Router;
use App\Controllers\LandingController;
use App\Controllers\AuthController;
use App\Controllers\DashboardController;
use App\Controllers\TodoController;

$router = new Router();

$router->get('/', [LandingController::class, 'index']);
$router->get('/login', [AuthController::class, 'renderLogin']);
$router->get('/register', [AuthController::class, 'renderRegister']);

// Dashboard routes
$router->get('/dashboard', [DashboardController::class, 'index']);
$router->get('/app/todo', [TodoController::class, 'index']);

return $router;