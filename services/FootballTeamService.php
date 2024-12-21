<?php
require_once DIR . '/controllers/FootballPlayerController.php';
require_once DIR . '/services/FootballTransferService.php';

class FootballTeamService
{
    private $pdo;
    private $user_id;
    private $footballPlayerController;
    private $footballTransferService;

    public function __construct($pdo)
    {
        global $user_id;
        $this->pdo = $pdo;
        $this->user_id = $user_id;
        $this->footballPlayerController = new FootballPlayerController();
        $this->footballTransferService = new FootballTransferService($this->pdo);
    }

    public function initializeTeams(array $teams, $systemUserId)
    {
        $sqlCheck = "SELECT COUNT(*) FROM football_team";
        $stmtCheck = $this->pdo->query($sqlCheck);
        $count = $stmtCheck->fetchColumn();

        if ($count > 0) {
            // Teams already initialized
            return false;
        }

        $this->pdo->beginTransaction();
        try {
            $sqlInsert = "INSERT INTO football_team (team_name, league_position, manager_id) 
                          VALUES (:team_name, :league_position, :manager_id)";
            $stmtInsert = $this->pdo->prepare($sqlInsert);

            foreach ($teams as $index => $team) {
                $stmtInsert->execute([
                    ':team_name' => $team['name'],
                    ':league_position' => $index + 1,
                    ':manager_id' => $systemUserId,
                ]);
            }

            $this->pdo->commit();
            return true;
        } catch (Exception $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }

    public function initializeTeamPlayers($teamId, $players)
    {
        if (empty($players)) {
            throw new Exception("Player list cannot be empty.");
        }

        $this->pdo->beginTransaction();
        try {
            foreach ($players as $player) {
                $this->footballPlayerController->createPlayer($teamId, $player['uuid']);
            }

            $this->pdo->commit();
            return true;
        } catch (Exception $e) {
            $this->pdo->rollBack();
            throw new Exception("Failed to initialize team players: " . $e->getMessage());
        }
    }

    // Create a new code
    public function createTeam($team_name, $system_user_id, $league_position = 1, $formation = '442')
    {
        $sql = "INSERT INTO football_team (team_name, manager_id, formation, league_position) VALUES (:team_name, :manager_id, :formation, :league_position)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':team_name' => $team_name, ':manager_id' => $system_user_id, ':formation' => $formation, ':league_position' => $league_position]);

        return $this->pdo->lastInsertId();
    }

    public function updateBudget($amount)
    {
        $team = $this->getMyTeamData();
        $remainingBudget = $team['budget'] - $amount;
        $sql = "UPDATE football_team SET budget = :budget, updated_at = CURRENT_TIMESTAMP WHERE manager_id = :manager_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':budget' => $remainingBudget, ':manager_id' => $this->user_id]);

        return $stmt->rowCount();
    }

    // Delete a code

    public function getMyTeamData()
    {
        $sql = "SELECT * FROM football_team WHERE manager_id = :manager_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':manager_id' => $this->user_id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getAllTeams()
    {
        $sql = "SELECT * FROM football_team ORDER BY league_position ASC ";
        $stmt = $this->pdo->query($sql);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTeamByUserId()
    {
        $team = $this->getMyTeamData();
        if (!empty($team)) {
            $players = array_map(function ($player) {
                return $this->footballPlayerController->viewPlayer($player['id']);
            }, $this->getTeamPlayers($team['id'], 'club'));
            $team['players'] = $players;
        }
        return $team;
    }

    public function getTeamPlayers($team_id, $type = '')
    {
        $query = "";
        $params = [':team_id' => $team_id];

        if ($type === 'club') {
            $query = "AND joining_date <= CURRENT_TIMESTAMP 
                  AND contract_end_date > CURRENT_TIMESTAMP 
                  AND status = 'club'";
        } elseif ($type === 'players') {
            $query = "AND joining_date < CURRENT_TIMESTAMP 
                  AND contract_end_date <= CURRENT_TIMESTAMP 
                  AND status = 'players'";
        }

        $sql = "SELECT * FROM football_player WHERE team_id = :team_id $query ORDER BY starting_order ASC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTeamPlayersByUserId()
    {
        $team = $this->getMyTeamData();
        if (!empty($team)) {
            $players = array_map(function ($player) {
                return $this->footballPlayerController->viewPlayer($player['id']);
            }, $this->getTeamPlayers($team['id'], 'players'));
            $team['players'] = $players;
        }
        return $team;
    }

    public function assignPlayerToTeam($teamId, $playerId)
    {
        $playerData = $this->footballPlayerController->viewPlayer($playerId);
        $teamData = $this->getTeamById($teamId);
        $starting_order = !empty($teamData['players']) ? count($teamData['players']) : 99;
        $joining_date = date('Y-m-d H:i:s');
        $contract_end_date = date('Y-m-d H:i:s', strtotime('+' . ($playerData['contract_end'] ?? 7) . ' days'));
        $sql = "UPDATE football_player SET joining_date = :joining_date, contract_end_date = :contract_end_date, status = :status, starting_order = :starting_order, updated_at = CURRENT_TIMESTAMP WHERE team_id = :team_id AND id = :player_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':joining_date' => $joining_date, ':contract_end_date' => $contract_end_date, ':status' => 'club', ':starting_order' => $starting_order, ':team_id' => $teamId, ':player_id' => $playerId]);

        return $stmt->rowCount();
    }

    public function getTeamById($teamId)
    {
        $team = $this->getTeamData($teamId);

        if (!empty($team)) {
            $players = array_map(function ($player) {
                return $this->footballPlayerController->viewPlayer($player['id']);
            }, $this->getTeamPlayers($teamId, 'club'));
            $team['players'] = $players;
        }
        return $team;
    }

    public function getTeamData($teamId)
    {
        $sql = "SELECT * FROM football_team WHERE id = :team_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':team_id' => $teamId]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function movePlayerToTeam($teamId, $playerId, $transferId)
    {
        $sql = "UPDATE football_player SET status = :status, updated_at = CURRENT_TIMESTAMP WHERE team_id = :team_id AND id = :player_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':status' => 'players', ':team_id' => $teamId, ':player_id' => $playerId]);

        $sql = "DELETE FROM football_transfer WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $transferId]);

        return $stmt->rowCount();
    }

    public function updateMyClubFormation($formation)
    {
        $sql = "UPDATE football_team SET formation = :formation, updated_at = CURRENT_TIMESTAMP WHERE manager_id = :manager_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':formation' => $formation, ':manager_id' => $this->user_id]);

        return $stmt->rowCount();
    }

    function updateMyClubPlayers($index, $players)
    {
        $myTeam = $this->getMyTeamData();
        try {
            // Start transaction
            $this->pdo->beginTransaction();

            // Prepare the SQL statement
            $stmt = $this->pdo->prepare("UPDATE football_player SET starting_order = :starting_order, updated_at = CURRENT_TIMESTAMP WHERE team_id = :team_id AND player_uuid = :player_uuid");

            // Execute the update for each player
            foreach ($players as $index => $player) {
                $stmt->execute([
                    ':starting_order' => $index,
                    ':team_id' => $myTeam['id'],
                    ':player_uuid' => $player['uuid'],
                ]);
            }

            // Commit the transaction
            $this->pdo->commit();
        } catch (Exception $e) {
            // Roll back if something fails
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }
        }
    }

    function updateMyClubPlayer($player)
    {
        $sql = "UPDATE football_player SET starting_order = :starting_order, updated_at = CURRENT_TIMESTAMP WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':starting_order' => $player['new_starting_order'],
            ':id' => $player['id']
        ]);

        return $stmt->rowCount();
    }

    public function getRefundFromPlayer($playerId, $transferId)
    {
        $team = $this->getMyTeamData();
        $playerData = $this->footballPlayerController->viewPlayer($playerId);
        $transferData = $this->footballTransferService->getTransferById($transferId);
        $budget = $team['budget'] + $transferData['amount'];

        try {
            $teamSql = "UPDATE football_team SET budget = :budget, updated_at = CURRENT_TIMESTAMP WHERE manager_id = :manager_id";
            $teamStmt = $this->pdo->prepare($teamSql);
            $teamStmt->execute([':budget' => $budget, ':manager_id' => $this->user_id]);

            $sql = "DELETE FROM football_transfer WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':id' => $transferId]);

            $playerSql = "DELETE FROM football_player WHERE id = :id";
            $playerStmt = $this->pdo->prepare($playerSql);
            $playerStmt->execute([':id' => $playerId]);

            if ($teamStmt->rowCount()) {
                return $playerData['market_value'];
            } else {
                return 0;
            }
        } catch (Exception $e) {
            return 0;
        }
    }
}
