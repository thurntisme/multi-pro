<?php

class TaskService
{
  private $pdo;
  private $user_id;

  public function __construct(PDO $pdo)
  {
    global $user_id;
    $this->pdo = $pdo;
    $this->user_id = $user_id;
  }

  // Create a new task
  public function createTask($title, $description, $status = 'todo', $due_date = null)
  {
    $sql = "INSERT INTO tasks (title, description, status, due_date, user_id) VALUES (:title, :description, :status, :due_date, :user_id)";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([
      ':title' => $title,
      ':description' => $description,
      ':status' => $status,
      ':due_date' => $due_date,
      ':user_id' => $this->user_id,
    ]);

    return $this->pdo->lastInsertId(); // Return the ID of the newly created task
  }

  // Retrieve a task by its ID
  public function getTask($id)
  {
    $sql = "SELECT * FROM tasks WHERE id = :id";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([':id' => $id]);

    return $stmt->fetch(PDO::FETCH_ASSOC); // Return the task as an associative array
  }

  // Retrieve all tasks
  public function getAllTasks()
  {
    $sql = "SELECT * FROM tasks WHERE user_id = :user_id";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([':user_id' => $this->user_id]);

    return $stmt->fetchAll(PDO::FETCH_ASSOC); // Return all tasks as an array of associative arrays
  }

  // Retrieve all incomplete tasks
  public function getAllIncompleteTasks($limit = -1)
  {
    $sql = "SELECT * FROM tasks WHERE status != 'completed' AND user_id = :user_id ORDER BY updated_at DESC LIMIT " . $limit;
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([':user_id' => $this->user_id]);

    return $stmt->fetchAll(PDO::FETCH_ASSOC); // Return all incomplete tasks as an array of associative arrays
  }

  // Update a task by its ID
  public function updateTask($id, $title, $description, $status, $due_date)
  {
    $sql = "UPDATE tasks SET title = :title, description = :description, status = :status, due_date = :due_date, updated_at = CURRENT_TIMESTAMP WHERE id = :id";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([
      ':title' => $title,
      ':description' => $description,
      ':status' => $status,
      ':due_date' => $due_date,
      ':id' => $id,
    ]);

    return $stmt->rowCount(); // Return the number of affected rows
  }

  // Mark a task as completed
  public function completeTask($id)
  {
    $sql = "UPDATE tasks SET status = 'completed', updated_at = CURRENT_TIMESTAMP WHERE id = :id";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([':id' => $id]);

    return $stmt->rowCount(); // Return the number of affected rows
  }

  // Delete a task by its ID
  public function deleteTask($id)
  {
    $sql = "DELETE FROM tasks WHERE id = :id";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([':id' => $id]);

    return $stmt->rowCount(); // Return the number of affected rows
  }
}
