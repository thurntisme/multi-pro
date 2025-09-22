<?php

require 'services/TaskService.php';

class TaskController
{
  private $taskService;

  public function __construct()
  {
    global $pdo;
    $this->taskService = new TaskService($pdo);
  }

  public function createTask()
  {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $title = $_POST['title'] ?? null;
      $description = $_POST['description'] ?? null;
      $status = $_POST['status'] ?? 'todo';
      $due_date = $_POST['due_date'] ?? null;

      if ($title) {
        $this->taskService->createTask($title, $description, $status, $due_date);
        $_SESSION['message'] = "Task created successfully";
        $_SESSION['message_type'] = 'success';
      } else {
        $_SESSION['message'] = "Title is required to create a task.";
        $_SESSION['message_type'] = 'danger';
      }

      header("Location: " . $_SERVER['REQUEST_URI']);
      exit();
    }
  }

  // Handle the retrieval of a single task by ID
  public function getTask($id)
  {
    return $this->taskService->getTask($id);
  }

  // Handle the retrieval of all tasks
  public function getAllTasks()
  {
    return $this->taskService->getAllTasks();
  }

  // Retrieve all incomplete tasks for dashboard
  public function getLatestTasks()
  {
    return $this->taskService->getAllIncompleteTasks(4);
  }

  // Retrieve all incomplete tasks
  public function getAllIncompleteTasks()
  {
    return $this->taskService->getAllIncompleteTasks();
  }

  // Handle the update of a task from form data
  public function updateTask($id)
  {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $title = $_POST['title'] ?? null;
      $description = $_POST['description'] ?? null;
      $status = $_POST['status'] ?? 'todo';
      $due_date = $_POST['due_date'] ?? null;

      if ($title) {
        $rowsAffected = $this->taskService->updateTask($id, $title, $description, $status, $due_date);
        $_SESSION['message'] = "Task updated successfully. Rows affected: $rowsAffected.";
        $_SESSION['message_type'] = 'success';
      } else {
        $_SESSION['message'] = "Title is required to update the task.";
        $_SESSION['message_type'] = 'danger';
      }

      header("Location: " . $_SERVER['REQUEST_URI']);
      exit();
    }
  }

  public function completeTask($id)
  {
    $rowsAffected = $this->taskService->completeTask($id);
    if ($rowsAffected) {
      $_SESSION['message'] = "Task marked as completed successfully.";
      $_SESSION['message_type'] = 'success';
    } else {
      $_SESSION['message'] = "Failed to mark task as completed.";
      $_SESSION['message_type'] = 'danger';
    }

    header("Location: " . $_SERVER['REQUEST_URI']);
    exit();
  }

  // Handle the deletion of a task by ID
  public function deleteTask($id)
  {
    $rowsDeleted = $this->taskService->deleteTask($id);
    if ($rowsDeleted) {
      $_SESSION['message'] = "Task deleted successfully.";
      $_SESSION['message_type'] = 'success';
    } else {
      $_SESSION['message'] = "Failed to delete task.";
      $_SESSION['message_type'] = 'danger';
    }

    header("Location: " . $_SERVER['REQUEST_URI']);
    exit();
  }
}
