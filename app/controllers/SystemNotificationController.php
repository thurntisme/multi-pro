<?php

class SystemNotificationController
{
    private $pdo;

    public function __construct()
    {
        global $pdo;
        $this->pdo = $pdo;
    }

    // Handle deleting a todo
    public function deleteSystemNotification()
    {
        $id = $_POST['post_id'] ?? null;
        if ($id) {
            $sql = "DELETE FROM system_notifications WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':id' => $id]);

            $rowsAffected =  $stmt->rowCount();
            if ($rowsAffected) {
                $_SESSION['message_type'] = 'success';
                $_SESSION['message'] = "System notification deleted successfully.";
            } else {
                $_SESSION['message_type'] = 'danger';
                $_SESSION['message'] = "Failed to delete System notification.";
            }
        } else {
            $_SESSION['message_type'] = 'danger';
            $_SESSION['message'] = "Failed to delete system notification.";
        }

        header("Location: " . $_SERVER['REQUEST_URI']);
        exit;
    }

    // Get all todos

    public function listNotifications()
    {
        // return $this->todoService->getAllNotifications();
        return [
            'list' => $this->getNotificationsSQL("result"),
            'count' => $this->getNotificationsSQL("count"),
        ];
    }

    // Handle listing all todos

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

        $sql = $queryType === "result" ? "SELECT * FROM system_notifications" : "SELECT COUNT(*) FROM system_notifications";

        if ($keyword !== '') {
            $keyword = '%' . $keyword . '%'; // Prepare for LIKE search
            $sql .= " WHERE title LIKE :keyword OR message LIKE :keyword";
        }

        $startDate = '';
        $endDate = '';
        if ($log_date !== '') {
            $date_array = explode('to', $log_date);
            $date_array = array_map('trim', $date_array);
            list($startDate, $endDate) = $date_array;
            $endDate = $endDate ?? $startDate;
            $sql .= " AND created_at BETWEEN :start_date AND :end_date";
        }

        $sql .= " ORDER BY created_at DESC";

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
    public function getSystemNotificationsUnreadCount()
    {
        $sql = "SELECT COUNT(*) FROM system_notifications WHERE is_read = 0";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchColumn();
    }

    public function readSystemNotification()
    {
        $id = $_POST['post_id'] ?? null;
        if ($id) {
            $rowsAffected = $this->setReadSystemNotification($id);
            if ($rowsAffected) {
                $_SESSION['message_type'] = 'success';
                $_SESSION['message'] = "System notification has been successfully updated.";
            } else {
                $_SESSION['message_type'] = 'danger';
                $_SESSION['message'] = "Failed to update system notification.";
            }
        } else {
            $_SESSION['message_type'] = 'danger';
            $_SESSION['message'] = "Failed to update system notification.";
        }

        header("Location: " . $_SERVER['REQUEST_URI']);
        exit;
    }

    function setReadSystemNotification($id)
    {
        $sql = "SELECT is_read FROM system_notifications WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
        $noti = $stmt->fetch(PDO::FETCH_ASSOC);
        $newNoti = $noti['is_read'] === 0 ? 1 : 0;

        $sql = "UPDATE system_notifications SET is_read = :is_read WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':is_read' => $newNoti, ':id' => $id]);

        return $stmt->rowCount();
    }
}
