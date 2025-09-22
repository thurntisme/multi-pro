<?php

require 'services/ReportWorkingService.php';

class ReportWorkingController
{
  private $user_id;
  private $pdo;
  private $reportWorkingService;

  public function __construct()
  {
    global $user_id;
    global $pdo;
    $this->user_id = $user_id;
    $this->pdo = $pdo;
    $this->reportWorkingService = new ReportWorkingService($pdo);
  }

  // Handle creating a new todo
  public function createReport()
  {
    $title = $_POST['title'] ?? '';
    $project = $_POST['project'] ?? '';
    $content = $_POST['content'] ?? '';
    $tags = $_POST['tags'] ?? '';
    $note = $_POST['note'] ?? '';
    $working_date = $_POST['working_date'] ?? '';

    if ($title) {
      $this->reportWorkingService->createReport($title, $project, $content, $tags, $note, $working_date);
      $_SESSION['message_type'] = 'success';
      $_SESSION['message'] = "ReportWorking created successfully";
    } else {
      $_SESSION['message_type'] = 'danger';
      $_SESSION['message'] = "Failed to create todo";
    }

    header("Location: " . home_url("report-working"));
    exit;
  }

  // Handle updating a todo
  public function updateReport()
  {
    $id = $_POST['report_id'] ?? '';
    $title = $_POST['title'] ?? '';
    $project = $_POST['project'] ?? '';
    $content = $_POST['content'] ?? '';
    $tags = $_POST['tags'] ?? '';
    $note = $_POST['note'] ?? '';
    $working_date = $_POST['working_date'] ?? '';

    if ($id && $title) {
      $rowsAffected = $this->reportWorkingService->updateReport($id, $project, $project, $content, $tags, $note, $working_date);
      if ($rowsAffected) {
        $_SESSION['message_type'] = 'success';
        $_SESSION['message'] = "ReportWorking updated successfully.";
      } else {
        $_SESSION['message_type'] = 'danger';
        $_SESSION['message'] = "Failed to update todo.";
      }
    } else {
      $_SESSION['message_type'] = 'danger';
      $_SESSION['message'] = "ReportWorking ID and service name are required.";
    }

    header("Location: " . home_url("report-working/edit") . '?id=' . $id);
    exit;
  }

  // Handle deleting a todo
  public function deleteReport()
  {
    $id = $_POST['post_id'] ?? null;
    if ($id) {
      $rowsAffected = $this->reportWorkingService->deleteReport($id);
      if ($rowsAffected) {
        $_SESSION['message_type'] = 'success';
        $_SESSION['message'] = "ReportWorking deleted successfully.";
      } else {
        $_SESSION['message_type'] = 'danger';
        $_SESSION['message'] = "Failed to delete todo.";
      }
    } else {
      $_SESSION['message_type'] = 'danger';
      $_SESSION['message'] = "Failed to delete todo.";
    }

    header("Location: " . home_url("report-working"));
    exit;
  }

  // Get all todos
  public function getReportWorkingsSQL($queryType = "result")
  {
    // Pagination parameters
    $itemsPerPage = 12; // Number of results per page
    $page = isset($_GET['page']) ? (int) $_GET['page'] : 1; // Current page number
    $offset = ($page - 1) * $itemsPerPage; // Offset for LIMIT clause

    // Search keyword
    $keyword = isset($_GET['s']) ? $_GET['s'] : '';
    $keyword = '%' . $keyword . '%'; // Prepare for LIKE search

    // Filter last updated
    $workingDate = isset($_GET['working_date']) ? $_GET['working_date'] : '';

    // Filter by role (optional)
    $type = isset($_GET['type']) ? $_GET['type'] : '';

    $selectSql = $queryType === "result" ? "SELECT * FROM report_working" : "SELECT COUNT(*) FROM report_working";
    $sql = $selectSql . " WHERE user_id = $this->user_id ";

    if ($keyword !== '') {
      $sql .= " AND (title LIKE :keyword OR tags LIKE :keyword OR content LIKE :keyword)";
    }

    if ($type !== '') {
      $sql .= " AND type = :type";
    }

    $startDate = '';
    $endDate = '';
    if ($workingDate !== '') {
      $date_array = explode('to', $workingDate);
      $date_array = array_map('trim', $date_array);
      list($startDate, $endDate) = $date_array;
      $endDate = $endDate ?? $startDate;
      $sql .= " AND working_date BETWEEN :start_date AND :end_date";
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
      $stmt->bindParam(':start_date', $startDate, );
      $stmt->bindParam(':end_date', $endDate);
    }

    // Execute the query
    $stmt->execute();
    return $queryType === "result" ? $stmt->fetchAll(PDO::FETCH_ASSOC) : $stmt->fetchColumn();
  }

  // Handle listing all todos
  public function listReportWorkings()
  {
    // return $this->reportWorkingService->getAllReportWorkings();
    return [
      'list' => $this->getReportWorkingsSQL("result"),
      'count' => $this->getReportWorkingsSQL("count"),
    ];
  }

  // Handle viewing a single todo
  public function viewReport($id)
  {
    if ($id) {
      return $this->reportWorkingService->getReportById($id);
    }

    $_SESSION['message_type'] = 'danger';
    $_SESSION['message'] = "ReportWorking ID is required.";
    header("Location: " . $_SERVER['REQUEST_URI']);
    exit;
  }
}