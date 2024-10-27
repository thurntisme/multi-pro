<?php

class BookService
{
  private $pdo;
  private $user_id;

  public function __construct($pdo)
  {
    global $user_id;
    $this->pdo = $pdo;
    $this->user_id = $user_id;
  }

  // Create a new book
  public function createBook($title, $content, $tags, $status, $url)
  {
    $sql = "INSERT INTO books (title, content, tags, status, url, user_id) VALUES (:title, :content, :tags, :status, :url, :user_id)";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([':title' => $title, ':content' => $content, ':tags' => $tags, ':status' => $status, ':url' => $url, ':user_id' => $this->user_id]);

    return $this->pdo->lastInsertId();
  }

  // Update a book
  public function updateBook($id, $title, $content, $tags, $status, $url)
  {
    $sql = "UPDATE books SET title = :title, content = :content, tags = :tags, status = :status, url = :url, updated_at = CURRENT_TIMESTAMP WHERE user_id = :user_id AND id = :id";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([':title' => $title, ':content' => $content, ':tags' => $tags, ':status' => $status, ':url' => $url, ':id' => $id, ':user_id' => $this->user_id]);

    return $stmt->rowCount();
  }

  // Delete a book
  public function deleteBook($id)
  {
    $sql = "DELETE FROM books WHERE id = :id AND user_id = :user_id";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([':id' => $id, ':user_id' => $this->user_id]);

    return $stmt->rowCount();
  }

  // Get all books
  public function getAllBooks($limit = -1)
  {
    $sql = "SELECT * FROM books WHERE user_id = :user_id ORDER BY updated_at DESC LIMIT " . $limit;
    $stmt = $this->pdo->query($sql);
    $stmt->execute([':user_id' => $this->user_id]);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  // Read a book by ID
  public function getBookById($id)
  {
    $sql = "SELECT * FROM books WHERE user_id = :user_id AND id = :id";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([':user_id' => $this->user_id, ':id' => $id]);

    return $stmt->fetch(PDO::FETCH_ASSOC);
  }
}