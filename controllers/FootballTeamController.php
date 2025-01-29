<?php

require_once DIR . '/services/FootballTeamService.php';
require_once DIR . '/controllers/FootballPlayerController.php';

class FootballTeamController
{
    private $user_id;
    private $pdo;
    private $footballTeamService;
    private $systemController;

    public function __construct()
    {
        global $user_id;
        global $pdo;
        global $systemController;
        $this->user_id = $user_id;
        $this->pdo = $pdo;
        $this->footballTeamService = new FootballTeamService($pdo);
        $this->systemController = $systemController;
    }

    // Handle creating a new team
    public function createTeam()
    {
        $team_name = $_POST['team_name'] ?? '';

        if ($team_name) {
            $teamId = $this->footballTeamService->createTeam($team_name, $this->user_id);
            if ($teamId) {
                $_SESSION['message_type'] = 'success';
                $_SESSION['message'] = "Team created successfully";
            } else {
                $_SESSION['message_type'] = 'danger';
                $_SESSION['message'] = "Failed to create team";
            }
        } else {
            $_SESSION['message_type'] = 'danger';
            $_SESSION['message'] = "Failed to create team";
        }

        header("Location: " . home_url("app/football-manager"));
        exit;
    }

    public function updateBudget($amount)
    {
        if ($amount) {
            $rowsAffected = $this->footballTeamService->updateBudget($amount);
        }
    }

