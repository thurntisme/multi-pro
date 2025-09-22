<?php

class ProjectService
{
  private $pdo;
  private $user_id;

  public function __construct($pdo)
  {
    global $user_id;
    $this->pdo = $pdo;
    $this->user_id = $user_id;
  }

  // Create a new project
  public function createProject($title, $description, $start_date, $end_date, $type, $dev_url, $staging_url, $production_url, $source_url)
  {
    $sql = "INSERT INTO projects (title, description, start_date, end_date, type, dev_url, staging_url, production_url, source_url, user_id) VALUES (:title, :description, :start_date, :end_date, :type, :dev_url, :staging_url, :production_url, :source_url,  :user_id)";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([':title' => $title, ':description' => $description, ':start_date' => $start_date, ':end_date' => $end_date, ':type' => $type, ':user_id' => $this->user_id, ':dev_url' => $dev_url, ':staging_url' => $staging_url, ':production_url' => $production_url, ':source_url' => $source_url]);

    return $this->pdo->lastInsertId();
  }

  // Read a project by ID
  public function getProjectById($id)
  {
    $sql = "SELECT * FROM projects WHERE id = :id";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([':id' => $id]);

    return $stmt->fetch(PDO::FETCH_ASSOC);
  }

  // Update a project
  public function updateProject($id, $title, $description, $start_date, $end_date, $status, $type, $dev_url, $staging_url, $production_url, $source_url)
  {
    $sql = "UPDATE projects SET title = :title, description = :description, start_date = :start_date, end_date = :end_date, status = :status, type = :type, dev_url = :dev_url, staging_url = :staging_url, production_url = :production_url, source_url = :source_url, updated_at = CURRENT_TIMESTAMP WHERE id = :id";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([':id' => $id, ':title' => $title, ':description' => $description, ':start_date' => $start_date, ':end_date' => $end_date, ':status' => $status, ':type' => $type, ':dev_url' => $dev_url, ':staging_url' => $staging_url, ':production_url' => $production_url, ':source_url' => $source_url]);

    return $stmt->rowCount();
  }

  // Delete a project
  public function deleteProject($id)
  {
    $sql = "DELETE FROM projects WHERE id = :id";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([':id' => $id]);

    return $stmt->rowCount();
  }

  // Get all projects
  public function getProjectsSQL($queryType = "result")
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

    $selectSql = $queryType === "result" ? "SELECT * FROM projects" : "SELECT COUNT(*) FROM projects";
    $sql = $selectSql . " WHERE user_id = $this->user_id ";

    if ($keyword !== '') {
      $sql .= " AND title LIKE :keyword";
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

  // Get all projects
  public function getProjectListData()
  {
    return [
      'list' => $this->getProjectsSQL("result"),
      'count' => $this->getProjectsSQL("count"),
    ];
  }
}
