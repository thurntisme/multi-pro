<?php

global $api_url, $payload, $method;

$slug = str_replace('football-manager/', '', $api_url);
switch ($slug) {
    case 'inventory/item':
        if ($method === 'GET') {
            sendResponse("success", 200, "Item retrieved successfully", ["item_uuid" => $_GET['item_uuid'] ?? null]);
        } elseif ($method === 'POST') {
            $item_uuid = $payload['item_uuid'] ?? null;
            sendResponse("success", 201, "Item created successfully. Item UUID: $item_uuid");
        } elseif ($method === 'PUT') {
            $item_uuid = $payload['item_uuid'] ?? null;
            sendResponse("success", 200, "Item updated successfully. Item UUID: $item_uuid");
        } elseif ($method === 'DELETE') {
            $item_uuid = $payload['item_uuid'] ?? null;
            sendResponse("success", 200, "Item deleted successfully. Item UUID: $item_uuid");
        } else {
            sendResponse("error", 405, "Method Not Allowed");
        }
        break;

    default:
        sendResponse("error", 404, "Endpoint not found");
        break;
}
