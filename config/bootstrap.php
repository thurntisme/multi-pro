<?php
declare(strict_types=1);

// Ensure session is started before accessing $_SESSION
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Composer autoload
require __DIR__ . '/../vendor/autoload.php';

// Load constants
require __DIR__ . '/constants.php';

$dotenv = new App\Core\Dotenv();
$dotenv->load();

$errorHandler = new App\core\ErrorHandler();
$errorHandler->register();

$router = require __DIR__ . '/router.php';
$container = require __DIR__ . '/container.php';
$middleware = require __DIR__ . '/middleware.php';
$request = App\Core\Request::createFromGlobals();

// Database connection setup
$db = $container->get(App\Core\Database::class);
$isDbOk = $db->checkConnection();

if (!$isDbOk) {
    throw new \PDOException('Database connection failed');
}

$dispatcher = new App\Core\Dispatcher($router, $container, $middleware);

$response = $dispatcher->handle($request);

$response->send();
