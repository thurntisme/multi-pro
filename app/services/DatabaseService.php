<?php

class DatabaseService
{
    private $pdo;
    private $user_id;

    public function __construct($pdo)
    {
        global $user_id;
        $this->pdo = $pdo;
        $this->user_id = $user_id;
    }

    // Create a new todo
    public function createTodo($title, $content, $tags, $status, $priority, $due_date)
    {
        $sql = "INSERT INTO todos (title, content, tags, status, priority, due_date, user_id) VALUES (:title, :content, :tags, :status, :priority, :due_date, :user_id)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':title' => $title, ':content' => $content, ':tags' => $tags, ':status' => $status, ':priority' => $priority, ':due_date' => $due_date, ':user_id' => $this->user_id]);

        return $this->pdo->lastInsertId();
    }

    // Update a todo
    public function updateTodo($id, $title, $content, $tags, $status, $priority, $due_date)
    {
        $sql = "UPDATE todos SET title = :title, content = :content, tags = :tags, status = :status, priority = :priority, due_date = :due_date, updated_at = CURRENT_TIMESTAMP WHERE user_id = :user_id AND id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':title' => $title, ':content' => $content, ':tags' => $tags, ':status' => $status, ':priority' => $priority, ':due_date' => $due_date, ':id' => $id, ':user_id' => $this->user_id]);

        return $stmt->rowCount();
    }

    // Delete a todo
    public function deleteTodo($id)
    {
        $sql = "DELETE FROM todos WHERE id = :id AND user_id = :user_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $id, ':user_id' => $this->user_id]);

        return $stmt->rowCount();
    }

    // Get all todos
    public function getAllTodos($limit = -1)
    {
        $sql = "SELECT * FROM todos WHERE user_id = :user_id ORDER BY updated_at DESC LIMIT " . $limit;
        $stmt = $this->pdo->query($sql);
        $stmt->execute([':user_id' => $this->user_id]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Read a todo by ID
    public function getTodoById($id)
    {
        $sql = "SELECT * FROM todos WHERE user_id = :user_id AND id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':user_id' => $this->user_id, ':id' => $id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}