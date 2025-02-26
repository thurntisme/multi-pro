<?php

require 'services/DailyChecklistService.php';

class DailyChecklistController
{
    private $user_id;
    private $pdo;
    private $dailyChecklistService;

    public function __construct()
    {
        global $user_id;
        global $pdo;
        $this->user_id = $user_id;
        $this->pdo = $pdo;
        $this->dailyChecklistService = new DailyChecklistService($pdo);
    }

    // Handle creating a new dailyChecklist
    public function createDailyChecklist()
    {
        // Check CSRF Token
        if ($_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            $_SESSION['message_type'] = 'danger';
            $_SESSION['message'] = "Failed to submission, CSRF token mismatch";
            header("Location: " . $_SERVER['REQUEST_URI']);
            exit;
        }

        $title = $_POST['title'] ?? '';
        $due_date = $_POST['due_date'] ?? '';
        $content = $_POST['content'] ?? '';

        if (empty($title) || empty($due_date) || empty($content)) {
            $_SESSION['message_type'] = 'danger';
            $_SESSION['message'] = "All fields are required";
            header("Location: " . $_SERVER['REQUEST_URI']);
            exit;
        }

        if ($title) {
            $this->dailyChecklistService->createDailyChecklist($title, $due_date, $content);
            $_SESSION['message_type'] = 'success';
            $_SESSION['message'] = "Checklist created successfully";
        } else {
            $_SESSION['message_type'] = 'danger';
            $_SESSION['message'] = "Failed to create checklist";
        }

        header("Location: " . $_SERVER['REQUEST_URI']);
        exit;
    }

    // Handle updating a dailyChecklist
    public function updateDailyChecklist($id)
    {
        if ($id) {
            $rowsAffected = $this->dailyChecklistService->updateDailyChecklist($id);
            if ($rowsAffected) {
                $_SESSION['message_type'] = 'success';
                $_SESSION['message'] = "DailyChecklist updated successfully.";
            } else {
                $_SESSION['message_type'] = 'danger';
                $_SESSION['message'] = "Failed to update dailyChecklist.";
            }
        } else {
            $_SESSION['message_type'] = 'danger';
            $_SESSION['message'] = "DailyChecklist ID and status are required.";
        }

        header("Location: " . $_SERVER['REQUEST_URI']);
        exit;
    }

    // Handle deleting a dailyChecklist
    public function deleteDailyChecklist($id)
    {
        if ($id) {
            $rowsAffected = $this->dailyChecklistService->deleteDailyChecklist($id);
            if ($rowsAffected) {
                $_SESSION['message_type'] = 'success';
                $_SESSION['message'] = "DailyChecklist deleted successfully.";
            } else {
                $_SESSION['message_type'] = 'danger';
                $_SESSION['message'] = "Failed to delete dailyChecklist.";
            }
        } else {
            $_SESSION['message_type'] = 'danger';
            $_SESSION['message'] = "DailyChecklist ID is required.";
        }

        header("Location: " . $_SERVER['REQUEST_URI']);
        exit;
    }

    // Handle listing all daily checklist
    public function listDailyChecklists()
    {
        return $this->dailyChecklistService->getTodayDailyChecklists();
    }

    public function finishDailyChecklist()
    {
        $sql = "DELETE FROM daily_checklist WHERE status = 'done' AND user_id = :user_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':user_id' => $this->user_id]);

        if ($stmt->rowCount()) {
            $_SESSION['message_type'] = 'success';
            $_SESSION['message'] = "Checklist cleared successfully";
        } else {
            $_SESSION['message_type'] = 'danger';
            $_SESSION['message'] = "Failed to finish checklist";
        }

        header("Location: " . $_SERVER['REQUEST_URI']);
        exit;
    }
}
