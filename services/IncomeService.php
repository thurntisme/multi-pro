<?php

class IncomeService
{
  private $pdo;
  private $user_id;

  public function __construct($pdo)
  {
    global $user_id;
    $this->pdo = $pdo;
    $this->user_id = $user_id;
  }

  // Create a new income
  public function createIncome($title, $amount, $description)
  {
    $sql = "INSERT INTO income (title, amount, description, user_id) VALUES (:title, :amount, :description, :user_id)";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([':title' => $title, ':amount' => $amount, ':description' => $description, ':user_id' => $this->user_id]);

    return $this->pdo->lastInsertId();
  }

  // Read a income by ID
  public function getIncomeById($id)
  {
    $sql = "SELECT * FROM income WHERE id = :id";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([':id' => $id]);

    return $stmt->fetch(PDO::FETCH_ASSOC);
  }

  // Update a income
  public function updateIncome($id, $title, $amount, $description)
  {
    $sql = "UPDATE income SET title = :title, amount = :amount, description = :description, updated_at = CURRENT_TIMESTAMP WHERE id = :id";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([':id' => $id, ':title' => $title, ':amount' => $amount, ':description' => $description, ':user_id' => $this->user_id]);

    return $stmt->rowCount();
  }

  // Delete a income
  public function deleteIncome($id)
  {
    $sql = "DELETE FROM income WHERE id = :id";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([':id' => $id]);

    return $stmt->rowCount();
  }

  // Get all income
  public function getAllIncomes($limit = -1)
  {
    $sql = "SELECT * FROM income WHERE user_id = :user_id ORDER BY updated_at DESC LIMIT " . $limit;
    $stmt = $this->pdo->query($sql);
    $stmt->execute([':user_id' => $this->user_id]);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  // Get all expenses
  public function getAllIncomesLast7Days()
  {
    $sql = "SELECT * FROM income WHERE user_id = :user_id AND created_at >= DATE('now', '-7 days') ORDER BY updated_at DESC";
    $stmt = $this->pdo->query($sql);
    $stmt->execute([':user_id' => $this->user_id]);

    // Fetch the records
    $records = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Ensure the array has 7 items, padding with nulls if necessary
    $records = array_pad($records, 7, null);

    return $records;
  }
}
