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
    public function createNotification($title, $type, $message)
    {
        $sql = "INSERT INTO notifications (title, type, message, user_id) VALUES (:title, :type, :message, :user_id)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':title' => $title, ':type' => $type, ':message' => $message, ':user_id' => $this->user_id]);

        return $this->pdo->lastInsertId();
    }

    // Update a notification
    public function readNotification($id)
    {
        $sql = "UPDATE notifications SET is_read = :is_read, updated_at = CURRENT_TIMESTAMP WHERE user_id = :user_id AND id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':is_read' => 1, ':id' => $id, ':user_id' => $this->user_id]);

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
