<?php

class NoteService
{
  private $pdo;
  private $user_id;

  public function __construct($pdo)
  {
    global $user_id;
    $this->pdo = $pdo;
    $this->user_id = $user_id;
  }

  // Create a new note
  public function createNote($title, $content, $tags)
  {
    $sql = "INSERT INTO notes (title, content, tags, user_id) VALUES (:title, :content, :tags, :user_id)";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([':title' => $title, ':content' => $content, ':tags' => $tags, ':user_id' => $this->user_id]);

    return $this->pdo->lastInsertId();
  }

  // Update a note
  public function updateNote($id, $title, $content, $tags)
  {
    $sql = "UPDATE notes SET title = :title, content = :content, tags = :tags, updated_at = CURRENT_TIMESTAMP WHERE user_id = :user_id AND id = :id";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([':title' => $title, ':content' => $content, ':tags' => $tags, ':id' => $id, ':user_id' => $this->user_id]);

    return $stmt->rowCount();
  }

  // Delete a note
  public function deleteNote($id)
  {
    $sql = "DELETE FROM notes WHERE id = :id AND user_id = :user_id";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([':id' => $id, ':user_id' => $this->user_id]);

    return $stmt->rowCount();
  }

  // Get all notes
  public function getAllNotes($limit = -1)
  {
    $sql = "SELECT * FROM notes WHERE user_id = :user_id ORDER BY updated_at DESC LIMIT " . $limit;
    $stmt = $this->pdo->query($sql);
    $stmt->execute([':user_id' => $this->user_id]);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  // Read a note by ID
  public function getNoteById($id)
  {
    $sql = "SELECT * FROM notes WHERE user_id = :user_id AND id = :id";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([':user_id' => $this->user_id, ':id' => $id]);

    return $stmt->fetch(PDO::FETCH_ASSOC);
  }
}