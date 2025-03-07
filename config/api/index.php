<?php

global $url;
require_once 'controllers/ApiLogController.php';
$apiLogController = new ApiLogController();
header("Content-Type: application/json");
$api_url = str_replace('api/', '', $url);

// Detect the HTTP method
$method = $_SERVER['REQUEST_METHOD'];

// Helper function to format responses
function sendResponse($status, $code, $message, $data = null)
{
    global $method, $api_url, $payload, $userData, $apiLogController;
    $logData = [
        'timestamp' => date('Y-m-d H:i:s'),
        'context' => [
            'method' => $method,
            'route' => $api_url,
            'payload' => array_filter($payload, function ($key) {
                return $key !== 'token';
            }, ARRAY_FILTER_USE_KEY),
            'result' => [
                'status' => $status,
                'code' => $code,
                'message' => $message
            ]
        ],
        'user_id' => $userData['id'] ?? '',
    ];
    log_api_message($logData);
    $apiLogController->createLog($status, $method, $api_url, $code, $message);
    echo json_encode([
        "status" => $status,
        "code" => $code,
        "message" => $message,
        "data" => $data,
        "timestamp" => gmdate("Y-m-d\TH:i:s\Z")
    ]);
    exit;
}

try {
    $contentType = $_SERVER['CONTENT_TYPE'] ?? '';
    $payload = [];

    if (in_array($method, ['GET', 'POST', 'PUT', 'DELETE'])) {
        // Handle JSON payload
        if (stripos($contentType, 'application/json') !== false) {
            $rawPayload = file_get_contents('php://input');
            $payload = json_decode($rawPayload, true) ?? [];
            if (json_last_error() !== JSON_ERROR_NONE) {
                sendResponse("error", 400, "Invalid JSON payload");
            }
        } // Handle multipart/form-data payload
        elseif (stripos($contentType, 'multipart/form-data') !== false) {
            $payload = $_POST;
            $payload['file'] = $_FILES['file'] ?? null;
            if ($_FILES && !isset($_FILES['file'])) {
                sendResponse("error", 400, "No file provided in form data");
            }
        }

        // Define public API routes
        $publicApiRoutes = [
            'resources' => 'resources.php',
        ];

        // Define private API routes
        $privateApiRoutes = [
            'football-manager' => 'football-manager.php',
            'file-manager' => 'file-manager.php',
        ];

        // Check if the request matches a public API route
        foreach ($publicApiRoutes as $route => $file) {
            if (str_starts_with($api_url, $route)) {
                include_once $file;
                exit;
            }
        }

        // Handle private API access
        if (isset($_SESSION['token'])) {
            $token = $_SESSION['token'];
            $userData = $authenticationController->checkUserDataByToken($token);
            $user_id = $userData['id'];
            $systemController = new SystemController($user_id);

            if (!empty($userData['role'])) {
                // Check private API routes
                foreach ($privateApiRoutes as $route => $file) {
                    if (str_starts_with($api_url, $route) && checkUserPermission($route, $userData['role'])) {
                        include_once $file;
                        exit;
                    }
                }
            }
        }
        sendResponse("error", 403, "Permission denied");
    } else {
        sendResponse("error", 405, "Method Not Allowed");
    }
} catch (Exception $e) {
    sendResponse("error", 500, "Internal Server Error");
}

sendResponse("success", 200, "Welcome to the mercufee API.");

/**
 * Function to check if a user has permission to access a specific route based on their role.
 *
 * @param string $route The route the user is trying to access.
 * @param string $userRole The role of the user (e.g., 'super_admin', 'premium_user', etc.).
 * @return bool  True if the user has permission to access the route, false otherwise.
 */
function checkUserPermission($route, $userRole)
{
    global $super_user_pack, $premium_user_pack, $standard_user_pack, $guest_user_pack;
    $routePermissions = [
        'football-manager' => $super_user_pack,
        'file-manager' => $guest_user_pack,
    ];

    if (isset($routePermissions[$route]) && in_array($userRole, $routePermissions[$route])) {
        return true;
    }

    return false;
}
