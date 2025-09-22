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

    public function listNotifications()
    {
        return [
            'list' => $this->getNotificationsSQL("result"),
            'count' => $this->getNotificationsSQL("count"),
        ];
    }

    public function getNotificationsSQL($queryType = "result")
    {
        // Pagination parameters
        $itemsPerPage = 10; // Number of results per page
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Current page number
        $offset = ($page - 1) * $itemsPerPage; // Offset for LIMIT clause

        // Search keyword
        $keyword = isset($_GET['s']) ? $_GET['s'] : '';

        // Filter last updated
        $log_date = isset($_GET['log_date']) ? $_GET['log_date'] : '';

        $selectSql = $queryType === "result" ? "SELECT * FROM notifications" : "SELECT COUNT(*) FROM notifications";
        $sql = $selectSql . " WHERE user_id = $this->user_id ";

        if ($keyword !== '') {
            $keyword = '%' . $keyword . '%'; // Prepare for LIKE search
            $sql .= " AND (title LIKE :keyword OR message LIKE :keyword)";
        }

        $startDate = '';
        $endDate = '';
        if ($log_date !== '') {
            $date_array = explode('to', $log_date);
            $date_array = array_map('trim', $date_array);
            list($startDate, $endDate) = $date_array;
            $endDate = $endDate ?? $startDate;
            $sql .= " AND log_date BETWEEN :start_date AND :end_date";
        }

        // Sorting parameters (optional)
        $sortColumn = $_GET['sort'] ?? 'updated_at';
        $sortOrder = isset($_GET['order']) && in_array(strtoupper($_GET['order']), ['ASC', 'DESC']) ? strtoupper($_GET['order']) : 'DESC'; // Default to DESC

        // Add the ORDER BY clause dynamically
        $sql .= " ORDER BY $sortColumn $sortOrder";

        if ($queryType === "result") {
            // Add pagination (LIMIT and OFFSET)
            $sql .= " LIMIT $itemsPerPage OFFSET $offset";
        }

        // Prepare the query
        $stmt = $this->pdo->prepare($sql);

        // Bind parameters
        if ($keyword != '') {
            $stmt->bindParam(':keyword', $keyword, PDO::PARAM_STR);
        }
        if ($startDate && $endDate) {
            $stmt->bindParam(':start_date', $startDate);
            $stmt->bindParam(':end_date', $endDate);
        }

        // Execute the query
        $stmt->execute();
        return $queryType === "result" ? $stmt->fetchAll(PDO::FETCH_ASSOC) : $stmt->fetchColumn();
    }

    public function readNotification()
    {
        $id = $_POST['post_id'] ?? null;
        if ($id) {
            $rowsAffected = $this->setReadNotification($id);
            if ($rowsAffected) {
                $_SESSION['message_type'] = 'success';
                $_SESSION['message'] = "Notification has been successfully updated.";
            } else {
                $_SESSION['message_type'] = 'danger';
                $_SESSION['message'] = "Failed to update notification.";
            }
        } else {
            $_SESSION['message_type'] = 'danger';
            $_SESSION['message'] = "Failed to update notification.";
        }

        header("Location: " . $_SERVER['REQUEST_URI']);
        exit;
    }

    function setReadNotification($id)
    {
        $sql = "SELECT is_read FROM notifications WHERE id = :id AND user_id = :user_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $id, ':user_id' => $this->user_id]);
        $noti = $stmt->fetch(PDO::FETCH_ASSOC);
        $newNoti = $noti['is_read'] === 0 ? 1 : 0;

        $sql = "UPDATE notifications SET is_read = :is_read WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':is_read' => $newNoti, ':id' => $id]);

        return $stmt->rowCount();
    }
}
