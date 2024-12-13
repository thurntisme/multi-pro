<?php

global $url;
header("Content-Type: application/json");
$api_url = str_replace('api/', '', $url);

// Detect the HTTP method
$method = $_SERVER['REQUEST_METHOD'];

// Helper function to format responses
function sendResponse($status, $code, $message, $data = null)
{
    echo json_encode([
        "status" => $status,
        "code" => $code,
        "message" => $message,
        "data" => $data,
        "timestamp" => gmdate("Y-m-d\TH:i:s\Z")
    ]);
    exit;
}

// Decode JSON payload if method is POST, PUT, or DELETE
if (in_array($method, ['POST', 'PUT', 'DELETE'])) {
    $rawPayload = file_get_contents('php://input');
    $payload = json_decode($rawPayload, true);

    // Check if JSON decoding failed
    if (json_last_error() !== JSON_ERROR_NONE) {
        sendResponse("error", 400, "Invalid JSON payload");
    }
}

if (str_starts_with($api_url, 'football-manager')) {
    include_once DIR . '/api/football-manager.php';
}
sendResponse("success", 200, "Welcome to the mercufee API");