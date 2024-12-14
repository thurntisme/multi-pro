<?php
include_once DIR . '/functions/generate-player.php';
global $api_url, $payload, $method;
$slug = str_replace('football-manager/', '', $api_url);
switch ($slug) {
    case 'inventory/item':
        if ($method === 'GET') {
            sendResponse("success", 200, "Item retrieved successfully", ["item_uuid" => $_GET['item_uuid'] ?? null]);
        } elseif ($method === 'POST') {
            $item_uuid = $payload['item_uuid'] ?? null;
            $item_type = $payload['item_type'] ?? null;
            $item_slug = $payload['item_slug'] ?? null;
            getPlayerByInventory($item_uuid, $item_type, $item_slug);
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

function getPlayerByInventory($item_uuid, $item_type, $item_slug): void
{
    if (!empty($item_type)) {
        if ($item_type === 'player') {
            $playerData = [];
            $players = filterPlayers($item_slug, $playerData);
            sendResponse("success", 201, "Player created successfully. Player card: " . $item_slug, $players);
            // after open item, user have 2 options to choose: receive the player or get 80% price from player's market value
        }
    }
    sendResponse("success", 201, "Item not found.");
}