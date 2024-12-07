<?php

class ExpenseService
{
    private $pdo;
    private $user_id;

    public function __construct($pdo)
    {
        global $user_id;
        $this->pdo = $pdo;
        $this->user_id = $user_id;
    }

    // Create a new expense
    public function createExpense($title, $category, $amount, $description, $date_expense, $tags)
    {
        $sql = "INSERT INTO expenses (title, category, amount, description, date_expense, tags, user_id) VALUES (:title, :category, :amount, :description, :date_expense, :tags, :user_id)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':title' => $title, ':category' => $category, ':amount' => $amount, ':description' => $description, ':date_expense' => $date_expense, ':tags' => $tags, ':user_id' => $this->user_id]);

        return $this->pdo->lastInsertId();
    }

    // Read a expense by ID
    public function getExpenseById($id)
    {
        $sql = "SELECT * FROM expenses WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Update a expense
    public function updateExpense($id, $title, $category, $amount, $description, $date_expense, $tags)
    {
        $sql = "UPDATE expenses SET title = :title, category = :category, amount = :amount, description = :description, date_expense = :date_expense, tags = :tags, updated_at = CURRENT_TIMESTAMP WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $id, ':title' => $title, ':category' => $category, ':amount' => $amount, ':description' => $description, ':date_expense' => $date_expense, ':tags' => $tags, ':user_id' => $this->user_id]);

        return $stmt->rowCount();
    }

    // Delete a expense
    public function deleteExpense($id)
    {
        $sql = "DELETE FROM expenses WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $id]);

        return $stmt->rowCount();
    }

    // Get all expenses
    public function getAllExpenses($limit = -1)
    {
        $sql = "SELECT * FROM expenses WHERE user_id = :user_id ORDER BY updated_at DESC LIMIT " . $limit;
        $stmt = $this->pdo->query($sql);
        $stmt->execute([':user_id' => $this->user_id]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