    public function renewPlayerContract($playerId, $playerName)
    {
        try {
            $myTeam = $this->getMyTeam();
            $teamId = $myTeam['id'];
            $teamBudget = $myTeam['budget'];

            // Fetch player data
            $sql = "SELECT player_uuid, contract_end_date FROM football_player WHERE id = :player_id AND team_id = :team_id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                ':player_id' => $playerId,
                ':team_id' => $teamId,
            ]);
            $playerData = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$playerData) {
                $_SESSION['message_type'] = 'danger';
                $_SESSION['message'] = "Player not found or does not belong to your team.";
                header("Location: " . $_SERVER['REQUEST_URI']);
                exit;
            }

            // Fetch player details from JSON
            $playerJson = getPlayerJsonByUuid($playerData['player_uuid']);
            $contractWage = $playerJson['contract_wage'];

            if ($contractWage > $teamBudget) {
                $_SESSION['message_type'] = 'danger';
                $_SESSION['message'] = "Failed to renew contract. Your team's budget is insufficient.";
                header("Location: " . $_SERVER['REQUEST_URI']);
                exit;
            }

            // Update contract details
            $contractEndDays = $playerJson['contract_end'] ?? 7; // Default to 7 days if 'contract_end' is not set
            $contractEndDate = $playerData['contract_end_date'];
            // Calculate the new contract end date
            $newContractEndDate = date('Y-m-d H:i:s', strtotime($contractEndDate . ' +' . $contractEndDays . ' days'));

            $updatePlayerSql = "
            UPDATE football_player 
            SET contract_end_date = :contract_end_date, updated_at = CURRENT_TIMESTAMP 
            WHERE id = :player_id AND team_id = :team_id";
            $updatePlayerStmt = $this->pdo->prepare($updatePlayerSql);
            $updatePlayerStmt->execute([
                ':contract_end_date' => $newContractEndDate,
                ':player_id' => $playerId,
                ':team_id' => $teamId,
            ]);

            // Deduct contract wage from budget
            $remainingBudget = $teamBudget - $contractWage;
            $updateTeamSql = "
            UPDATE football_team 
            SET budget = :budget, updated_at = CURRENT_TIMESTAMP 
            WHERE id = :team_id";
            $updateTeamStmt = $this->pdo->prepare($updateTeamSql);
            $updateTeamStmt->execute([
                ':budget' => $remainingBudget,
                ':team_id' => $teamId,
            ]);

            // Success message
            $_SESSION['message_type'] = 'success';
            $_SESSION['message'] = "$playerName's contract has been renewed successfully.";
        } catch (Exception $e) {
            $_SESSION['message_type'] = 'danger';
            $_SESSION['message'] = "Failed to renew contract for player $playerName. " . $e->getMessage();
        }

        // Redirect to the same page
        header("Location: " . $_SERVER['REQUEST_URI']);
        exit;
    }

    public function getMyTeam()
    {
        $sql = "SELECT * FROM football_team WHERE manager_id = :manager_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':manager_id' => $this->user_id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function terminatePlayerContract($playerId, $playerName)
    {
        try {
            $myTeam = $this->getMyTeam();

            // Check if the player belongs to the team
            $sql = "SELECT * FROM football_player WHERE id = :player_id AND team_id = :team_id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                ':player_id' => $playerId,
                ':team_id' => $myTeam['id'],
            ]);

            $playerData = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$playerData) {
                $_SESSION['message_type'] = 'danger';
                $_SESSION['message'] = "Player not found in your team.";
                header("Location: " . $_SERVER['REQUEST_URI']);
                exit;
            }

            // Terminate the player's contract by setting `team_id` to NULL
            $terminateSql = "DELETE FROM football_player WHERE id = :player_id";
            $terminateStmt = $this->pdo->prepare($terminateSql);
            $terminateStmt->execute([':player_id' => $playerId]);

            $_SESSION['message_type'] = 'success';
            $_SESSION['message'] = "$playerName's contract has been successfully terminated.";
        } catch (Exception $e) {
            $_SESSION['message_type'] = 'danger';
            $_SESSION['message'] = "Failed to terminate $playerName's contract.";
        }

        header("Location: " . $_SERVER['REQUEST_URI']);
        exit;
    }

    public function listTeams()
    {
        return $this->footballTeamService->getAllTeams();
    }

    function getPlayerById($id)
    {
        $sql = "SELECT * FROM football_player WHERE  id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $id]);

        $player = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($player) {
            $player_uuid = $player['player_uuid'];
            $playerJsonData = getPlayerJsonByUuid($player_uuid);
            return array_merge($playerJsonData, $player, ['score' => 5]);
        } else {
            return null;
        }
    }

    public function getPlayersInMatch($teamId)
    {

    }

    public function getMyTeamInHome()
    {
        $myTeam = $this->getMyTeam();
        $query = "";
        $params = [':team_id' => $myTeam['id']];

        $query = "AND joining_date < CURRENT_TIMESTAMP 
        AND status = 'club'";

        $sql = "SELECT * FROM football_player WHERE team_id = :team_id $query ORDER BY avg_score DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);

        $players = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (count($players) > 0) {
            $players = array_map(function ($player) {
                $playerJsonData = getPlayerJsonByUuid($player['player_uuid']);
                return array_merge($playerJsonData, $player);
            }, $players);
            $myTeam['players'] = $players;
        } else {
            $myTeam['players'] = [];
        }

        $myTeam['rcm_players'] = $this->getRecommendPlayer($myTeam['formation']);

        return $myTeam;
    }

    function getRecommendPlayer($formation)
    {
        $formationData = array_filter(DEFAULT_FOOTBALL_FORMATION, function ($item) use ($formation) {
            return $item['slug'] === $formation;
        });
        $players = $this->randomTeamPlayers(array_values($formationData)[0]);
        return array_slice($players, 0, 8);
    }

    function randomTeamPlayers($formation): array
    {
        $players = getPlayersJson();

        // Assign players to the formation
        return $this->assignPlayersToFormation($players, $formation);
    }

    function assignPlayersToFormation($players, $formation): array
    {
        $assignedPlayers = []; // Store assigned players
        $usedPlayers = [];     // Track already assigned players

        $lineup = ['GK'];
        $substitutes = ['GK'];
        foreach ($formation['player_positions'] as $positions) {
            $lineup = array_merge($lineup, $positions);
            $substitutes = array_merge($substitutes, $positions);
        }
        $positionsArr = array_merge($lineup, $substitutes);

        foreach ($positionsArr as $position) {
            $eligiblePlayers = []; // Collect players eligible for the current position

            foreach ($players as $player) {
                // Skip if the player is already used
                if (in_array($player['uuid'], $usedPlayers)) {
                    continue;
                }

                // Check if the player matches the position (best_position or playable_positions)
                if ($player['best_position'] === $position || in_array($position, $player['playable_positions'])) {
                    $eligiblePlayers[] = $player;
                }
            }

            // Randomly select a player for the position if there are eligible players
            if (!empty($eligiblePlayers)) {
                $randomPlayer = $eligiblePlayers[array_rand($eligiblePlayers)];
                $assignedPlayers[] = array_merge($randomPlayer, ['score' => 5]);
                $usedPlayers[] = $randomPlayer['uuid']; // Mark the player as used
            } else {
                // If no eligible players, assign null or indicate unfilled position
                $assignedPlayers[] = [];
            }
        }

        return $assignedPlayers;
    }

    public function getMyTeamInTransfer()
    {
        $myTeam = $this->getMyTeam();

        $sql = "SELECT * FROM football_player WHERE team_id = :team_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':team_id' => $myTeam['id']]);
        $players = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $favoritesSql = "SELECT * FROM football_favorite_player WHERE manager_id = :manager_id";
        $favoritesStmt = $this->pdo->prepare($favoritesSql);
        $favoritesStmt->execute([':manager_id' => $this->user_id]);
        $favorites = $favoritesStmt->fetchAll(PDO::FETCH_ASSOC);

        if (count($players) > 0) {
            $players = array_map(function ($player) {
                $playerJsonData = getPlayerJsonByUuid($player['player_uuid']);
                return array_merge($playerJsonData, $player);
            }, $players);
            $myTeam['players'] = $players;
        } else {
            $myTeam['players'] = [];
        }

        $myTeam['favorites'] = $favorites;

        return $myTeam;
    }

    public function getMyTeamInMatch()
    {
        $team = $this->getMyTeam();
        if (!empty($team)) {
            $players = $this->getTeamPlayersInMatch($team['id']);
            $lineupPlayers = array_slice($players, 0, 11);
            $subPlayers = array_slice($players, 11);

            return [
                'team_id' => $team['id'],
                'team_name' => $team['team_name'],
                'formation' => $team['formation'],
                'lineup' => $lineupPlayers,
                'bench' => $subPlayers,
                'myTeam' => true
            ];
        }
        return [];
    }

    public function getTeamPlayersInMatch($teamId)
    {
        $params = [':team_id' => $teamId];

        $query = "AND joining_date < CURRENT_TIMESTAMP 
        AND (injury_end_date IS NULL OR injury_end_date < CURRENT_TIMESTAMP) 
        AND contract_end_date <= CURRENT_TIMESTAMP 
        AND player_stamina >= 50 
        AND status = 'club'";

        $sql = "SELECT * FROM football_player WHERE team_id = :team_id $query ORDER BY starting_order ASC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);

        $players = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (count($players) > 0) {
            $players = array_map(function ($player) {
                $playerJsonData = getPlayerJsonByUuid($player['player_uuid']);
                return array_merge($playerJsonData, $player, ['score' => 5]);
            }, $players);
        }

        return $players;
    }

    public function getRandTeamInMatch(): array
    {
        $formation = $this->randFormation();
        $players = $this->randomTeamPlayers($formation);
        $lineupPlayers = array_slice($players, 0, 11);
        $subPlayers = array_slice($players, 11);
        return [
            'formation' => $formation['slug'],
            'lineup' => $lineupPlayers,
            'bench' => $subPlayers,
            'myTeam' => false
        ];
    }

    function randFormation()
    {
        return DEFAULT_FOOTBALL_FORMATION[array_rand(DEFAULT_FOOTBALL_FORMATION)];
    }

    public function getMyTeamPlayers()
    {
        $team = $this->getMyTeam();
        if (!$team) {
            return null;
        }

        $sql = "SELECT * FROM football_player WHERE team_id = :team_id AND status = 'players' ORDER BY starting_order ASC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':team_id' => $team['id']]);

        $players = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (!empty($players)) {
            $team['players'] = array_map(function ($player) {
                $playerJsonData = getPlayerJsonByUuid($player['player_uuid']);
                return array_merge($playerJsonData, $player);
            }, $players);
        } else {
            $team['players'] = [];
        }

        return $team;
    }

    function updateMyClub(): void
    {
        $msg = '';
        $myTeam = $this->getMyTeam();
        $formation = $_POST['team_formation'] ?? '';
        $players = $_POST['team_players'] ? json_decode($_POST['team_players'], true) : [];
        $isSuccess = null;
        if ($formation !== $myTeam['formation']) {
            try {
                $rowsAffectedFormation = $this->footballTeamService->updateMyClubFormation($formation);
                if ($rowsAffectedFormation) {
                    $isSuccess = true;
                } else {
                    $isSuccess = false;
                }
            } catch (Throwable $th) {
                $isSuccess = false;
                $msg = $th->getMessage();
            }
        }
        if ($players && count($players) > 0) {
            try {
                $this->updateMyClubPlayers($players);
                $isSuccess = true;
            } catch (Throwable $th) {
                $isSuccess = false;
                $msg = $th->getMessage();
            }
        }

        if ($isSuccess !== null) {
            if ($isSuccess) {
                $_SESSION['message_type'] = 'success';
                $_SESSION['message'] = "Your club updated successfully.";
            } else {
                $_SESSION['message_type'] = 'danger';
                $_SESSION['message'] = "Failed to update your club. " . $msg;
            }
        }

        header("Location: " . home_url("app/football-manager/my-club"));
        exit;
    }

    function updateMyClubPlayers($players): void
    {
        // Begin transaction for bulk update
        $this->pdo->beginTransaction();

        try {
            $sql = "UPDATE football_player 
                SET starting_order = :starting_order, updated_at = CURRENT_TIMESTAMP 
                WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);

            foreach ($players as $player) {
                if (!isset($player['new_starting_order'], $player['id'])) {
                    throw new InvalidArgumentException("Invalid player data");
                }

                $stmt->execute([
                    ':starting_order' => $player['new_starting_order'],
                    ':id' => $player['id']
                ]);
            }

            // Commit transaction
            $this->pdo->commit();
        } catch (Exception $e) {
            // Rollback transaction on error
            $this->pdo->rollBack();
            throw $e;
        }
    }

    function updateMyPlayers()
    {
        // Retrieve the current team details
        $myTeam = $this->getMyTeam();
        $players = isset($_POST['team_players']) ? json_decode($_POST['team_players'], true) : [];
        $isSuccess = false;

        // Ensure there are players to update
        if ($players && count($players) > 0) {
            try {
                // Begin a single transaction for all updates
                $this->pdo->beginTransaction();

                // Prepare the SQL statement
                $stmt = $this->pdo->prepare("
                UPDATE football_player 
                SET shirt_number = :shirt_number, 
                    updated_at = CURRENT_TIMESTAMP 
                WHERE team_id = :team_id 
                  AND player_uuid = :player_uuid 
                  AND id = :player_id
            ");

                // Execute the update for each player
                foreach ($players as $player) {
                    $stmt->execute([
                        ':team_id' => $myTeam['id'],
                        ':shirt_number' => $player['shirt_number'],
                        ':player_id' => $player['id'],
                        ':player_uuid' => $player['uuid'],
                    ]);
                }

                // Commit the transaction
                $this->pdo->commit();
                $isSuccess = true;
            } catch (Exception $e) {
                // Rollback the transaction if any update fails
                if ($this->pdo->inTransaction()) {
                    $this->pdo->rollBack();
                }
                error_log("Error updating players: " . $e->getMessage());
            }
        }

        // Set success or error messages
        if ($isSuccess) {
            $_SESSION['message_type'] = 'success';
            $_SESSION['message'] = "Your players were updated successfully.";
        } else {
            $_SESSION['message_type'] = 'danger';
            $_SESSION['message'] = "Failed to update your players.";
        }

        // Redirect to the current page
        header("Location: " . $_SERVER['REQUEST_URI']);
        exit;
    }

    function assignPlayerToTeam($teamId, $playerId, $playerName)
    {
        $this->checkTeamMaxPlayers();
        $rowsAffected = $this->footballTeamService->assignPlayerToTeam($teamId, $playerId);
        if ($rowsAffected) {
            $_SESSION['message_type'] = 'success';
            $_SESSION['message'] = $playerName . " has been assigned successfully to your team.";
        } else {
            $_SESSION['message_type'] = 'danger';
            $_SESSION['message'] = "Failed to assign $playerName to your team.";
        }

        header("Location: " . $_SERVER['REQUEST_URI']);
        exit;
    }

    function checkTeamMaxPlayers()
    {
        $teamInClub = $this->getMyTeamInClub();
        $total_players_in_club = count($teamInClub['players']);
        if (isset($teamInClub['players']) && $total_players_in_club === 32) {
            $_SESSION['message_type'] = 'danger';
            $_SESSION['message'] = "Team data has reached the maximum number of club players ($total_players_in_club).";
        }
        header("Location: " . $_SERVER['REQUEST_URI']);
        exit;
    }

    public function getMyTeamInClub(): array
    {
        $team = $this->getMyTeam();
        if (!empty($team)) {
            $players = $this->getTeamPlayersInClub($team['id'], $team['formation']);

            return [
                'team_name' => $team['team_name'],
                'team_id' => $team['id'],
                'formation' => $team['formation'],
                'players' => $players,
            ];
        }
        return [];
    }

    public function getTeamPlayersInClub($teamId, $formation = ''): array
    {
        $sql = "SELECT * FROM football_player WHERE team_id = :team_id AND joining_date < CURRENT_TIMESTAMP 
        AND status = 'club' ORDER BY starting_order ASC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':team_id' => $teamId]);

        $players = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $positionsArr = [];
        if (!empty($formation)) {
            $positionsArr = $this->getPlayerPositionsBySlug($formation);
        }

        if (count($players) > 0) {
            $players = array_map(function ($player, $index) use ($positionsArr) {
                $playerJsonData = getPlayerJsonByUuid($player['player_uuid']);
                $playerJsonData['remaining_contract_date'] = $this->calRemainingContractDate($player['contract_end_date']);
                $playerJsonData['market_value'] = formatCurrency($playerJsonData['market_value']);
                $playerJsonData['contract_wage'] = formatCurrency($playerJsonData['contract_wage']);
                $player['contract_end_date'] = $this->systemController->convertDate($player['contract_end_date']);
                $playerJsonData['is_expired'] = $playerJsonData['remaining_contract_date'] < 0;
                $player['level'] = $this->getLevelDetails($player['level']);
                $player['is_injury'] = !empty($player['injury_end_date']) && !$this->systemController->isBeforeCurrentUTCDateTime($player['injury_end_date']);
                $player['position_in_formation'] = $positionsArr[$index] ?? null; // Use index here
                return array_merge($playerJsonData, $player);
            }, $players, array_keys($players));
        }

        return $players;
    }

    function getPlayerPositionsBySlug($slug): ?array
    {
        global $formations;
        $filtered = array_filter($formations, function ($formation) use ($slug) {
            return $formation['slug'] === $slug;
        });

        if (!empty($filtered)) {
            // Flatten the player_positions array
            $formation = array_values($filtered)[0];
            return array_merge(["GK"], ...array_values($formation['player_positions']));
        }

        return null;
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

    function moveAllPlayersToTeam($transferType)
    {
        $rowsAffected = $this->footballTeamService->moveAllPlayersToTeam($transferType);
        if ($rowsAffected) {
            $_SESSION['message_type'] = 'success';
            $_SESSION['message'] = "All players has been move successfully to your players.";
        } else {
            $_SESSION['message_type'] = 'danger';
            $_SESSION['message'] = "Failed to assign move all players to your players.";
        }

        header("Location: " . $_SERVER['REQUEST_URI']);
        exit;
    }

    function joinAllPlayersToTeam()
    {
        $this->checkTeamMaxPlayers();
        $rowsAffected = $this->footballTeamService->joinAllPlayersToTeam();
        if ($rowsAffected) {
            $_SESSION['message_type'] = 'success';
            $_SESSION['message'] = "All players has been joined successfully to your team.";
        } else {
            $_SESSION['message_type'] = 'danger';
            $_SESSION['message'] = "Failed to assign joined all players to your team.";
        }

        header("Location: " . $_SERVER['REQUEST_URI']);
        exit;
    }

    function movePlayerToTeam($teamId, $playerId, $playerName, $transferId)
    {
        $rowsAffected = $this->footballTeamService->movePlayerToTeam($teamId, $playerId, $transferId);
        if ($rowsAffected) {
            $_SESSION['message_type'] = 'success';
            $_SESSION['message'] = $playerName . " has been move successfully to your players.";
        } else {
            $_SESSION['message_type'] = 'danger';
            $_SESSION['message'] = "Failed to assign $playerName to your players.";
        }

        header("Location: " . $_SERVER['REQUEST_URI']);
        exit;
    }

    function getRefundFromPlayer($playerId, $playerName, $transferId)
    {
        $budget = $this->footballTeamService->getRefundFromPlayer($playerId, $transferId);
        if ($budget) {
            $_SESSION['message_type'] = 'success';
            $_SESSION['message'] = 'An amount of $' . convertAmount($budget) . " has been successfully added to your team's budget.";
        } else {
            $_SESSION['message_type'] = 'danger';
            $_SESSION['message'] = "Failed to refund from player $playerName.";
        }
        header("Location: " . $_SERVER['REQUEST_URI']);
        exit;
    }
}
