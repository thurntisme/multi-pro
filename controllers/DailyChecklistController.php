<?php

require 'services/DailyChecklistService.php';

class DailyChecklistController
{
  private $dailyChecklistService;

  public function __construct()
  {
    global $pdo;
    $this->dailyChecklistService = new DailyChecklistService($pdo);
  }

  // Handle creating a new dailyChecklist
  public function createDailyChecklist()
  {
    $task_id = $_POST['task_id'] ?? '';

    if ($task_id) {
      $this->dailyChecklistService->createDailyChecklist($task_id);
      $_SESSION['message_type'] = 'success';
      $_SESSION['message'] = "DailyChecklist created successfully";
    } else {
      $_SESSION['message_type'] = 'danger';
      $_SESSION['message'] = "DailyChecklist title is required.";
    }

    header("Location: " . $_SERVER['REQUEST_URI']);
    exit;
  }

  // Handle updating a dailyChecklist
  public function updateDailyChecklist()
  {
    $id = $_POST['checklist_id'] ?? null;
    $status = $_POST['status'] ?? '';

    if ($id && $status) {
      $rowsAffected = $this->dailyChecklistService->updateDailyChecklist($id, $status);
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
}
