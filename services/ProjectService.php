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
  public function createProject($title, $description, $start_date, $end_date, $type)
  {
    $sql = "INSERT INTO projects (title, description, start_date, end_date, type, user_id) VALUES (:title, :description, :start_date, :end_date, :type, :user_id)";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([':title' => $title, ':description' => $description, ':start_date' => $start_date, ':end_date' => $end_date, ':type' => $type, ':user_id' => $this->user_id]);

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
  public function updateProject($id, $title, $description, $start_date, $end_date, $status, $type)
  {
    $sql = "UPDATE projects SET title = :title, description = :description, start_date = :start_date, end_date = :end_date, status = :status, type = :type, updated_at = CURRENT_TIMESTAMP WHERE id = :id";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([':id' => $id, ':title' => $title, ':description' => $description, ':start_date' => $start_date, ':end_date' => $end_date, ':status' => $status, ':type' => $type]);

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
  public function getAllProjects()
  {
    // Pagination parameters
    $itemsPerPage = 12; // Number of results per page
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Current page number
    $offset = ($page - 1) * $itemsPerPage; // Offset for LIMIT clause

    // Search keyword
    $keyword = isset($_GET['s']) ? $_GET['s'] : '';
    $keyword = '%' . $keyword . '%'; // Prepare for LIKE search

    // Filter by role (optional)
    $type = isset($_GET['type']) ? $_GET['type'] : '';

    $sql = "SELECT * FROM projects WHERE user_id = $this->user_id ";

    if ($keyword !== '') {
      $sql .= " AND title LIKE :keyword";
    }

    if ($type !== '') {
      $sql .= " AND type = :type";
    }

    // Sorting parameters (optional)
    $sortColumn = isset($_GET['sort']) ? $_GET['sort'] : 'updated_at'; // Default sort by updated_at
    $sortOrder = isset($_GET['order']) && in_array(strtoupper($_GET['order']), ['ASC', 'DESC']) ? strtoupper($_GET['order']) : 'DESC'; // Default to DESC

    // Add the ORDER BY clause dynamically
    $sql .= " ORDER BY $sortColumn $sortOrder";

    // Add pagination (LIMIT and OFFSET)
    $sql .= " LIMIT $itemsPerPage OFFSET $offset";

    // Prepare the query
    $stmt = $this->pdo->prepare($sql);

    // Bind parameters
    if ($keyword != '') {
      $stmt->bindParam(':keyword', $keyword, PDO::PARAM_STR);
    }
    if ($type !== '') {
      $stmt->bindParam(':type', $type, PDO::PARAM_STR);
    }

    // Execute the query
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }
}
