<?php

class ReportWorkingService
{
  private $pdo;
  private $user_id;

  public function __construct($pdo)
  {
    global $user_id;
    $this->pdo = $pdo;
    $this->user_id = $user_id;
  }

  // Create a new report
  public function createReport($title, $project, $content, $tags, $note, $working_date)
  {
    $sql = "INSERT INTO report_working (title, project, content, tags, note, working_date, user_id) VALUES (:title, :project, :content, :tags, :note, :working_date, :user_id)";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([':title' => $title, ':project' => $project, ':content' => $content, ':tags' => $tags, ':note' => $note, ':working_date' => $working_date, ':user_id' => $this->user_id]);

    return $this->pdo->lastInsertId();
  }

  // Update a report
  public function updateReport($id, $title, $project, $content, $tags, $note, $working_date)
  {
    $sql = "UPDATE report_working SET title = :title, project = :project, content = :content,tags = :tags, note = :note, working_date = :working_date, updated_at = CURRENT_TIMESTAMP WHERE user_id = :user_id AND id = :id";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([':title' => $title, ':project' => $project, ':content' => $content, ':tags' => $tags, ':note' => $note, ':working_date' => $working_date, ':id' => $id, ':user_id' => $this->user_id]);

    return $stmt->rowCount();
  }

  // Delete a report
  public function deleteReport($id)
  {
    $sql = "DELETE FROM report_working WHERE id = :id AND user_id = :user_id";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([':id' => $id, ':user_id' => $this->user_id]);

    return $stmt->rowCount();
  }

  // Get all report_working
  public function getAllReports($limit = -1)
  {
    $sql = "SELECT * FROM report_working WHERE user_id = :user_id ORDER BY updated_at DESC LIMIT " . $limit;
    $stmt = $this->pdo->query($sql);
    $stmt->execute([':user_id' => $this->user_id]);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  // Read a report by ID
  public function getReportById($id)
  {
    $sql = "SELECT * FROM report_working WHERE user_id = :user_id AND id = :id";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([':user_id' => $this->user_id, ':id' => $id]);

    return $stmt->fetch(PDO::FETCH_ASSOC);
  }
}