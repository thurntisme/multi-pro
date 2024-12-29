<?php

require 'services/TodoService.php';

class TodoController
{
    private $user_id;
    private $pdo;
    private $todoService;

    public function __construct()
    {
        global $user_id;
        global $pdo;
        $this->user_id = $user_id;
        $this->pdo = $pdo;
        $this->todoService = new TodoService($pdo);
    }

    // Handle creating a new todo
    public function createTodo()
    {
        // Check CSRF Token
        if ($_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            $_SESSION['message_type'] = 'danger';
            $_SESSION['message'] = "Failed to update todo. CSRF token mismatch";
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
            $this->todoService->createTodo($title, $content, $tags, $status, $priority, $due_date);
            $_SESSION['message_type'] = 'success';
            $_SESSION['message'] = "Todo created successfully";
        } else {
            $_SESSION['message_type'] = 'danger';
            $_SESSION['message'] = "Failed to create todo";
        }

        header("Location: " . home_url("todo"));
        exit;
    }

    // Handle updating a todo
    public function updateTodo()
    {
        // Check CSRF Token
        if ($_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            $_SESSION['message_type'] = 'danger';
            $_SESSION['message'] = "Failed to update todo. CSRF token mismatch";
            header("Location: " . $_SERVER['REQUEST_URI']);
            exit;
        }

        $id = $_POST['todo_id'] ?? '';
        $title = $_POST['title'] ?? '';
        $content = $_POST['content'] ?? '';
        $tags = $_POST['tags'] ?? '';
        $status = $_POST['status'] ?? '';
        $priority = $_POST['priority'] ?? '';
        //        $due_date = !empty($_POST['due_date']) ? $_POST['due_date'] : date('Y-m-d');
        $due_date = $_POST['due_date'] ?? '';

        if ($id && $title) {
            $rowsAffected = $this->todoService->updateTodo($id, $title, $content, $tags, $status, $priority, $due_date);
            if ($rowsAffected) {
                $_SESSION['message_type'] = 'success';
                $_SESSION['message'] = "Todo updated successfully.";
            } else {
                $_SESSION['message_type'] = 'danger';
                $_SESSION['message'] = "Failed to update todo.";
            }
        } else {
            $_SESSION['message_type'] = 'danger';
            $_SESSION['message'] = "Todo ID and service name are required.";
        }

        header("Location: " . home_url("todo/edit") . '?id=' . $id);
        exit;
    }

    // Handle deleting a todo
    public function deleteTodo()
    {
        $id = $_POST['post_id'] ?? null;
        if ($id) {
            $rowsAffected = $this->todoService->deleteTodo($id);
            if ($rowsAffected) {
                $_SESSION['message_type'] = 'success';
                $_SESSION['message'] = "Todo deleted successfully.";
            } else {
                $_SESSION['message_type'] = 'danger';
                $_SESSION['message'] = "Failed to delete todo.";
            }
        } else {
            $_SESSION['message_type'] = 'danger';
            $_SESSION['message'] = "Failed to delete todo.";
        }

        header("Location: " . home_url("todo"));
        exit;
    }

    // Get all todos

    public function listTodos()
    {
        // return $this->todoService->getAllTodos();
        return [
            'list' => $this->getTodosSQL("result"),
            'count' => $this->getTodosSQL("count"),
        ];
    }

    // Handle listing all todos

    public function getTodosSQL($queryType = "result")
    {
        // Pagination parameters
        $itemsPerPage = 10; // Number of results per page
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Current page number
        $offset = ($page - 1) * $itemsPerPage; // Offset for LIMIT clause

        // Search keyword
        $keyword = isset($_GET['s']) ? $_GET['s'] : '';

        // Filter last updated
        $due_date = isset($_GET['due_date']) ? $_GET['due_date'] : '';

        // Filter by role (optional)
        $priority = isset($_GET['priority']) ? $_GET['priority'] : '';
        $status = isset($_GET['status']) ? $_GET['status'] : '';

        $selectSql = $queryType === "result" ? "SELECT * FROM todos" : "SELECT COUNT(*) FROM todos";
        $sql = $selectSql . " WHERE user_id = $this->user_id ";

        if ($keyword !== '') {
            $keyword = '%' . $keyword . '%'; // Prepare for LIKE search
            $sql .= " AND (title LIKE :keyword OR tags LIKE :keyword OR content LIKE :keyword)";
        }

        if ($priority !== '') {
            $sql .= " AND priority = :priority";
        }
        if ($status !== '') {
            $sql .= " AND status = :status";
        }

        $startDate = '';
        $endDate = '';
        if ($due_date !== '') {
            $date_array = explode('to', $due_date);
            $date_array = array_map('trim', $date_array);
            list($startDate, $endDate) = $date_array;
            $endDate = $endDate ?? $startDate;
            $sql .= " AND due_date BETWEEN :start_date AND :end_date";
        }

        // Sorting parameters (optional)
        $sortColumn = $_GET['sort'] ?? 'updated_at';
        $sortOrder = isset($_GET['order']) && in_array(strtoupper($_GET['order']), ['ASC', 'DESC']) ? strtoupper($_GET['order']) : 'DESC'; // Default to DESC

        // Add the ORDER BY clause dynamically
        $sql .= " ORDER BY FIELD(priority, 'critical', 'high', 'medium', 'low'), $sortColumn $sortOrder";

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
        if ($priority !== '') {
            $stmt->bindParam(':priority', $priority, PDO::PARAM_STR);
        }
        if ($status !== '') {
            $stmt->bindParam(':status', $status, PDO::PARAM_STR);
        }
        if ($startDate && $endDate) {
            $stmt->bindParam(':start_date', $startDate);
            $stmt->bindParam(':end_date', $endDate);
        }

        // Execute the query
        $stmt->execute();
        return $queryType === "result" ? $stmt->fetchAll(PDO::FETCH_ASSOC) : $stmt->fetchColumn();
    }

    // Handle viewing a single todo

    public function viewTodo($id)
    {
        if ($id) {
            return $this->todoService->getTodoById($id);
        }

        $_SESSION['message_type'] = 'danger';
        $_SESSION['message'] = "Todo ID is required.";
        header("Location: " . $_SERVER['REQUEST_URI']);
        exit;
    }
}
