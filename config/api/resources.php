<?php
require_once DIR . '/controllers/BlogController.php';
$blogController = new BlogController();
global $api_url, $payload, $method, $user_id;
$slug = str_replace('resources', '', $api_url);
switch ($slug) {
    case '':
        if ($method === 'GET') {
            $data = $blogController->apiListBlogs();
            sendResponse("success", 200, "Item retrieved successfully", $data);
        } else {
            sendResponse("error", 405, "Method Not Allowed");
        }
        break;

    default:
        sendResponse("error", 404, "Endpoint not found");
        break;
}
