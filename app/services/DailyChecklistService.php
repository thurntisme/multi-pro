<?php

class DailyChecklistService
{
    private $pdo;
    private $user_id;

    public function __construct($pdo)
    {
        global $user_id;
        $this->pdo = $pdo;
        $this->user_id = $user_id;
    }

    // Create a new daily checklist
    public function createDailyChecklist($title, $due_date, $content)
    {
        $sql = "INSERT INTO daily_checklist (title, due_date, content, user_id) VALUES (:title, :due_date, :content, :user_id)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':title' => $title, ':due_date' => $due_date, ':content' => $content, ':user_id' => $this->user_id]);

        return $this->pdo->lastInsertId();
    }

    // Read a daily checklist by ID

    public function updateDailyChecklist($id)
    {
        $checklist = $this->getDailyChecklistById($id);
        $status = $checklist['status'] === 'done' ? 'todo' : 'done';
        $sql = "UPDATE daily_checklist SET status = :status, updated_at = CURRENT_TIMESTAMP WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $id, ':status' => $status]);

        return $stmt->rowCount();
    }

    // Update a daily checklist

    public function getDailyChecklistById($id)
    {
        $sql = "SELECT * FROM daily_checklist WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Delete a daily checklist

    public function deleteDailyChecklist($id)
    {
        $sql = "DELETE FROM daily_checklist WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $id]);

        return $stmt->rowCount();
    }

    // Get all daily_checklist
    public function getAllDailyChecklists($limit = -1)
    {
        $sql = "SELECT * FROM daily_checklist WHERE user_id = :user_id ORDER BY updated_at DESC LIMIT " . $limit;
        $stmt = $this->pdo->query($sql);
        $stmt->execute([':user_id' => $this->user_id]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get today daily_checklist
    public function getTodayDailyChecklists()
    {
        $sql = "SELECT * FROM daily_checklist 
            WHERE user_id = :user_id 
            ORDER BY updated_at DESC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':user_id' => $this->user_id,
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
