<?php

require 'services/DatabaseService.php';

class DatabaseController
{
    private $user_id;
    private $pdo;
    private $databaseService;

    public function __construct()
    {
        global $user_id;
        global $pdo;
        $this->user_id = $user_id;
        $this->pdo = $pdo;
        $this->databaseService = new DatabaseService($pdo);
    }

    // Handle creating a new database
    public function createDatabase()
    {
        // Check CSRF Token
        if ($_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            $_SESSION['message_type'] = 'danger';
            $_SESSION['message'] = "Failed to update database. CSRF token mismatch";
            header("Location: " . $_SERVER['REQUEST_URI']);
            exit;
        }

        $title = $_POST['title'] ?? '';
        $content = $_POST['content'] ?? '';
        $tags = $_POST['tags'] ?? '';
        $status = $_POST['status'] ?? '';
        $priority = $_POST['priority'] ?? '';
        //        $due_date = !empty($_POST['due_date']) ? $_POST['due_date'] : date('Y-m-d');
        $due_date = $_POST['due_date'] ?? '';

        if ($title) {
            $this->databaseService->createDatabase($title, $content, $tags, $status, $priority, $due_date);
            $_SESSION['message_type'] = 'success';
            $_SESSION['message'] = "Database created successfully";
        } else {
            $_SESSION['message_type'] = 'danger';
            $_SESSION['message'] = "Failed to create database";
        }

        header("Location: " . home_url("database"));
        exit;
    }

    // Handle updating a database
    public function updateDatabase()
    {
        // Check CSRF Token
        if ($_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            $_SESSION['message_type'] = 'danger';
            $_SESSION['message'] = "Failed to update database. CSRF token mismatch";
            header("Location: " . $_SERVER['REQUEST_URI']);
            exit;
        }

        $id = $_POST['database_id'] ?? '';
        $title = $_POST['title'] ?? '';
        $content = $_POST['content'] ?? '';
        $tags = $_POST['tags'] ?? '';
        $status = $_POST['status'] ?? '';
        $priority = $_POST['priority'] ?? '';
        //        $due_date = !empty($_POST['due_date']) ? $_POST['due_date'] : date('Y-m-d');
        $due_date = $_POST['due_date'] ?? '';

        if ($id && $title) {
            $rowsAffected = $this->databaseService->updateDatabase($id, $title, $content, $tags, $status, $priority, $due_date);
            if ($rowsAffected) {
                $_SESSION['message_type'] = 'success';
                $_SESSION['message'] = "Database updated successfully.";
            } else {
                $_SESSION['message_type'] = 'danger';
                $_SESSION['message'] = "Failed to update database.";
            }
        } else {
            $_SESSION['message_type'] = 'danger';
            $_SESSION['message'] = "Database ID and service name are required.";
        }

        header("Location: " . home_url("database/edit") . '?id=' . $id);
        exit;
    }

    // Handle deleting a database
    public function deleteDatabase()
    {
        $id = $_POST['post_id'] ?? null;
        if ($id) {
            $rowsAffected = $this->databaseService->deleteDatabase($id);
            if ($rowsAffected) {
                $_SESSION['message_type'] = 'success';
                $_SESSION['message'] = "Database deleted successfully.";
            } else {
                $_SESSION['message_type'] = 'danger';
                $_SESSION['message'] = "Failed to delete database.";
            }
        } else {
            $_SESSION['message_type'] = 'danger';
            $_SESSION['message'] = "Failed to delete database.";
        }

        header("Location: " . home_url("database"));
        exit;
    }

    // Get all databases

    public function listDatabase()
    {
        // return $this->databaseService->getAllDatabases();
        return [
            'list' => $this->getDatabasesSQL("result"),
            'count' => $this->getDatabasesSQL("count"),
        ];
    }

    // Handle listing all databases

    public function getDatabasesSQL($queryType = "result")
    {
        // Pagination parameters
        $itemsPerPage = 10; // Number of results per page
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Current page number
        $offset = ($page - 1) * $itemsPerPage; // Offset for LIMIT clause
        // Search keyword
        $keyword = isset($_GET['s']) ? $_GET['s'] : '';

        $sql = "SELECT 
                TABLE_NAME AS `table`, 
                ROUND((DATA_LENGTH + INDEX_LENGTH) / 1024, 2) AS `size`,
                TABLE_ROWS AS `records` 
            FROM information_schema.TABLES
            WHERE TABLE_SCHEMA = :database";

        try {
            // Add filtering by keyword if provided
            if ($keyword !== null) {
                $keyword = '%' . $keyword . '%'; // Prepare for LIKE search
                $sql .= " AND TABLE_NAME LIKE :keyword";
            }

            $sql .= " ORDER BY TABLE_ROWS DESC";

            if ($queryType === "result") {
                // Add pagination (LIMIT and OFFSET)
                $sql .= " LIMIT $itemsPerPage OFFSET $offset";
            }

            // Prepare the query
            $stmt = $this->pdo->prepare($sql);

            // Bind the database name
            if ($keyword != '') {
                $stmt->bindParam(':keyword', $keyword, PDO::PARAM_STR);
            }
            $stmt->bindValue(':database', $this->pdo->query("SELECT DATABASE()")->fetchColumn());

            // Execute the query
            $stmt->execute();

            // Return based on query type
            if ($queryType === "result") {
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            } elseif ($queryType === "count") {
                return $stmt->rowCount();
            } else {
                throw new InvalidArgumentException("Invalid query type. Allowed values are 'result' or 'count'.");
            }
        } catch (PDOException $e) {
            // Handle any PDO exceptions
            throw new RuntimeException("Error executing SQL query: " . $e->getMessage());
        }
    }

    // Handle viewing a single database

    public function viewDatabase($id)
    {
        if ($id) {
            return $this->databaseService->getDatabaseById($id);
        }

        $_SESSION['message_type'] = 'danger';
        $_SESSION['message'] = "Database ID is required.";
        header("Location: " . $_SERVER['REQUEST_URI']);
        exit;
    }
}
