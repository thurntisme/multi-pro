<?php

require_once DIR . '/controllers/FootballTeamController.php';
require_once DIR . '/services/FootballPlayerService.php';
require_once DIR . '/functions/generate-player.php';

class FootballPlayerController
{
    private $user_id;
    private $pdo;
    private $footballTeamController;
    private $footballPlayerService;

    public function __construct()
    {
        global $user_id;
        global $pdo;
        $this->user_id = $user_id;
        $this->pdo = $pdo;
        $this->footballPlayerService = new FootballPlayerService($pdo);
        $this->footballTeamController = new FootballTeamController($pdo);
    }

    // Handle creating a new code
    public function createPlayer($uuid)
    {
        $player = getPlayerJsonByUuid($uuid);
        $team = $this->footballTeamController->getMyTeam();

        if ($player) {
            return $this->footballPlayerService->createPlayer($team['id'], $player);
        } else {
            return null;
        }
    }

    public function initializeTeams($teams)
    {
        $this->footballPlayerService->initializeTeams($teams);
    }

    // Handle updating a code
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

    // Handle deleting a code
    public function deleteCode()
    {
        $id = $_POST['post_id'] ?? null;
        if ($id) {
            $rowsAffected = $this->footballPlayerService->deleteCode($id);
            if ($rowsAffected) {
                $_SESSION['message_type'] = 'success';
                $_SESSION['message'] = "Code deleted successfully.";
            } else {
                $_SESSION['message_type'] = 'danger';
                $_SESSION['message'] = "Failed to delete code.";
            }
        } else {
            $_SESSION['message_type'] = 'danger';
            $_SESSION['message'] = "Failed to delete code.";
        }

        header("Location: " . home_url("code"));
        exit;
    }

    // Get all teams
    public function listTeams()
    {
        return $this->footballPlayerService->getAllTeams();
    }

    public function getMyTeam()
    {
        return $this->footballPlayerService->getTeamByUserId();
    }

    // Get all favorite players
    public function getFavoritePlayerSQL($queryType = "result")
    {
        // Pagination parameters
        $itemsPerPage = 10; // Number of results per page
        $page = isset($_GET['page']) ? (int) $_GET['page'] : 1; // Current page number
        $offset = ($page - 1) * $itemsPerPage; // Offset for LIMIT clause

        // Filter last updated
        $lastUpdated = isset($_GET['last_updated']) ? $_GET['last_updated'] : '';

        $selectSql = $queryType === "result" ? "SELECT * FROM football_favorite_player" : "SELECT COUNT(*) FROM football_favorite_player";
        $sql = $selectSql . " WHERE manager_id = $this->user_id ";

        // Sorting parameters (optional)
        $sortColumn = isset($_GET['sort']) ? $_GET['sort'] : 'updated_at'; // Default sort by updated_at
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

    // Handle listing all transfers
    public function listFavoritePlayers()
    {
        return [
            'list' => $this->getFavoritePlayerSQL("result"),
            'count' => $this->getFavoritePlayerSQL("count"),
        ];
    }

    // Handle viewing a single player
    public function viewPlayer($id)
    {
        if ($id) {
            $player = $this->footballPlayerService->getPlayerById($id);
            if ($player) {
                $player_uuid = $player['player_uuid'];
                $playerJsonData = getPlayerJsonByUuid($player_uuid);
                return array_merge($playerJsonData, $player);
            } else {
                return null;
            }
        }

        return null;
    }
}
