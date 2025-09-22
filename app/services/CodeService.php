<?php

class CodeService
{
  private $pdo;
  private $user_id;

  public function __construct($pdo)
  {
    global $user_id;
    $this->pdo = $pdo;
    $this->user_id = $user_id;
  }

  // Create a new code
  public function createCode($title, $content, $tags, $url)
  {
    $sql = "INSERT INTO codes (title, content, tags, url, user_id) VALUES (:title, :content, :tags, :url, :user_id)";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([':title' => $title, ':content' => $content, ':tags' => $tags,  ':url' => $url, ':user_id' => $this->user_id]);

    return $this->pdo->lastInsertId();
  }

  // Update a code
  public function updateCode($id, $title, $content, $tags, $url)
  {
    $sql = "UPDATE codes SET title = :title, content = :content, tags = :tags, url = :url, updated_at = CURRENT_TIMESTAMP WHERE user_id = :user_id AND id = :id";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([':title' => $title, ':content' => $content, ':tags' => $tags, ':url' => $url, ':id' => $id, ':user_id' => $this->user_id]);

    return $stmt->rowCount();
  }

  // Delete a code
  public function deleteCode($id)
  {
    $sql = "DELETE FROM codes WHERE id = :id AND user_id = :user_id";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([':id' => $id, ':user_id' => $this->user_id]);

    return $stmt->rowCount();
  }

  // Get all codes
  public function getAllCodes($limit = -1)
  {
    $sql = "SELECT * FROM codes WHERE user_id = :user_id ORDER BY updated_at DESC LIMIT " . $limit;
    $stmt = $this->pdo->query($sql);
    $stmt->execute([':user_id' => $this->user_id]);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  // Read a code by ID
  public function getCodeById($id)
  {
    $sql = "SELECT * FROM codes WHERE user_id = :user_id AND id = :id";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([':user_id' => $this->user_id, ':id' => $id]);

    return $stmt->fetch(PDO::FETCH_ASSOC);
  }
}