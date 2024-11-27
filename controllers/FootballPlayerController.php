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
        $data = getPlayerJsonByUuid($uuid);
        // Prepare player data for insertion
        $player = [
            'player_uuid' => $data['uuid'],
            'age' => $data['age'],
            'injury' => $data['injury'],
            'recovery_time' => $data['recovery_time'],
            'ability' => $data['ability'],
            'salary' => $data['contract_wage'],
            'market_value' => $data['market_value'],
            'passing' => $data['attributes']['technical']['passing'],
            'dribbling' => $data['attributes']['technical']['dribbling'],
            'first_touch' => $data['attributes']['technical']['first_touch'],
            'crossing' => $data['attributes']['technical']['crossing'],
            'finishing' => $data['attributes']['technical']['finishing'],
            'long_shots' => $data['attributes']['technical']['long_shots'],
            'free_kick_accuracy' => $data['attributes']['technical']['free_kick_accuracy'],
            'heading' => $data['attributes']['technical']['heading'],
            'tackling' => $data['attributes']['technical']['tackling'],
            'handling' => $data['attributes']['technical']['handling'],
            'marking' => $data['attributes']['technical']['marking'],
            'decision' => $data['attributes']['mental']['decision'],
            'vision' => $data['attributes']['mental']['vision'],
            'leadership' => $data['attributes']['mental']['leadership'],
            'work_rate' => $data['attributes']['mental']['work_rate'],
            'positioning' => $data['attributes']['mental']['positioning'],
            'composure' => $data['attributes']['mental']['composure'],
            'aggression' => $data['attributes']['mental']['aggression'],
            'anticipation' => $data['attributes']['mental']['anticipation'],
            'concentration' => $data['attributes']['mental']['concentration'],
            'off_the_ball' => $data['attributes']['mental']['off_the_ball'],
            'flair' => $data['attributes']['mental']['flair'],
            'pace' => $data['attributes']['physical']['pace'],
            'strength' => $data['attributes']['physical']['strength'],
            'stamina' => $data['attributes']['physical']['stamina'],
            'agility' => $data['attributes']['physical']['agility'],
            'balance' => $data['attributes']['physical']['balance'],
            'jumping_reach' => $data['attributes']['physical']['jumping_reach'],
            'natural_fitness' => $data['attributes']['physical']['natural_fitness']
        ];
        $team = $this->footballTeamController->getMyTeam();

        if ($player) {
            return $this->footballPlayerService->createPlayer($team['id'], $player);
        } else {
            return null;
        }
    }

    public function getMyTeam()
    {
        return $this->footballPlayerService->getTeamByUserId();
    }

    // Handle updating a code

    public function initializeTeams($teams)
    {
        $this->footballPlayerService->initializeTeams($teams);
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

    public function listTeams()
    {
        return $this->footballPlayerService->getAllTeams();
    }

    // Get all favorite players

    public function listFavoritePlayers()
    {
        return [
            'list' => $this->getFavoritePlayerSQL("result"),
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
