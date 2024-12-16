<?php

require_once DIR . '/services/FootballTeamService.php';
require_once DIR . '/controllers/FootballPlayerController.php';

class FootballTeamController
{
    private $user_id;
    private $footballTeamService;
    private $userController;

    public function __construct()
    {
        global $user_id;
        global $pdo;
        $this->user_id = $user_id;
        $this->footballTeamService = new FootballTeamService($pdo);
        $this->userController = new UserController();
    }

    // Handle creating a new team
    public function createTeam()
    {
        $team_name = $_POST['team_name'] ?? '';

        if ($team_name) {
            $teamId = $this->footballTeamService->createTeam($team_name, $this->user_id);
            $this->initializeTeamPlayers($teamId);
            $this->initializeSystemTeams();
            $_SESSION['message_type'] = 'success';
            $_SESSION['message'] = "Team created successfully";
        } else {
            $_SESSION['message_type'] = 'danger';
            $_SESSION['message'] = "Failed to create team";
        }

        header("Location: " . home_url("football-manager"));
        exit;
    }

    public function initializeTeamPlayers($teamId)
    {
        $playerArr = getPlayersJson();
        if (count($playerArr) < 22) {
            throw new Exception("The player array must contain at least 22 items.");
        }
        // Shuffle the array
        shuffle($playerArr);
        // Get the first 22 players
        $players = array_slice($playerArr, 0, 22);

        $this->footballTeamService->initializeTeamPlayers($teamId, $players);
    }

    public function initializeSystemTeams()
    {
        $systemUser = $this->userController->getSystemUser();
        foreach (DEFAULT_FOOTBALL_TEAM as $index => $team) {
            $teamId = $this->footballTeamService->createTeam($team['name'], $systemUser['id'], $index + 2, $this->randFormation()['slug']);
//            $this->initializeTeamPlayers($teamId);
        }
    }

    function randFormation()
    {
        return DEFAULT_FOOTBALL_FORMATION[array_rand(DEFAULT_FOOTBALL_FORMATION)];
    }

    public function updateBudget($amount)
    {
        if ($amount) {
            $rowsAffected = $this->footballTeamService->updateBudget($amount);
        }
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

    public function getMyTeam()
    {
        return $this->footballTeamService->getTeamByUserId();
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
        header("Location: " . home_url("football-manager/transfer/buy-list"));
        exit;
    }
}
