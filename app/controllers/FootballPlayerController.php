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

    function getItemByUuid($uuid)
    {
        global $playerItems;
        $filteredItems = array_filter($playerItems, function ($item) use ($uuid) {
            return $item['uuid'] === $uuid;
        });

        return !empty($filteredItems) ? array_values($filteredItems)[0] : null;
    }

    function getMyTeamData()
    {
        $sql = "SELECT * FROM football_team WHERE manager_id = :manager_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':manager_id' => $this->user_id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function upgradePlayer($item_uuid, $player_uuid)
    {
        $teamData = $this->getMyTeamData();
        $item = $this->getItemByUuid($item_uuid);
        $is_success = false;

        // Ensure item and player exist and budget is sufficient
        if ($item && $player_uuid && $teamData['budget'] >= $item['price']) {
            $updates = [];
            $params = [
                ':player_uuid' => $player_uuid,
                ':team_id' => $teamData['id']
            ];

            // Determine the update query and parameters based on item slug
            switch ($item['slug']) {
                case 'stamina':
                    $updates['query'] = "UPDATE football_player SET player_stamina = :player_stamina, updated_at = CURRENT_TIMESTAMP WHERE player_uuid = :player_uuid AND team_id = :team_id";
                    $updates['params'] = [':player_stamina' => 100];
                    break;

                case 'form':
                    $updates['query'] = "UPDATE football_player SET player_form = :player_form, updated_at = CURRENT_TIMESTAMP WHERE player_uuid = :player_uuid AND team_id = :team_id";
                    $updates['params'] = [':player_form' => 5];
                    break;

                case 'injury':
                    $updates['query'] = "UPDATE football_player SET injury_end_date = :injury_end_date, updated_at = CURRENT_TIMESTAMP WHERE player_uuid = :player_uuid AND team_id = :team_id";
                    $updates['params'] = [':injury_end_date' => null];
                    break;

                default:
                    return $is_success;
            }

            // Execute the player update
            $playerStmt = $this->pdo->prepare($updates['query']);
            $playerStmt->execute(array_merge($params, $updates['params']));

            if ($playerStmt->rowCount()) {
                $newBudget = $teamData['budget'] - $item['price'];
                $updateBudgetSql = "UPDATE football_team SET budget = :budget WHERE id = :team_id";
                $updateBudgetStmt = $this->pdo->prepare($updateBudgetSql);
                $updateBudgetStmt->execute([':budget' => $newBudget, ':team_id' => $teamData['id']]);

                $is_success = true;
            }
        }

        return [
            'success' => $is_success,
            'item_slug' => $item['slug'] ?? '',
            'budget' => $newBudget ? formatCurrency($newBudget) : ''
        ];
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
