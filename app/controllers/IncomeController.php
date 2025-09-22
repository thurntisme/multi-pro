<?php

require 'services/IncomeService.php';

class IncomeController
{
  private $incomeService;

  public function __construct()
  {
    global $pdo;
    $this->incomeService = new IncomeService($pdo);
  }

  // Handle creating a new income
  public function createIncome()
  {
    $title = $_POST['title'] ?? '';
    $amount = $_POST['amount'] ?? '';
    $description = $_POST['description'] ?? '';

    if ($title && $amount) {
      $this->incomeService->createIncome($title, $amount, $description);
      $_SESSION['message_type'] = 'success';
      $_SESSION['message'] = "Income created successfully";
    } else {
      $_SESSION['message_type'] = 'danger';
      $_SESSION['message'] = "Income title and amount are required.";
    }

    header("Location: " . $_SERVER['REQUEST_URI']);
    exit;
  }

  // Handle updating a income
  public function updateIncome()
  {
    $id = $_POST['id'] ?? null;
    $title = $_POST['title'] ?? '';
    $amount = $_POST['amount'] ?? '';
    $description = $_POST['description'] ?? '';

    if ($id && $title & $amount) {
      $rowsAffected = $this->incomeService->updateIncome($id, $title, $amount, $description);
      if ($rowsAffected) {
        $_SESSION['message_type'] = 'success';
        $_SESSION['message'] = "Income updated successfully.";
      } else {
        $_SESSION['message_type'] = 'danger';
        $_SESSION['message'] = "Failed to update income.";
      }
    } else {
      $_SESSION['message_type'] = 'danger';
      $_SESSION['message'] = "Income ID, title and amount are required.";
    }

    header("Location: " . $_SERVER['REQUEST_URI']);
    exit;
  }

  // Handle deleting a income
  public function deleteIncome()
  {
    $id = $_POST['income_id'] ?? null;
    if ($id) {
      $rowsAffected = $this->incomeService->deleteIncome($id);
      if ($rowsAffected) {
        $_SESSION['message_type'] = 'success';
        $_SESSION['message'] = "Income deleted successfully.";
      } else {
        $_SESSION['message_type'] = 'danger';
        $_SESSION['message'] = "Failed to delete income.";
      }
    } else {
      $_SESSION['message_type'] = 'danger';
      $_SESSION['message'] = "Income ID is required.";
    }

    header("Location: " . $_SERVER['REQUEST_URI']);
    exit;
  }

  // Retrieve all incomes for dashboard
  public function getLatestTasks()
  {
    return $this->incomeService->getAllIncomes(4);
  }

  // Handle listing all incomes
  public function listIncomes()
  {
    return $this->incomeService->getAllIncomes();
  }

  // Handle listing incomes in last 7 days
  public function listIncomesLast7Days()
  {
    return $this->incomeService->getAllIncomesLast7Days();
  }

  // Handle viewing a single income
  public function viewIncome()
  {
    $id = $_GET['id'] ?? null;
    if ($id) {
      return $this->incomeService->getIncomeById($id);
    }

    $_SESSION['message_type'] = 'danger';
    $_SESSION['message'] = "Income ID is required.";
    header("Location: " . $_SERVER['REQUEST_URI']);
    exit;
  }
}
