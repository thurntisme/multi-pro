<?php
include_once DIR . '/functions/generate-player.php';
require_once DIR . '/controllers/FootballTeamController.php';
require_once DIR . '/controllers/FootballMatchController.php';
global $api_url, $payload, $method, $user_id;
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

    case 'club/analysis':
        if ($method === 'POST') {
            $players = $payload['players'] ?? null;
            $result = getClubAnalysis($players);
        } else {
            sendResponse("error", 405, "Method Not Allowed");
        }
        break;

    case 'my-club':
        if ($method === 'POST') {
            $result = getMyClubData();
        } else {
            sendResponse("error", 405, "Method Not Allowed");
        }
        break;

    case 'match/result':
        if ($method === 'POST') {
            $match_uuid = $payload['match_uuid'] ?? null;
            $result = $payload['result'] ?? null;
            saveMatchResult($match_uuid, $result);
        } else {
            sendResponse("error", 405, "Method Not Allowed");
        }
        break;

    case 'match/record':
        if ($method === 'POST') {
            $match_uuid = $payload['match_uuid'] ?? null;
            recordMatch($match_uuid, $user_id);
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
        if ($item_type === 'new-player') {
            $playerData = [];
            $players = generateRandomPlayers($item_slug, $playerData);
            try {
                exportPlayersToJson($players);
                sendResponse("success", 201, "Player created successfully.", $players[0]);
            } catch (Exception $e) {
                sendResponse("error", 405, "Failed to create a new player.");
                $_SESSION['message_type'] = 'danger';
            }
            // after open item, user have 2 options to choose: receive the player or get 80% price from player's market value
        }
    }
    sendResponse("success", 201, "Item not found.");
}

function getClubAnalysis($players)
{
    if ($players) {
        $result = array_map(function ($player) {
            $player['new_ability'] = getPlayerAbility($player['position_in_match'], $player['attributes']);
            $player['bg_color'] = getBackgroundColor($player['new_ability']);
            $player['position_color'] = getPositionColor($player['position_in_match']);
            return $player;
        }, $players);
        sendResponse("success", 201, "Club data created successfully.", $result);
    } else {
        sendResponse("error", 405, "Failed to analysis club data.");
    }
}

function getMyClubData()
{
    global $user_id;
    try {
        $footballTeamController = new FootballTeamController();
        $teamData = $footballTeamController->getMyTeam();
        if ($teamData) {
            $result['formation'] = $teamData['formation'];
            $result['players'] = $teamData['players'];
            sendResponse("success", 201, "Get Club data successfully.", $result);
        } else {
            sendResponse("error", 405, "Failed to get club data. " . $user_id);
        }
    } catch (Throwable $th) {
        sendResponse("error", 500, "Failed to get club data." . $user_id);
    }
}

function saveMatchResult($match_uuid, $result): void
{
    try {
        $footballMatchController = new FootballMatchController();
        $matchData = $footballMatchController->saveMatchResult($match_uuid, $result);
        if ($matchData) {
            sendResponse("success", 201, "Save match data successfully.");
        } else {
            sendResponse("error", 405, "Failed to save match data.");
        }
    } catch (Throwable $th) {
        sendResponse("error", 500, "Failed to save match data.");
    }
}

function recordMatch($match_uuid, $user_id): void
{
    try {
        $footballMatchController = new FootballMatchController();
        $matchData = $footballMatchController->recordMatch($match_uuid);
        if ($matchData) {
            sendResponse("success", 201, "Record match data successfully.");
        } else {
            sendResponse("error", 405, "Failed to record match data.");
        }
    } catch (Throwable $th) {
        sendResponse("error", 500, "Failed to record match data. " . $th->getMessage());
    }
}
