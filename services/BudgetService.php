<?php

class BudgetService
{
  private $pdo;
  private $user_id;

  public function __construct($pdo)
  {
    global $user_id;
    $this->pdo = $pdo;
    $this->user_id = $user_id;
  }

  // Create a new budget
  public function createBudget($title, $amount, $description)
  {
    $sql = "INSERT INTO budget (title, amount, description, user_id) VALUES (:title, :amount, :description, :user_id)";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([':title' => $title, ':amount' => $amount, ':description' => $description, ':user_id' => $this->user_id]);

    return $this->pdo->lastInsertId();
  }

  // Read a budget by ID
  public function getBudgetById($id)
  {
    $sql = "SELECT * FROM budget WHERE id = :id";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([':id' => $id]);

    return $stmt->fetch(PDO::FETCH_ASSOC);
  }

  // Update a budget
  public function updateBudget($id, $title, $amount, $description)
  {
    $sql = "UPDATE budget SET title = :title, amount = :amount, description = :description, updated_at = CURRENT_TIMESTAMP WHERE id = :id";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([':id' => $id, ':title' => $title, ':amount' => $amount, ':description' => $description, ':user_id' => $this->user_id]);

    return $stmt->rowCount();
  }

  // Delete a budget
  public function deleteBudget($id)
  {
    $sql = "DELETE FROM budget WHERE id = :id";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([':id' => $id]);

    return $stmt->rowCount();
  }

  // Get all budgets
  public function getAllBudgets($limit = -1)
  {
    $sql = "SELECT * FROM budget WHERE user_id = :user_id ORDER BY updated_at DESC LIMIT " . $limit;
    $stmt = $this->pdo->query($sql);
    $stmt->execute([':user_id' => $this->user_id]);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  public function getTotalBudgetAmount()
  {
    $sql = "SELECT SUM(amount) as total_amount FROM budget";

    // Prepare and execute the statement
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute();

    // Fetch the total amount
    return $stmt->fetch(PDO::FETCH_ASSOC);
  }
}
