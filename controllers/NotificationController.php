<?php

require 'services/NotificationService.php';

class NotificationController
{
    private $user_id;
    private $pdo;
    private $notificationService;
    private $systemController;

    public function __construct()
    {
        global $user_id;
        global $pdo;
        global $systemController;
        $this->user_id = $user_id;
        $this->pdo = $pdo;
        $this->notificationService = new NotificationService($pdo);
        $this->systemController = $systemController;
    }

    // Handle creating a new notification
    public function createNotification($csrf_token, $title, $type, $message)
    {
        // Check CSRF Token
        if ($csrf_token !== $_SESSION['csrf_token']) {
            $_SESSION['message_type'] = 'danger';
            $_SESSION['message'] = "Failed to create notification. CSRF token mismatch";
            header("Location: " . $_SERVER['REQUEST_URI']);
            exit;
        }

        if ($title && $type && $message) {
            $this->notificationService->createNotification($title, $type, $message);
        }
    }

    // Handle updating a notification
    public function updateNotification()
    {
        // Check CSRF Token
        if ($_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            $_SESSION['message_type'] = 'danger';
            $_SESSION['message'] = "Failed to update notification. CSRF token mismatch";
            header("Location: " . $_SERVER['REQUEST_URI']);
            exit;
        }

        $id = $_POST['notification_id'] ?? '';
        $title = $_POST['title'] ?? '';
        $content = $_POST['content'] ?? '';
        $tags = $_POST['tags'] ?? '';
        $status = $_POST['status'] ?? '';
        $priority = $_POST['priority'] ?? '';
        //        $due_date = !empty($_POST['due_date']) ? $_POST['due_date'] : date('Y-m-d');
        $due_date = $_POST['due_date'] ?? '';

        if ($id && $title) {
            $rowsAffected = $this->notificationService->updateNotification($id, $title, $content, $tags, $status, $priority, $due_date);
            if ($rowsAffected) {
                $_SESSION['message_type'] = 'success';
                $_SESSION['message'] = "Notification updated successfully.";
            } else {
                $_SESSION['message_type'] = 'danger';
                $_SESSION['message'] = "Failed to update notification.";
            }
        } else {
            $_SESSION['message_type'] = 'danger';
            $_SESSION['message'] = "Notification ID and service name are required.";
        }

        header("Location: " . home_url("notification/edit") . '?id=' . $id);
        exit;
    }

    // Handle deleting a notification
    public function deleteNotification()
    {
        $id = $_POST['post_id'] ?? null;
        if ($id) {
            $rowsAffected = $this->notificationService->deleteNotification($id);
            if ($rowsAffected) {
                $_SESSION['message_type'] = 'success';
                $_SESSION['message'] = "Notification deleted successfully.";
            } else {
                $_SESSION['message_type'] = 'danger';
                $_SESSION['message'] = "Failed to delete notification.";
            }
        } else {
            $_SESSION['message_type'] = 'danger';
            $_SESSION['message'] = "Failed to delete notification.";
        }

        header("Location: " . home_url("notification"));
        exit;
    }

    // Get all notifications

    public function newestNotifications($user_id): array
    {
        return [
            'newest' => $this->notificationService->newestNotifications($user_id),
            'count' => $this->notificationService->getCountUnreadNotifications($user_id)
        ];
    }

    // Handle viewing a single notification

    public function viewNotification($id)
    {
        if ($id) {
            return $this->notificationService->getNotificationById($id);
        }

        $_SESSION['message_type'] = 'danger';
        $_SESSION['message'] = "Notification ID is required.";
        header("Location: " . $_SERVER['REQUEST_URI']);
        exit;
    }
}
