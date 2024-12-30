<?php

class EventService
{
  private $pdo;
  private $user_id;

  public function __construct($pdo)
  {
    global $user_id;
    $this->pdo = $pdo;
    $this->user_id = $user_id;
  }

  // Create a new event
  public function createEvent($title, $content, $tags, $status, $priority, $due_date)
  {
    $sql = "INSERT INTO events (title, content, tags, status, priority, due_date, user_id) VALUES (:title, :content, :tags, :status, :priority, :due_date, :user_id)";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([':title' => $title, ':content' => $content, ':tags' => $tags, ':status' => $status, ':priority' => $priority, ':due_date' => $due_date, ':user_id' => $this->user_id]);

    return $this->pdo->lastInsertId();
  }

  // Update a event
  public function updateEvent($id, $title, $content, $tags, $status, $priority, $due_date)
  {
    $sql = "UPDATE events SET title = :title, content = :content, tags = :tags, status = :status, priority = :priority, due_date = :due_date, updated_at = CURRENT_TIMESTAMP WHERE user_id = :user_id AND id = :id";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([':title' => $title, ':content' => $content, ':tags' => $tags, ':status' => $status, ':priority' => $priority, ':due_date' => $due_date, ':id' => $id, ':user_id' => $this->user_id]);

    return $stmt->rowCount();
  }

  // Delete a event
  public function deleteEvent($id)
  {
    $sql = "DELETE FROM events WHERE id = :id AND user_id = :user_id";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([':id' => $id, ':user_id' => $this->user_id]);

    return $stmt->rowCount();
  }

  // Get all events
  public function getAllEvents($limit = -1)
  {
    $sql = "SELECT * FROM events WHERE user_id = :user_id ORDER BY updated_at DESC LIMIT " . $limit;
    $stmt = $this->pdo->query($sql);
    $stmt->execute([':user_id' => $this->user_id]);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  // Read a event by ID
  public function getEventById($id)
  {
    $sql = "SELECT * FROM events WHERE user_id = :user_id AND id = :id";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([':user_id' => $this->user_id, ':id' => $id]);

    return $stmt->fetch(PDO::FETCH_ASSOC);
  }
}
