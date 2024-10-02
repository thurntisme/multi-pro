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
  public function createNote($title, $content)
  {
    $sql = "INSERT INTO notes (title, content, user_id) VALUES (:title, :content, :user_id)";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([':title' => $title, ':content' => $content, ':user_id' => $this->user_id]);

    return $this->pdo->lastInsertId();
  }

  // Read a note by ID
  public function getNoteById($id)
  {
    $sql = "SELECT * FROM notes WHERE id = :id";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([':id' => $id]);

    return $stmt->fetch(PDO::FETCH_ASSOC);
  }

  // Update a note
  public function updateNote($id, $title, $content)
  {
    $sql = "UPDATE notes SET title = :title, content = :content, updated_at = CURRENT_TIMESTAMP WHERE id = :id";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([':id' => $id, ':title' => $title, ':content' => $content]);

    return $stmt->rowCount();
  }

  // Delete a note
  public function deleteNote($id)
  {
    $sql = "DELETE FROM notes WHERE id = :id";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([':id' => $id]);

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
}
