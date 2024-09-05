<?php

require_once __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

require_once __DIR__ . '/config/db.php';

require_once 'controllers/AuthenticationController.php';
$authenticationController = new AuthenticationController();

header('Content-Type: application/json');

// Function to get the Authorization header
function getAuthorizationHeader()
{
    if (isset($_SERVER['HTTP_AUTHORIZATION'])) {
        return trim($_SERVER['HTTP_AUTHORIZATION']);
    } elseif (function_exists('apache_request_headers')) {
        $requestHeaders = apache_request_headers();
        return isset($requestHeaders['Authorization']) ? trim($requestHeaders['Authorization']) : null;
    } elseif (function_exists('getallheaders')) {
        $requestHeaders = getallheaders();
        return isset($requestHeaders['Authorization']) ? trim($requestHeaders['Authorization']) : null;
    }
    return null;
}

function isAuthenticated()
{
    global $authenticationController;
    $token = getAuthorizationHeader();

    if (!$token) {
        return false;
    }

    return $authenticationController->getTokenData($token);
}

// Check authentication and return appropriate response
$authData = isAuthenticated();

if ($authData) {
    echo json_encode([
        'status' => 'success',
        'message' => 'User authenticated',
    ]);
} else {
    http_response_code(401); // Unauthorized
    echo json_encode([
        'status' => 'error',
        'message' => 'Unauthorized. Please log in.'
    ]);
    exit();
}
