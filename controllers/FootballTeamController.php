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

    // Handle creating a new code
    public function createTeam()
    {
        $team_name = $_POST['team_name'] ?? '';

        if ($team_name) {
            $systemUser = $this->userController->getSystemUser();
            $this->initializeTeams(DEFAULT_FOOTBALL_TEAM, $systemUser['id']);
            $teamId = $this->footballTeamService->createTeam($team_name, $this->user_id);
            $this->initializeTeamPlayers($teamId);
            $_SESSION['message_type'] = 'success';
            $_SESSION['message'] = "Team created successfully";
        } else {
            $_SESSION['message_type'] = 'danger';
            $_SESSION['message'] = "Failed to create team";
        }

        header("Location: " . home_url("football-manager"));
        exit;
    }

    public function initializeTeams($teams, $systemUserId)
    {
        $this->footballTeamService->initializeTeams($teams, $systemUserId);
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

    // Handle updating a code
    public function updateCode()
    {
        $id = $_POST['code_id'] ?? '';
        $title = $_POST['title'] ?? '';
        $content = $_POST['content'] ?? '';
        $tags = $_POST['tags'] ?? '';
        $url = $_POST['url'] ?? '';

        if ($id && $title) {
            $rowsAffected = $this->footballTeamService->updateCode($id, $title, $content, $tags, $url);
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

    public function updateBudget($amount)
    {
        if ($amount) {
            $rowsAffected = $this->footballTeamService->updateBudget($amount);
        }
    }

    // Handle deleting a code
    public function deleteCode()
    {
        $id = $_POST['post_id'] ?? null;
        if ($id) {
            $rowsAffected = $this->footballTeamService->deleteCode($id);
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
        return $this->footballTeamService->getAllTeams();
    }

    public function getMyTeam()
    {
        return $this->footballTeamService->getTeamByUserId();
    }

    public function getMyTeamInMatch()
    {
        $team = $this->footballTeamService->getTeamByUserId();
        $lineupPlayers = array_slice($team['players'], 0, 11);
        $subPlayers = array_slice($team['players'], 11, 17);

        return [
            'team_id' => $team['id'],
            'team_name' => $team['team_name'],
            'formation' => $team['formation'],
            'lineup' => $lineupPlayers,
            'bench' => $subPlayers
        ];
    }
}
