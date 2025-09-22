<?php

require 'services/GitService.php';

class GitController
{
  private $user_id;
  private $pdo;
  private $gitService;

  public function __construct()
  {
    global $user_id;
    global $pdo;
    $this->user_id = $user_id;
    $this->pdo = $pdo;
    $this->gitService = new GitService($pdo);
  }

  // Handle creating a new git
  public function createGit()
  {
    $title = $_POST['title'] ?? '';
    $content = $_POST['content'] ?? '';
    $tags = $_POST['tags'] ?? '';
    $url = $_POST['url'] ?? '';

    if ($title) {
      $this->gitService->createGit($title, $content, $tags, $url);
      $_SESSION['message_type'] = 'success';
      $_SESSION['message'] = "Git created successfully";
    } else {
      $_SESSION['message_type'] = 'danger';
      $_SESSION['message'] = "Failed to create git";
    }

    header("Location: " . home_url("git"));
    exit;
  }

  // Handle updating a git
  public function updateGit()
  {
    $id = $_POST['git_id'] ?? '';
    $title = $_POST['title'] ?? '';
    $content = $_POST['content'] ?? '';
    $tags = $_POST['tags'] ?? '';
    $url = $_POST['url'] ?? '';

    if ($id && $title) {
      $rowsAffected = $this->gitService->updateGit($id, $title, $content, $tags, $url);
      if ($rowsAffected) {
        $_SESSION['message_type'] = 'success';
        $_SESSION['message'] = "Git updated successfully.";
      } else {
        $_SESSION['message_type'] = 'danger';
        $_SESSION['message'] = "Failed to update git.";
      }
    } else {
      $_SESSION['message_type'] = 'danger';
      $_SESSION['message'] = "Git ID and service name are required.";
    }

    header("Location: " . home_url("git/edit") . '?id=' . $id);
    exit;
  }

  // Handle deleting a git
  public function deleteGit()
  {
    $id = $_POST['post_id'] ?? null;
    if ($id) {
      $rowsAffected = $this->gitService->deleteGit($id);
      if ($rowsAffected) {
        $_SESSION['message_type'] = 'success';
        $_SESSION['message'] = "Git deleted successfully.";
      } else {
        $_SESSION['message_type'] = 'danger';
        $_SESSION['message'] = "Failed to delete git.";
      }
    } else {
      $_SESSION['message_type'] = 'danger';
      $_SESSION['message'] = "Failed to delete git.";
    }

    header("Location: " . home_url("git"));
    exit;
  }

  // Get all gits
  public function getGitsSQL($queryType = "result")
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

    $selectSql = $queryType === "result" ? "SELECT * FROM gits" : "SELECT COUNT(*) FROM gits";
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

  // Handle listing all gits
  public function listGits()
  {
    // return $this->gitService->getAllGits();
    return [
      'list' => $this->getGitsSQL("result"),
      'count' => $this->getGitsSQL("count"),
    ];
  }

  // Handle viewing a single git
  public function viewGit($id)
  {
    if ($id) {
      return $this->gitService->getGitById($id);
    }

    $_SESSION['message_type'] = 'danger';
    $_SESSION['message'] = "Git ID is required.";
    header("Location: " . $_SERVER['REQUEST_URI']);
    exit;
  }
}