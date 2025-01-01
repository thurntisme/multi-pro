<?php

class NotificationService
{
    private $pdo;
    private $user_id;

    public function __construct($pdo)
    {
        global $user_id;
        $this->pdo = $pdo;
        $this->user_id = $user_id;
    }

    // Create a new notification
    public function createNotification($title, $content, $tags, $status, $priority, $due_date)
    {
        $sql = "INSERT INTO notifications (title, content, tags, status, priority, due_date, user_id) VALUES (:title, :content, :tags, :status, :priority, :due_date, :user_id)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':title' => $title, ':content' => $content, ':tags' => $tags, ':status' => $status, ':priority' => $priority, ':due_date' => $due_date, ':user_id' => $this->user_id]);

        return $this->pdo->lastInsertId();
    }

    // Update a notification
    public function updateNotification($id, $title, $content, $tags, $status, $priority, $due_date)
    {
        $sql = "UPDATE notifications SET title = :title, content = :content, tags = :tags, status = :status, priority = :priority, due_date = :due_date, updated_at = CURRENT_TIMESTAMP WHERE user_id = :user_id AND id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':title' => $title, ':content' => $content, ':tags' => $tags, ':status' => $status, ':priority' => $priority, ':due_date' => $due_date, ':id' => $id, ':user_id' => $this->user_id]);

        return $stmt->rowCount();
    }

    // Delete a notification
    public function deleteNotification($id)
    {
        $sql = "DELETE FROM notifications WHERE id = :id AND user_id = :user_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $id, ':user_id' => $this->user_id]);

        return $stmt->rowCount();
    }

    public function newestNotifications($user_id)
    {
        $sql = "SELECT * FROM notifications WHERE user_id = :user_id ORDER BY updated_at DESC LIMIT 5";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':user_id' => $user_id]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCountUnreadNotifications($user_id)
    {
        $sql = "SELECT COUNT(*) FROM notifications WHERE user_id = :user_id AND is_read = 0";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':user_id' => $user_id]);

        return $stmt->fetchColumn();
    }

    // Read a notification by ID
    public function getNotificationById($id)
    {
        $sql = "SELECT * FROM notifications WHERE user_id = :user_id AND id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':user_id' => $this->user_id, ':id' => $id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}