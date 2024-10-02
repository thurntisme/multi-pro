<?php

class PlanService
{
  private $pdo;
  private $user_id;

  public function __construct($pdo)
  {
    global $user_id;
    $this->pdo = $pdo;
    $this->user_id = $user_id;
  }

  // Create a new plan
  public function createPlan($title, $content, $status = 'pending', $start_date = null, $end_date = null)
  {
    $sql = "INSERT INTO plans (title, content, status, start_date, end_date, user_id) VALUES (:title, :content, :status, :start_date, :end_date, :user_id)";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([':title' => $title, ':content' => $content, ':status' => $status, ':start_date' => $start_date, ':end_date' => $end_date, ':user_id' => $this->user_id]);

    return $this->pdo->lastInsertId();
  }

  // Read a plan by ID
  public function getPlanById($id)
  {
    $sql = "SELECT * FROM plans WHERE id = :id";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([':id' => $id]);

    return $stmt->fetch(PDO::FETCH_ASSOC);
  }

  // Update a plan
  public function updatePlan($id, $title, $content, $status, $start_date, $end_date)
  {
    $sql = "UPDATE plans SET title = :title, content = :content, status = :status, start_date = :start_date, end_date = :end_date,  updated_at = CURRENT_TIMESTAMP WHERE id = :id";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([':id' => $id, ':title' => $title, ':content' => $content, ':status' => $status, ':start_date' => $start_date, ':end_date' => $end_date]);

    return $stmt->rowCount();
  }

  // Delete a plan
  public function deletePlan($id)
  {
    $sql = "DELETE FROM plans WHERE id = :id";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([':id' => $id]);

    return $stmt->rowCount();
  }

  // Get all plans
  public function getAllPlans($limit = -1)
  {
    $sql = "SELECT * FROM plans WHERE user_id = :user_id ORDER BY updated_at DESC LIMIT " . $limit;
    $stmt = $this->pdo->query($sql);
    $stmt->execute([':user_id' => $this->user_id]);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }
}
