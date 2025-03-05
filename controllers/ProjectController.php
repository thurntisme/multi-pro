<?php

require 'services/ProjectService.php';

class ProjectController
{
  private $user_id;
  private $pdo;
  private $projectService;

  public function __construct()
  {
    global $pdo;
    global $user_id;
    $this->user_id = $user_id;
    $this->pdo = $pdo;
    $this->projectService = new ProjectService($pdo);
  }

  // Handle creating a new project
  public function createProject()
  {
    $title = $_POST['title'] ?? '';
    $description = $_POST['description'] ?? '';
    $start_date = $_POST['start_date'] ?? '';
    $end_date = $_POST['end_date'] ?? '';
    $type = $_POST['type'] ?? '';
    $dev_url = $_POST['dev_url'] ?? '';
    $staging_url = $_POST['staging_url'] ?? '';
    $production_url = $_POST['production_url'] ?? '';
    $source_url = $_POST['source_url'] ?? '';

    if ($title) {
      $this->projectService->createProject($title, $description, $start_date, $end_date, $type, $dev_url, $staging_url, $production_url, $source_url);
      $_SESSION['message_type'] = 'success';
      $_SESSION['message'] = "Project created successfully";

      header("Location: " . home_url("app/project"));
      exit;
    } else {
      $_SESSION['message_type'] = 'danger';
      $_SESSION['message'] = "Project title is required.";
    }
  }

  // Handle updating a project
  public function updateProject()
  {
    $id = $_POST['project_id'] ?? null;
    $title = $_POST['title'] ?? '';
    $description = $_POST['description'] ?? '';
    $start_date = $_POST['start_date'] ?? '';
    $end_date = $_POST['end_date'] ?? '';
    $type = $_POST['type'] ?? '';
    $status = $_POST['status'] ?? '';
    $dev_url = $_POST['dev_url'] ?? '';
    $staging_url = $_POST['staging_url'] ?? '';
    $production_url = $_POST['production_url'] ?? '';
    $source_url = $_POST['source_url'] ?? '';

    if ($id && $title) {
      $rowsAffected = $this->projectService->updateProject($id, $title, $description, $start_date, $end_date, $status, $type, $dev_url, $staging_url, $production_url, $source_url);
      if ($rowsAffected) {
        $_SESSION['message_type'] = 'success';
        $_SESSION['message'] = "Project updated successfully.";
      } else {
        $_SESSION['message_type'] = 'danger';
        $_SESSION['message'] = "Failed to update project.";
      }
    } else {
      $_SESSION['message_type'] = 'danger';
      $_SESSION['message'] = "Project ID and title are required.";
    }

    header("Location: " . home_url("app/project/edit?post_id=" . $id));
    exit;
  }

  // Handle deleting a project
  public function deleteProject()
  {
    $id = $_POST['post_id'] ?? null;
    if ($id) {
      $rowsAffected = $this->projectService->deleteProject($id);
      if ($rowsAffected) {
        $_SESSION['message_type'] = 'success';
        $_SESSION['message'] = "Project deleted successfully.";
        header("Location: " . home_url("app/project"));
        exit;
      } else {
        $_SESSION['message_type'] = 'danger';
        $_SESSION['message'] = "Failed to delete project.";
      }
    } else {
      $_SESSION['message_type'] = 'danger';
      $_SESSION['message'] = "Project ID is required.";
    }

    header("Location: " . $_SERVER['REQUEST_URI']);
    exit;
  }

  // Handle listing all projects
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
  public function listProjects()
  {
    return [
      'list' => $this->getProjectsSQL("result"),
      'count' => $this->getProjectsSQL("count"),
    ];
  }

