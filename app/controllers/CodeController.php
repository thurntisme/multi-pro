<?php

require 'services/CodeService.php';

class CodeController
{
  private $user_id;
  private $pdo;
  private $codeService;

  public function __construct()
  {
    global $user_id;
    global $pdo;
    $this->user_id = $user_id;
    $this->pdo = $pdo;
    $this->codeService = new CodeService($pdo);
  }

  // Handle creating a new code
  public function createCode()
  {
    $title = $_POST['title'] ?? '';
    $content = $_POST['content'] ?? '';
    $tags = $_POST['tags'] ?? '';
    $url = $_POST['url'] ?? '';

    if ($title) {
      $this->codeService->createCode($title, $content, $tags, $url);
      $_SESSION['message_type'] = 'success';
      $_SESSION['message'] = "Code created successfully";
    } else {
      $_SESSION['message_type'] = 'danger';
      $_SESSION['message'] = "Failed to create code";
    }

    header("Location: " . home_url("code"));
    exit;
  }

  // Handle updating a code
  public function updateCode()
  {
    $id = $_POST['code_id'] ?? '';
    $title = $_POST['title'] ?? '';
    $content = $_POST['content'] ?? '';
    $tags = $_POST['tags'] ?? '';
    $url = $_POST['url'] ?? '';

    if ($id && $title) {
      $rowsAffected = $this->codeService->updateCode($id, $title, $content, $tags, $url);
      if ($rowsAffected) {
        $_SESSION['message_type'] = 'success';
        $_SESSION['message'] = "Code updated successfully.";
      } else {
        $_SESSION['message_type'] = 'danger';
        $_SESSION['message'] = "Failed to update code.";
      }
    } else {
      $_SESSION['message_type'] = 'danger';
      $_SESSION['message'] = "Code ID and service name are required.";
    }

    header("Location: " . home_url("code/edit") . '?id=' . $id);
    exit;
  }

  // Handle deleting a code
  public function deleteCode()
  {
    $id = $_POST['post_id'] ?? null;
    if ($id) {
      $rowsAffected = $this->codeService->deleteCode($id);
      if ($rowsAffected) {
        $_SESSION['message_type'] = 'success';
        $_SESSION['message'] = "Code deleted successfully.";
      } else {
        $_SESSION['message_type'] = 'danger';
        $_SESSION['message'] = "Failed to delete code.";
      }
    } else {
      $_SESSION['message_type'] = 'danger';
      $_SESSION['message'] = "Failed to delete code.";
    }

    header("Location: " . home_url("code"));
    exit;
  }

  // Get all codes
  public function getCodesSQL($queryType = "result")
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

    $selectSql = $queryType === "result" ? "SELECT * FROM codes" : "SELECT COUNT(*) FROM codes";
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

  // Handle listing all codes
  public function listCodes()
  {
    // return $this->codeService->getAllCodes();
    return [
      'list' => $this->getCodesSQL("result"),
      'count' => $this->getCodesSQL("count"),
    ];
  }

  // Handle viewing a single code
  public function viewCode($id)
  {
    if ($id) {
      return $this->codeService->getCodeById($id);
    }

    $_SESSION['message_type'] = 'danger';
    $_SESSION['message'] = "Code ID is required.";
    header("Location: " . $_SERVER['REQUEST_URI']);
    exit;
  }
}