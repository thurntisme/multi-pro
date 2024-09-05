<?php

require_once __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Set error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Database connection setup
require_once __DIR__ . '/config/db.php';

// Include functions
require_once __DIR__ . '/utils.php';

// Include constants
require_once __DIR__ . '/constants/index.php';

define("DIR", __DIR__);
define('HOME_PATH', $_ENV['HOME_PATH'] ?? DEFAULT_HOME_PATH);

// Config timezone
require_once "controllers/CommonController.php";
$commonController = new CommonController();
date_default_timezone_set($commonController->getTimezone());

require_once "controllers/AuthenticationController.php";
$authenticationController = new AuthenticationController();

// Start session
session_start();
