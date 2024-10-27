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
    $title = $_POST['title'] ?? '';
    $content = $_POST['content'] ?? '';
    $tags = $_POST['tags'] ?? '';
    $status = $_POST['status'] ?? '';
    $priority = $_POST['priority'] ?? '';
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
    $id = $_POST['todo_id'] ?? '';
    $title = $_POST['title'] ?? '';
    $content = $_POST['content'] ?? '';
    $tags = $_POST['tags'] ?? '';
    $status = $_POST['status'] ?? '';
    $priority = $_POST['priority'] ?? '';
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
  public function getTodosSQL($queryType = "result")
  {
    // Pagination parameters
    $itemsPerPage = 12; // Number of results per page
    $page = isset($_GET['page']) ? (int) $_GET['page'] : 1; // Current page number
    $offset = ($page - 1) * $itemsPerPage; // Offset for LIMIT clause

    // Search keyword
    $keyword = isset($_GET['s']) ? $_GET['s'] : '';
    $keyword = '%' . $keyword . '%'; // Prepare for LIKE search

    // Filter last updated
    $lastUpdated = isset($_GET['last_updated']) ? $_GET['last_updated'] : '';

    // Filter by role (optional)
    $type = isset($_GET['type']) ? $_GET['type'] : '';

    $selectSql = $queryType === "result" ? "SELECT * FROM todos" : "SELECT COUNT(*) FROM todos";
    $sql = $selectSql . " WHERE user_id = $this->user_id ";

    if ($keyword !== '') {
      $sql .= " AND (title LIKE :keyword OR tags LIKE :keyword OR content LIKE :keyword)";
    }

    if ($type !== '') {
      $sql .= " AND type = :type";
    }

    list($startDate, $endDate) = getDateRange($lastUpdated);
    if ($lastUpdated !== '') {
      $sql .= " AND datetime(updated_at, '" . getTimezoneOffset() . "') BETWEEN :start_date AND :end_date";
    }

    // Sorting parameters (optional)
    $sortColumn = isset($_GET['sort']) ? $_GET['sort'] : 'updated_at'; // Default sort by updated_at
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
    if ($type !== '') {
      $stmt->bindParam(':type', $type, PDO::PARAM_STR);
    }
    if ($startDate && $endDate) {
      $stmt->bindParam(':start_date', $startDate,);
      $stmt->bindParam(':end_date', $endDate);
    }

    // Execute the query
    $stmt->execute();
    return $queryType === "result" ? $stmt->fetchAll(PDO::FETCH_ASSOC) : $stmt->fetchColumn();
  }

  // Handle listing all todos
  public function listTodos()
  {
    // return $this->todoService->getAllTodos();
    return [
      'list' => $this->getTodosSQL("result"),
      'count' => $this->getTodosSQL("count"),
    ];
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