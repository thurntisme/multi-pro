<?php

require_once DIR . '/services/FootballTeamService.php';
require_once DIR . '/controllers/FootballPlayerController.php';

class FootballTeamController
{
    private $user_id;
    private $pdo;
    private $footballTeamService;
    private $userController;

    public function __construct()
    {
        global $user_id;
        global $pdo;
        $this->user_id = $user_id;
        $this->pdo = $pdo;
        $this->footballTeamService = new FootballTeamService($pdo);
        $this->userController = new UserController();
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

        header("Location: " . home_url("football-manager"));
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
        return $this->footballTeamService->getTeamByUserId();
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

            // Calculate termination cost (e.g., 25% of remaining wage)
            $remainingWage = $playerData['contract_wage'] * 0.25;

            if ($myTeam['budget'] < $remainingWage) {
                $_SESSION['message_type'] = 'danger';
                $_SESSION['message'] = "Not enough budget to terminate $playerName's contract.";
                header("Location: " . $_SERVER['REQUEST_URI']);
                exit;
            }

            // Deduct termination cost from the team budget
            $teamBudget = $myTeam['budget'] - $remainingWage;
            $updateTeamSql = "UPDATE football_team SET budget = :budget, updated_at = CURRENT_TIMESTAMP WHERE id = :team_id";
            $updateTeamStmt = $this->pdo->prepare($updateTeamSql);
            $updateTeamStmt->execute([
                ':budget' => $teamBudget,
                ':team_id' => $myTeam['id'],
            ]);

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

    // Get all teams

    public function getMyTeamInMatch($teamId): array
    {
        $myTeamId = $this->getMyTeam()['id'];
        $team = $this->footballTeamService->getTeamById($teamId);
        if (!empty($myTeamId) && $myTeamId == $teamId) {
            $lineupPlayers = array_slice($team['players'], 0, 11);
            $subPlayers = array_slice($team['players'], 11);

            return [
                'team_id' => $team['id'],
                'team_name' => $team['team_name'],
                'formation' => $team['formation'],
                'lineup' => $lineupPlayers,
                'bench' => $subPlayers,
                'myTeam' => true
            ];
        } else {
            $formation = $this->randFormation();
            $players = $this->randomTeamPlayers($formation);
            $lineupPlayers = array_slice($players, 0, 11);
            $subPlayers = array_slice($players, 11);
            return [
                'team_id' => $teamId,
                'team_name' => $team['team_name'],
                'formation' => $formation['slug'],
                'lineup' => $lineupPlayers,
                'bench' => $subPlayers,
                'myTeam' => true
            ];
        }
    }

    function randFormation()
    {
        return DEFAULT_FOOTBALL_FORMATION[array_rand(DEFAULT_FOOTBALL_FORMATION)];
    }

    function randomTeamPlayers($formation)
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

    public function getRandTeamInMatch()
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

    public function getRecommendPlayer($formation)
    {
        $formationData = array_filter(DEFAULT_FOOTBALL_FORMATION, function ($item) use ($formation) {
            return $item['slug'] === $formation;
        });
        $players = $this->randomTeamPlayers(array_values($formationData)[0]);
        return array_slice($players, 0, 11);
    }

    public function getMyTeamPlayers()
    {
        return $this->footballTeamService->getTeamPlayersByUserId();
    }

    function updateMyClub()
    {
        $myTeam = $this->getMyTeam();
        $formation = $_POST['team_formation'] ?? '';
        $players = $_POST['team_players'] ? json_decode($_POST['team_players'], true) : '';
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
                $isSuccess = true;
            }
        }
        if ($players && count($players) > 0) {
            try {
                foreach ($players as $player) {
                    $this->footballTeamService->updateMyClubPlayer($player);
                }
                $isSuccess = true;
            } catch (Throwable $th) {
                $isSuccess = false;
            }
        }

        if ($isSuccess !== null) {
            if ($isSuccess) {
                $_SESSION['message_type'] = 'success';
                $_SESSION['message'] = "Your club updated successfully.";
            } else {
                $_SESSION['message_type'] = 'danger';
                $_SESSION['message'] = "Failed to update your club.";
            }
        }

        header("Location: " . home_url("football-manager/my-club"));
        exit;
    }

    function assignPlayerToTeam($teamId, $playerId, $playerName)
    {
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

    function moveAllPlayersToTeam()
    {
        $rowsAffected = $this->footballTeamService->moveAllPlayersToTeam();
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