  // Handle viewing a single project
  public function viewProject($post_id)
  {
    if ($post_id) {
      $sql = "SELECT * FROM projects WHERE id = :id";
      $stmt = $this->pdo->prepare($sql);
      $stmt->execute([':id' => $post_id]);

      $taskSql = "SELECT * FROM tasks WHERE project_id = :project_id ORDER BY updated_at DESC";
      $taskStmt = $this->pdo->prepare($taskSql);
      $taskStmt->execute([':project_id' => $post_id]);

      $result = $stmt->fetch(PDO::FETCH_ASSOC);
      $result['tasks'] = $taskStmt->fetchAll(PDO::FETCH_ASSOC);

      return $result;
    }

    $_SESSION['message_type'] = 'danger';
    $_SESSION['message'] = "Project ID is required.";
    header("Location: " . $_SERVER['REQUEST_URI']);
    exit;
  }

  public function createTask()
  {
    $project_id = $_POST['project_id'] ?? '';
    $task_title = $_POST['task_title'] ?? '';
    $task_description = $_POST['task_description'] ?? '';
    $task_due_date = $_POST['task_due_date'] ?? '';

    if ($project_id) {
      $sql = "INSERT INTO tasks (project_id, title, description, due_date) VALUES (:project_id, :title, :description, :due_date)";
      $stmt = $this->pdo->prepare($sql);
      $stmt->execute([':project_id' => $project_id, ':title' => $task_title, ':description' => $task_description, ':due_date' => $task_due_date]);

      $result = $this->pdo->lastInsertId();

      if ($result) {
        $_SESSION['message_type'] = 'success';
        $_SESSION['message'] = "Task created successfully";
      } else {
        $_SESSION['message_type'] = 'danger';
        $_SESSION['message'] = "Project ID is required.";
      }

      header("Location: " . $_SERVER['REQUEST_URI']);
      exit;
    } else {
      $_SESSION['message_type'] = 'danger';
      $_SESSION['message'] = "Task title is required.";
    }
  }

  public function deleteTask()
  {
    $id = $_POST['task_id'] ?? null;
    $project_id = $_POST['project_id'] ?? '';
    if ($id) {
      $sql = "DELETE FROM tasks WHERE id = :id";
      $stmt = $this->pdo->prepare($sql);
      $stmt->execute([':id' => $id]);
  
      $rowsAffected = $stmt->rowCount();
      if ($rowsAffected) {
        $_SESSION['message_type'] = 'success';
        $_SESSION['message'] = "Task deleted successfully.";
        if (!empty($project_id)){
          header("Location: " . home_url('app/project/detail?id=' . $project_id));
          exit;
        }
      } else {
        $_SESSION['message_type'] = 'danger';
        $_SESSION['message'] = "Failed to delete task.";
      }
    } else {
      $_SESSION['message_type'] = 'danger';
      $_SESSION['message'] = "Task ID is required.";
    }

    header("Location: " . $_SERVER['REQUEST_URI']);
    exit;
  }

  public function viewTask($post_id)
  {
    if ($post_id) {
      $sql = "SELECT * FROM tasks WHERE id = :id";
      $stmt = $this->pdo->prepare($sql);
      $stmt->execute([':id' => $post_id]);

      return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    $_SESSION['message_type'] = 'danger';
    $_SESSION['message'] = "Task ID is required.";
    header("Location: " . $_SERVER['REQUEST_URI']);
    exit;
  }

  public function changeStatusTask()
  {
      $id = $_POST['task_id'] ?? null;
      $status = $_POST['status'] ?? 'not_started';
      if ($id) {
          $rowsAffected = $this->changeStatusTaskCallback($id, $status);
          if ($rowsAffected) {
              $_SESSION['message_type'] = 'success';
              $_SESSION['message'] = "Task's status has been successfully updated.";
          } else {
              $_SESSION['message_type'] = 'danger';
              $_SESSION['message'] = "Failed to update task's status.";
          }
      } else {
          $_SESSION['message_type'] = 'danger';
          $_SESSION['message'] = "Failed to update task's status.";
      }

      header("Location: " . $_SERVER['REQUEST_URI']);
      exit;
  }

  function changeStatusTaskCallback($id, $status)
  {
      $sql = "UPDATE tasks SET status = :status WHERE id = :id";
      $stmt = $this->pdo->prepare($sql);
      $stmt->execute([':status' => $status, ':id' => $id]);

      return $stmt->rowCount();
  }
}
