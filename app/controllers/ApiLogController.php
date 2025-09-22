<?php

class ApiLogController
{
    private $pdo;

    public function __construct()
    {
        global $pdo;
        $this->pdo = $pdo;
    }

    public function createLog($status, $method, $route, $result_code, $message)
    {
        $ip_address = $_SERVER['REMOTE_ADDR'];
        $sql = "INSERT INTO api_logs (status, method, route, result_code, ip_address, message) VALUES (:status, :method, :route, :result_code, :ip_address, :message)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':status' => $status, ':method' => $method, ':route' => $route, ':result_code' => $result_code, ':ip_address' => $ip_address, ':message' => $message]);

        return $this->pdo->lastInsertId();
    }

    // Handle deleting a todo
    public function deleteLog()
    {
        $id = $_POST['post_id'] ?? null;
        if ($id) {
            $sql = "DELETE FROM api_logs WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':id' => $id]);

            $rowsAffected =  $stmt->rowCount();
            if ($rowsAffected) {
                $_SESSION['message_type'] = 'success';
                $_SESSION['message'] = "API log deleted successfully.";
            } else {
                $_SESSION['message_type'] = 'danger';
                $_SESSION['message'] = "Failed to delete API log.";
            }
        } else {
            $_SESSION['message_type'] = 'danger';
            $_SESSION['message'] = "Failed to delete API log.";
        }

        header("Location: " . $_SERVER['REQUEST_URI']);
        exit;
    }

    // Get all todos

    public function listLogs()
    {
        // return $this->todoService->getAllNotifications();
        return [
            'list' => $this->getApiLogSQL("result"),
            'count' => $this->getApiLogSQL("count"),
        ];
    }

    // Handle listing all todos

    public function getApiLogSQL($queryType = "result")
    {
        // Pagination parameters
        $itemsPerPage = 10; // Number of results per page
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Current page number
        $offset = ($page - 1) * $itemsPerPage; // Offset for LIMIT clause

        // Search keyword
        $keyword = isset($_GET['s']) ? $_GET['s'] : '';

        // Filter last updated
        $log_date = isset($_GET['log_date']) ? $_GET['log_date'] : '';

        $sql = $queryType === "result" ? "SELECT * FROM api_logs" : "SELECT COUNT(*) FROM api_logs";

        if ($keyword !== '') {
            $keyword = '%' . $keyword . '%'; // Prepare for LIKE search
            $sql .= " WHERE message LIKE :keyword";
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
}
