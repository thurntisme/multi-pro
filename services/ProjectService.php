<?php

class ProjectService
{
  private $pdo;
  private $user_id;

  public function __construct($pdo)
  {
    global $user_id;
    $this->pdo = $pdo;
    $this->user_id = $user_id;
  }

  // Create a new project
  public function createProject($title, $description, $start_date, $end_date)
  {
    $sql = "INSERT INTO projects (title, description, start_date, end_date, user_id) VALUES (:title, :description, :start_date, :end_date, :user_id)";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([':title' => $title, ':description' => $description, ':start_date' => $start_date, ':end_date' => $end_date, ':user_id' => $this->user_id]);

    return $this->pdo->lastInsertId();
  }

  // Read a project by ID
  public function getProjectById($id)
  {
    $sql = "SELECT * FROM projects WHERE id = :id";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([':id' => $id]);

    return $stmt->fetch(PDO::FETCH_ASSOC);
  }

  // Update a project
  public function updateProject($id, $title, $description, $start_date, $end_date, $status)
  {
    $sql = "UPDATE projects SET title = :title, description = :description, start_date = :start_date, end_date = :end_date,status = :status, updated_at = CURRENT_TIMESTAMP WHERE id = :id";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([':id' => $id, ':title' => $title, ':description' => $description, ':start_date' => $start_date, ':end_date' => $end_date, ':status' => $status,]);

    return $stmt->rowCount();
  }

  // Delete a project
  public function deleteProject($id)
  {
    $sql = "DELETE FROM projects WHERE id = :id";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([':id' => $id]);

    return $stmt->rowCount();
  }

  // Get all projects
  public function getAllProjects($limit = -1)
  {
    $sql = "SELECT * FROM projects WHERE user_id = :user_id ORDER BY updated_at DESC LIMIT " . $limit;
    $stmt = $this->pdo->query($sql);
    $stmt->execute([':user_id' => $this->user_id]);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }
}
