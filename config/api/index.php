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

// Decode payload based on the request method and content type
if (in_array($method, ['POST', 'PUT', 'DELETE'])) {
    $contentType = $_SERVER['CONTENT_TYPE'] ?? '';

    if (stripos($contentType, 'application/json') !== false) {
        // Handle JSON payload
        $rawPayload = file_get_contents('php://input');
        $payload = json_decode($rawPayload, true);

        // Check if JSON decoding failed
        if (json_last_error() !== JSON_ERROR_NONE) {
            sendResponse("error", 400, "Invalid JSON payload");
        }
    } elseif (stripos($contentType, 'multipart/form-data') !== false) {
        // Handle form-data payload
        $payload = $_POST; // Normal form fields
        $payload['file'] = $_FILES['file'] ?? null; // Uploaded file(s), if any

        // Check if file data exists
        if ($_FILES && !isset($_FILES['file'])) {
            sendResponse("error", 400, "No file provided in form data");
        }
    } else {
        // Unsupported content type
        sendResponse("error", 415, "Unsupported Media Type");
    }
}

if (str_starts_with($api_url, 'football-manager')) {
    include_once 'football-manager.php';
}
if (str_starts_with($api_url, 'file-manager')) {
    include_once 'file-manager.php';
}
sendResponse("success", 200, "Welcome to the mercufee API.");
