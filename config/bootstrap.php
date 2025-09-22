<?php

require_once __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

// Include the routing file to handle routing logic
// require_once __DIR__ . '/../config/route.php';

// Include constants
require_once __DIR__ . '/constants.php';

// // Set error reporting
ini_set('display_errors', 0); // Turn off error display (optional for production)
ini_set('display_startup_errors', 1); // Display startup errors (optional)
error_reporting(E_ALL); // Report all errors

// // Specify the error log file
ini_set('log_errors', 1); // Enable error logging
ini_set('error_log', LOGS_PATH . 'app-error.log'); // Path to the error log file

// Database connection setup
$isDbOk = require __DIR__ . '/db.php';

if (!$isDbOk) {
    die('Database connection failed');
}

// // Include functions
// require_once __DIR__ . '/utils.php';


// const DIR = __DIR__;
// define('HOME_PATH', $_ENV['HOME_PATH'] ?? DEFAULT_HOME_PATH);

// require_once "controllers/CommonController.php";
// $commonController = new CommonController();

// require_once "controllers/AuthenticationController.php";
// $authenticationController = new AuthenticationController();

// // Set default timezone to UTC
// date_default_timezone_set('UTC');

// // Start session
// session_start();
// if (empty($_SESSION['csrf_token'])) {
//     $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); // Generate a CSRF token
// }
