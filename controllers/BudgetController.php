<?php

require 'services/BudgetService.php';

class BudgetController
{
  private $budgetService;

  public function __construct()
  {
    global $pdo;
    $this->budgetService = new BudgetService($pdo);
  }

  // Handle creating a new budget
  public function createBudget()
  {
    $title = $_POST['title'] ?? '';
    $amount = $_POST['amount'] ?? '';
    $description = $_POST['description'] ?? '';

    if ($title && $amount) {
      $this->budgetService->createBudget($title, $amount, $description);
      $_SESSION['message_type'] = 'success';
      $_SESSION['message'] = "Budget created successfully";
    } else {
      $_SESSION['message_type'] = 'danger';
      $_SESSION['message'] = "Budget title and amount are required.";
    }

    header("Location: " . $_SERVER['REQUEST_URI']);
    exit;
  }

  // Handle updating a budget
  public function updateBudget()
  {
    $id = $_POST['id'] ?? null;
    $title = $_POST['title'] ?? '';
    $amount = $_POST['amount'] ?? '';
    $description = $_POST['description'] ?? '';

    if ($id && $title && $amount) {
      $rowsAffected = $this->budgetService->updateBudget($id, $title, $amount, $description);
      if ($rowsAffected) {
        $_SESSION['message_type'] = 'success';
        $_SESSION['message'] = "Budget updated successfully.";
      } else {
        $_SESSION['message_type'] = 'danger';
        $_SESSION['message'] = "Failed to update budget.";
      }
    } else {
      $_SESSION['message_type'] = 'danger';
      $_SESSION['message'] = "Budget ID, title and amount are required.";
    }

    header("Location: " . $_SERVER['REQUEST_URI']);
    exit;
  }

  // Handle deleting a budget
  public function deleteBudget()
  {
    $id = $_POST['budget_id'] ?? null;
    if ($id) {
      $rowsAffected = $this->budgetService->deleteBudget($id);
      if ($rowsAffected) {
        $_SESSION['message_type'] = 'success';
        $_SESSION['message'] = "Budget deleted successfully.";
      } else {
        $_SESSION['message_type'] = 'danger';
        $_SESSION['message'] = "Failed to delete budget.";
      }
    } else {
      $_SESSION['message_type'] = 'danger';
      $_SESSION['message'] = "Budget ID is required.";
    }

    header("Location: " . $_SERVER['REQUEST_URI']);
    exit;
  }

  // Retrieve all budgets for dashboard
  public function getLatestTasks()
  {
    return $this->budgetService->getAllBudgets(4);
  }

  // Handle listing all budgets
  public function listBudgets()
  {
    return $this->budgetService->getAllBudgets();
  }

  // Handle viewing a single budget
  public function viewBudget()
  {
    $id = $_GET['id'] ?? null;
    if ($id) {
      return $this->budgetService->getBudgetById($id);
    }

    $_SESSION['message_type'] = 'danger';
    $_SESSION['message'] = "Budget ID is required.";
    header("Location: " . $_SERVER['REQUEST_URI']);
    exit;
  }

  public function getTotalBudget()
  {
    $result = $this->budgetService->getTotalBudgetAmount();
    return $result['total_amount'] ?? 0;
  }
}
