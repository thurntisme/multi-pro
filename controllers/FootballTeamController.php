<?php

require_once DIR . '/services/FootballTeamService.php';

class FootballTeamController
{
    private $user_id;
    private $pdo;
    private $footballTeamService;

    public function __construct()
    {
        global $user_id;
        global $pdo;
        $this->user_id = $user_id;
        $this->pdo = $pdo;
        $this->footballTeamService = new FootballTeamService($pdo);
    }

    // Handle creating a new code
    public function createCode()
    {
        $title = $_POST['title'] ?? '';
        $content = $_POST['content'] ?? '';
        $tags = $_POST['tags'] ?? '';
        $url = $_POST['url'] ?? '';

        if ($title) {
            $this->footballTeamService->createCode($title, $content, $tags, $url);
            $_SESSION['message_type'] = 'success';
            $_SESSION['message'] = "Code created successfully";
        } else {
            $_SESSION['message_type'] = 'danger';
            $_SESSION['message'] = "Failed to create code";
        }

        header("Location: " . home_url("code"));
        exit;
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

    // Handle viewing a single code
    public function viewCode($id)
    {
        if ($id) {
            return $this->footballTeamService->getCodeById($id);
        }

        $_SESSION['message_type'] = 'danger';
        $_SESSION['message'] = "Code ID is required.";
        header("Location: " . $_SERVER['REQUEST_URI']);
        exit;
    }
}