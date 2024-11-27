<?php

require_once DIR . '/services/FootballTeamService.php';
require_once DIR . '/controllers/UserController.php';

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

    // Handle creating a new code
    public function createTeam()
    {
        $team_name = $_POST['team_name'] ?? '';

        if ($team_name) {
            $systemUser = $this->userController->getSystemUser();
            $this->initializeTeams(DEFAULT_FOOTBALL_TEAM, $systemUser['id']);
            $this->footballTeamService->createTeam($team_name, $this->user_id);
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
}
