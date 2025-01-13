<?php
require_once DIR . '/controllers/FootballPlayerController.php';
require_once DIR . '/services/FootballTransferService.php';

class FootballTeamService
{
    private $pdo;
    private $user_id;
    private $footballPlayerController;
    private $footballTransferService;
    private $systemController;

    public function __construct($pdo)
    {
        global $user_id;
        global $systemController;
        $this->pdo = $pdo;
        $this->user_id = $user_id;
        $this->footballPlayerController = new FootballPlayerController();
        $this->footballTransferService = new FootballTransferService($this->pdo);
        $this->systemController = $systemController;
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
    public function createTeam($team_name, $manager_id)
    {
        $sql = "INSERT INTO football_team (team_name, manager_id) VALUES (:team_name, :manager_id)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':team_name' => $team_name, ':manager_id' => $manager_id]);

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
        $sql = "SELECT * FROM football_team";
        $stmt = $this->pdo->query($sql);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTeamByUserId()
    {
        $team = $this->getMyTeamData();
        if (!empty($team)) {
            $players = array_map(function ($player) {
                $playerData = $this->footballPlayerController->viewPlayer($player['id']);
                $playerData['remaining_contract_date'] = $this->calRemainingContractDate($playerData['contract_end_date']);
                $playerData['market_value'] = formatCurrency($playerData['market_value']);
                $playerData['contract_wage'] = formatCurrency($playerData['contract_wage']);
                $playerData['contract_end_date'] = $this->systemController->convertDate($playerData['contract_end_date']);
                $playerData['is_expired'] = $playerData['remaining_contract_date'] < 0;
                $playerData['level'] = $this->getLevelDetails($playerData['level']);
                return $playerData;
            }, $this->getTeamPlayers($team['id'], 'club'));
            $team['players'] = $players;
        }
        return $team;
    }

    public function calRemainingContractDate($date): int
    {
        // Convert both $now and $date to DateTime objects
        try {
            $nowDateTime = $this->systemController->getDateTime('now');
            $convertedDate = $this->systemController->getDateTime($date);
        } catch (Exception $e) {
            throw new InvalidArgumentException("Invalid date format provided: " . $e->getMessage());
        }

        $nowDateTime->setTime(0, 0);
        $convertedDate->setTime(0, 0);

        // Calculate the difference
        $diff = $nowDateTime->diff($convertedDate);

        // Determine the sign of the difference using $diff->invert
        $days = (int)$diff->format('%a');
        if ($diff->invert === 1) {
            $days = -$days; // Make the difference negative if the date is in the future
        }

        return $days;
    }

    function getLevelDetails($points): array
    {
        $pointsPerLevel = 100; // Points required for one level
        $level = floor($points / $pointsPerLevel); // Current level
        $progress = $points % $pointsPerLevel; // Points towards the next level
        $percentageToNextLevel = ($progress / $pointsPerLevel) * 100; // Progress percentage

        return [
            'num' => $level,
            'percentageToNextLevel' => number_format($percentageToNextLevel, 2)
        ];
    }

    public function getTeamPlayers($team_id, $type = '')
    {
        $query = "";
        $params = [':team_id' => $team_id];

        if ($type === 'club') {
            $query = "AND joining_date <= CURRENT_TIMESTAMP 
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
        try {
            // Fetch player and team data
            $playerData = $this->footballPlayerController->viewPlayer($playerId);
            $teamData = $this->getTeamById($teamId);

            // Validate data
            if (empty($playerData)) {
                throw new Exception("Player data not found for ID: $playerId");
            }
            if (empty($teamData)) {
                throw new Exception("Team data not found for ID: $teamId");
            }

            // Calculate starting order, joining date, and contract end date
            $startingOrder = $this->calculateStartingOrder($teamData['players']);
            $joiningDate = date('Y-m-d H:i:s');
            $contractEndDate = $this->calculateContractEndDate($playerData['contract_end'] ?? 7);

            // Update player data in the database
            $this->updatePlayerTeamData($teamId, $playerId, $joiningDate, $contractEndDate, $startingOrder);

            return true;
        } catch (Exception $e) {
            throw new Exception("Failed to assign player $playerId to team $teamId: " . $e->getMessage());
        }
    }
    
    private function calculateStartingOrder($players)
    {
        return !empty($players) ? count($players) : 99;
    }

    private function calculateContractEndDate($contractDurationDays)
    {
        return date('Y-m-d H:i:s', strtotime("+$contractDurationDays days"));
    }

    private function updatePlayerTeamData($teamId, $playerId, $joiningDate, $contractEndDate, $startingOrder)
    {
        $sql = "UPDATE football_player 
                SET joining_date = :joining_date, 
                    contract_end_date = :contract_end_date, 
                    status = :status, 
                    starting_order = :starting_order, 
                    updated_at = CURRENT_TIMESTAMP 
                WHERE team_id = :team_id AND id = :player_id";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([
            ':joining_date' => $joiningDate,
            ':contract_end_date' => $contractEndDate,
            ':status' => 'club',
            ':starting_order' => $startingOrder,
            ':team_id' => $teamId,
            ':player_id' => $playerId
        ]);

        if ($stmt->rowCount() === 0) {
            throw new Exception("Failed to update player $playerId for team $teamId.");
        }
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

    public function moveAllPlayersToTeam()
    {
        try {
            // Get the current team data for the user
            $team = $this->getMyTeamData();

            if (!empty($team)) {
                $transfers = $this->getAllSuccessTransfer();

                // Loop through each transfer and move the player to the team
                foreach ($transfers as $transfer) {
                    $this->movePlayerToTeam($team['id'], $transfer['player_id'], $transfer['id']);
                }
            }
            return true;
        } catch (Exception $e) {
            throw new Exception("Failed to move all players to the team: " . $e->getMessage());
        }
    }

    public function joinAllPlayersToTeam()
    {
        try {
            // Get the current team data for the user
            $team = $this->getTeamPlayersByUserId();

            if (empty($team) || empty($team['players'])) {
                throw new Exception("No team or players found to process.");
            }

            $totalPlayers = count($team['players']);
            $processedPlayers = 0;

            foreach ($team['players'] as $player) {
                try {
                    $this->assignPlayerToTeam($team['id'], $player['id']);
                    $processedPlayers++;
                } catch (Exception $e) {
                    // Log the error for this player and continue with others
                    error_log("Failed to assign player {$player['name']} to team: " . $e->getMessage());
                }
            }

            // Check if all players were processed successfully
            if ($processedPlayers === $totalPlayers) {
                return true; // All players were successfully processed
            } else {
                throw new Exception("Only $processedPlayers out of $totalPlayers players were successfully processed.");
            }
        } catch (Exception $e) {
            throw new Exception("Failed to join all players to the team: " . $e->getMessage());
        }
    }

    public function getAllSuccessTransfer()
    {
        $fetchTransfersSql = "SELECT id, player_id 
                                  FROM football_transfer 
                                  WHERE manager_id = :manager_id 
                                  AND is_success = :is_success 
                                  AND response_at < CURRENT_TIMESTAMP";
        $fetchTransfersStmt = $this->pdo->prepare($fetchTransfersSql);
        $fetchTransfersStmt->execute([
            ':manager_id' => $this->user_id,
            ':is_success' => 1,
        ]);

        return $fetchTransfersStmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function movePlayerToTeam($teamId, $playerId, $transferId)
    {
        try {
            // Start a transaction to ensure data consistency
            $this->pdo->beginTransaction();

            // Update player's team and status
            $updatePlayerSql = "UPDATE football_player 
                            SET status = :status, updated_at = CURRENT_TIMESTAMP 
                            WHERE team_id = :team_id AND id = :player_id";
            $updatePlayerStmt = $this->pdo->prepare($updatePlayerSql);
            $updatePlayerStmt->execute([
                ':status' => 'players',
                ':team_id' => $teamId,
                ':player_id' => $playerId,
            ]);

            // Remove the transfer record
            $deleteTransferSql = "DELETE FROM football_transfer WHERE id = :id";
            $deleteTransferStmt = $this->pdo->prepare($deleteTransferSql);
            $deleteTransferStmt->execute([':id' => $transferId]);

            // Commit the transaction
            $this->pdo->commit();

            // Return the number of affected rows for validation
            return $deleteTransferStmt->rowCount();
        } catch (Exception $e) {
            // Rollback the transaction in case of an error
            $this->pdo->rollBack();
            throw new Exception("Failed to move player to team: " . $e->getMessage());
        }
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
