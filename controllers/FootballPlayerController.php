<?php

require_once DIR . '/controllers/FootballTeamController.php';
require_once DIR . '/services/FootballPlayerService.php';
require_once DIR . '/functions/generate-player.php';

class FootballPlayerController
{
    private $user_id;
    private $pdo;
    private $footballPlayerService;

    public function __construct()
    {
        global $user_id;
        global $pdo;
        $this->user_id = $user_id;
        $this->pdo = $pdo;
        $this->footballPlayerService = new FootballPlayerService($pdo);
    }

    // Handle creating a new player
    public function createPlayer($teamId, $playerUuid, $status = '')
    {
        if ($playerUuid) {
            return $this->footballPlayerService->createPlayer($teamId, $playerUuid, $status);
        } else {
            return null;
        }
    }

    public function getMyTeam()
    {
        return $this->footballPlayerService->getTeamByUserId();
    }

    // Handle deleting a code

    public function updateCode()
    {
        $id = $_POST['code_id'] ?? '';
        $title = $_POST['title'] ?? '';
        $content = $_POST['content'] ?? '';
        $tags = $_POST['tags'] ?? '';
        $url = $_POST['url'] ?? '';

        if ($id && $title) {
            $rowsAffected = $this->footballPlayerService->updateCode($id, $title, $content, $tags, $url);
            if ($rowsAffected) {
                $_SESSION['message_type'] = 'success';
                $_SESSION['message'] = "Code updated successfully.";
            } else {
                $_SESSION['message_type'] = 'danger';
                $_SESSION['message'] = "Failed to update code.";
            }
        } else {
            $_SESSION['message_type'] = 'danger';
            $_SESSION['message'] = "Code ID and service name are required.";
        }

        header("Location: " . home_url("code/edit") . '?id=' . $id);
        exit;
    }

    // Get all teams

    public function deletePlayer($teamId, $playerId, $playerName)
    {
        if ($playerId) {
            $rowsAffected = $this->footballPlayerService->deletePlayer($playerId);
            if ($rowsAffected) {
                $_SESSION['message_type'] = 'success';
                $_SESSION['message'] = $playerName . "has been deleted successfully.";
            } else {
                $_SESSION['message_type'] = 'danger';
                $_SESSION['message'] = "Failed to delete $playerName from your list.";
            }
        } else {
            $_SESSION['message_type'] = 'danger';
            $_SESSION['message'] = "Failed to delete $playerName from your list.";
        }

        header("Location: " . $_SERVER['REQUEST_URI']);
        exit;
    }

    public function listTeams()
    {
        return $this->footballPlayerService->getAllTeams();
    }

    public function createFavoritePlayer($playerUuid, $playerName)
    {
        if ($playerUuid) {
            $this->footballPlayerService->createFavoritePlayer($playerUuid);
            $_SESSION['message_type'] = 'success';
            $_SESSION['message'] = $playerName . " has been added successfully to your favorites list.";
        } else {
            $_SESSION['message_type'] = 'danger';
            $_SESSION['message'] = "Failed to add player to your favorites list.";
        }

        header("Location: " . $_SERVER['REQUEST_URI']);
        exit;
    }

    public function removeFavoritePlayer($playerUuid, $playerName)
    {
        if ($playerUuid) {
            $this->footballPlayerService->removeFavoritePlayer($playerUuid);
            $_SESSION['message_type'] = 'success';
            $_SESSION['message'] = $playerName . " has been removed successfully to your favorites list.";
        } else {
            $_SESSION['message_type'] = 'danger';
            $_SESSION['message'] = "Failed to remove player to your favorites list.";
        }

        header("Location: " . $_SERVER['REQUEST_URI']);
        exit;
    }

    // Get all favorite players

    public function listFavoritePlayers(): array
    {
        $resources = array_map(function ($player) {
            return getPlayerJsonByUuid($player['player_uuid']);
        }, $this->getFavoritePlayerSQL("result"));
        return [
            'resources' => $resources,
            'count' => $this->getFavoritePlayerSQL("count"),
        ];
    }

    // Handle listing all transfers

    public function getFavoritePlayerSQL($queryType = "result")
    {
        // Pagination parameters
        $itemsPerPage = 10; // Number of results per page
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Current page number
        $offset = ($page - 1) * $itemsPerPage; // Offset for LIMIT clause

        $selectSql = $queryType === "result" ? "SELECT * FROM football_favorite_player" : "SELECT COUNT(*) FROM football_favorite_player";
        $sql = $selectSql . " WHERE manager_id = $this->user_id ";

        // Sorting parameters (optional)
        $sortColumn = isset($_GET['sort']) ? $_GET['sort'] : 'created_at'; // Default sort by updated_at
        $sortOrder = isset($_GET['order']) && in_array(strtoupper($_GET['order']), ['ASC', 'DESC']) ? strtoupper($_GET['order']) : 'DESC'; // Default to DESC

        // Add the ORDER BY clause dynamically
        $sql .= " ORDER BY $sortColumn $sortOrder";

        if ($queryType === "result") {
            // Add pagination (LIMIT and OFFSET)
            $sql .= " LIMIT $itemsPerPage OFFSET $offset";
        }

        // Prepare the query
        $stmt = $this->pdo->prepare($sql);

        // Execute the query
        $stmt->execute();
        return $queryType === "result" ? $stmt->fetchAll(PDO::FETCH_ASSOC) : $stmt->fetchColumn();
    }

    // Handle viewing a single player

    public function viewPlayer($id)
    {
        if ($id) {
            $player = $this->footballPlayerService->getPlayerById($id);
            if ($player) {
                $player_uuid = $player['player_uuid'];
                $playerJsonData = getPlayerJsonByUuid($player_uuid);
                return array_merge($playerJsonData, $player, ['score' => 5]);
            } else {
                return null;
            }
        }

        return null;
    }

    public function deletePlayerJson($player_uuid, $playerName)
    {
        if (deletePlayerJson($player_uuid)) {
            $_SESSION['message_type'] = 'success';
            $_SESSION['message'] = $playerName . " has been deleted successfully.";
        } else {
            $_SESSION['message_type'] = 'danger';
            $_SESSION['message'] = "Failed to delete player.";
        }

        header("Location: " . $_SERVER['REQUEST_URI']);
        exit;
    }
}
