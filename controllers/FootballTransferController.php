<?php

require_once DIR . '/services/FootballTransferService.php';
require_once DIR . '/controllers/FootballPlayerController.php';
require_once DIR . '/controllers/FootballTeamController.php';
require_once DIR . '/functions/generate-player.php';

class FootballTransferController
{
    private $user_id;
    private $pdo;
    private $footballPlayerController;
    private $footballTeamController;
    private $footballTransferService;

    public function __construct()
    {
        global $user_id;
        global $pdo;
        $this->user_id = $user_id;
        $this->pdo = $pdo;
        $this->footballPlayerController = new FootballPlayerController();
        $this->footballTeamController = new FootballTeamController();
        $this->footballTransferService = new FootballTransferService($pdo);
    }

    // Handle creating a new code

    public function createTransferBuyPlayer()
    {
        $this->createTransfer('buy');
    }

    public function createTransfer($type)
    {
        if ($type) {
            if ($type == 'buy') {
                $team = $this->footballTeamController->getMyTeam();
                $playerId = $this->footballPlayerController->createPlayer($team['id'], $_POST['player_uuid'], $type);
                if ($playerId) {
                    $player = $this->footballPlayerController->viewPlayer($playerId);
                    $player_id = $player['id'];
                    $market_value = $player['market_value'] ?? 0;
                    $amount = $market_value + ($market_value * 0.05 / 100);
                    try {
                        $this->footballTransferService->createTransfer($type, $player_id, (int)$amount);
                        $this->footballTeamController->updateBudget((int)$amount);
                    } catch (Throwable $th) {
                        $_SESSION['message_type'] = 'danger';
                        $_SESSION['message'] = "Failed to create transfer. " . $th->getMessage();
                        header("Location: " . $_SERVER['REQUEST_URI']);
                        exit;
                    }
                }
            }
            $_SESSION['message_type'] = 'success';
            $_SESSION['message'] = "Your transfer is processing";
            header("Location: " . home_url("football-manager/transfer/buy-list"));
        } else {
            $_SESSION['message_type'] = 'danger';
            $_SESSION['message'] = "Failed to create transfer";
            header("Location: " . $_SERVER['REQUEST_URI']);
        }

        exit;
    }

    public function createTransferSellPlayer()
    {
        $this->createTransfer('sell');
    }

    public function initializeTeams($teams)
    {
        $this->footballTransferService->initializeTeams($teams);
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
            $rowsAffected = $this->footballTransferService->updateCode($id, $title, $content, $tags, $url);
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
            $rowsAffected = $this->footballTransferService->deleteCode($id);
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

    // Get all transfers

    public function listTransferPlayers($transferType)
    {
        
        $list = array_map(function ($player) {
            $player_data = $this->footballPlayerController->viewPlayer($player['player_id']);
            return array_merge($player_data, $player);
        }, $this->getTransferSQL("result", $transferType));
        return [
            'list' => $list,
            'count' => $this->getTransferSQL("count", $transferType),
        ];
    }

    // Handle listing all transfers

    public function getTransferSQL($queryType = "result", $transferType = '')
    {
        // Pagination parameters
        $itemsPerPage = 10; // Number of results per page
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Current page number
        $offset = ($page - 1) * $itemsPerPage; // Offset for LIMIT clause

        $selectSql = $queryType === "result" ? "SELECT * FROM football_transfer" : "SELECT COUNT(*) FROM football_transfer";
        $sql = $selectSql . " WHERE manager_id = $this->user_id ";

        if ($transferType !== '') {
            $sql .= " AND type = :type";
        }

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

        if ($transferType !== '') {
            $stmt->bindParam(':type', $transferType, PDO::PARAM_STR);
        }

        // Execute the query
        $stmt->execute();
        return $queryType === "result" ? $stmt->fetchAll(PDO::FETCH_ASSOC) : $stmt->fetchColumn();
    }
}
