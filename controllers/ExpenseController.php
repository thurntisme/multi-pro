<?php

require 'services/ExpenseService.php';

class ExpenseController
{
  private $expenseService;

  public function __construct()
  {
    global $pdo;
    $this->expenseService = new ExpenseService($pdo);
  }

  // Handle creating a new expense
  public function createExpense()
  {
    $title = $_POST['title'] ?? '';
    $amount = $_POST['amount'] ?? '';
    $description = $_POST['description'] ?? '';
    $category_id = $_POST['category_id'] ?? '';

    if ($title && $amount) {
      $this->expenseService->createExpense($title, $amount, $description, $category_id);
      $_SESSION['message_type'] = 'success';
      $_SESSION['message'] = "Expense created successfully";
    } else {
      $_SESSION['message_type'] = 'danger';
      $_SESSION['message'] = "Expense title and amount are required.";
    }

    header("Location: " . $_SERVER['REQUEST_URI']);
    exit;
  }

  // Handle updating a expense
  public function updateExpense()
  {
    $id = $_POST['id'] ?? null;
    $title = $_POST['title'] ?? '';
    $amount = $_POST['amount'] ?? '';
    $description = $_POST['description'] ?? '';
    $category_id = $_POST['category_id'] ?? '';

    if ($id && $title && $amount) {
      $rowsAffected = $this->expenseService->updateExpense($id, $title, $amount, $description, $category_id);
      if ($rowsAffected) {
        $_SESSION['message_type'] = 'success';
        $_SESSION['message'] = "Expense updated successfully.";
      } else {
        $_SESSION['message_type'] = 'danger';
        $_SESSION['message'] = "Failed to update expense.";
      }
    } else {
      $_SESSION['message_type'] = 'danger';
      $_SESSION['message'] = "Expense ID, title and amount are required.";
    }

    header("Location: " . $_SERVER['REQUEST_URI']);
    exit;
  }

  // Handle deleting a expense
  public function deleteExpense()
  {
    $id = $_POST['expense_id'] ?? null;
    if ($id) {
      $rowsAffected = $this->expenseService->deleteExpense($id);
      if ($rowsAffected) {
        $_SESSION['message_type'] = 'success';
        $_SESSION['message'] = "Expense deleted successfully.";
      } else {
        $_SESSION['message_type'] = 'danger';
        $_SESSION['message'] = "Failed to delete expense.";
      }
    } else {
      $_SESSION['message_type'] = 'danger';
      $_SESSION['message'] = "Expense ID is required.";
    }

    header("Location: " . $_SERVER['REQUEST_URI']);
    exit;
  }

  // Retrieve all expenses for dashboard
  public function getLatestTasks()
  {
    return $this->expenseService->getAllExpenses(4);
  }

  // Handle listing all expenses
  public function listExpenses()
  {
    return $this->expenseService->getAllExpenses();
  }

  // Handle listing expenses in last 7 days
  public function listExpensesLast7Days()
  {
    return $this->expenseService->getAllExpensesLast7Days();
  }

  // Handle viewing a single expense
  public function viewExpense()
  {
    $id = $_GET['id'] ?? null;
    if ($id) {
      return $this->expenseService->getExpenseById($id);
    }

    $_SESSION['message_type'] = 'danger';
    $_SESSION['message'] = "Expense ID is required.";
    header("Location: " . $_SERVER['REQUEST_URI']);
    exit;
  }
}
