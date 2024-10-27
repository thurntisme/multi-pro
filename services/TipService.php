<?php

class TipService
{
  private $pdo;
  private $user_id;

  public function __construct($pdo)
  {
    global $user_id;
    $this->pdo = $pdo;
    $this->user_id = $user_id;
  }

  // Create a new tip
  public function createTip($title, $content, $tags, $url)
  {
    $sql = "INSERT INTO tips (title, content, tags, url, user_id) VALUES (:title, :content, :tags, :url, :user_id)";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([':title' => $title, ':content' => $content, ':tags' => $tags,  ':url' => $url, ':user_id' => $this->user_id]);

    return $this->pdo->lastInsertId();
  }

  // Update a tip
  public function updateTip($id, $title, $content, $tags, $url)
  {
    $sql = "UPDATE tips SET title = :title, content = :content, tags = :tags, url = :url, updated_at = CURRENT_TIMESTAMP WHERE user_id = :user_id AND id = :id";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([':title' => $title, ':content' => $content, ':tags' => $tags, ':url' => $url, ':id' => $id, ':user_id' => $this->user_id]);

    return $stmt->rowCount();
  }

  // Delete a tip
  public function deleteTip($id)
  {
    $sql = "DELETE FROM tips WHERE id = :id AND user_id = :user_id";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([':id' => $id, ':user_id' => $this->user_id]);

    return $stmt->rowCount();
  }

  // Get all tips
  public function getAllTips($limit = -1)
  {
    $sql = "SELECT * FROM tips WHERE user_id = :user_id ORDER BY updated_at DESC LIMIT " . $limit;
    $stmt = $this->pdo->query($sql);
    $stmt->execute([':user_id' => $this->user_id]);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  // Read a tip by ID
  public function getTipById($id)
  {
    $sql = "SELECT * FROM tips WHERE user_id = :user_id AND id = :id";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([':user_id' => $this->user_id, ':id' => $id]);

    return $stmt->fetch(PDO::FETCH_ASSOC);
  }
}