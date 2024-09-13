<?php

require 'services/ProjectService.php';

class ProjectController
{
  private $projectService;

  public function __construct()
  {
    global $pdo;
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

      header("Location: " . home_url("projects/list"));
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
      } else {
        $_SESSION['message_type'] = 'danger';
        $_SESSION['message'] = "Failed to update project.";
      }
    } else {
      $_SESSION['message_type'] = 'danger';
      $_SESSION['message'] = "Project ID and title are required.";
    }

    header("Location: " . home_url("projects/edit?post_id=" . $id));
    exit;
  }

  // Handle deleting a project
  public function deleteProject($id)
  {
    if ($id) {
      $rowsAffected = $this->projectService->deleteProject($id);
      if ($rowsAffected) {
        $_SESSION['message_type'] = 'success';
        $_SESSION['message'] = "Project deleted successfully.";
        header("Location: " . home_url("projects/list"));
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
  public function listProjectData()
  {
    return $this->projectService->getProjectListData();
  }

  // Handle viewing a single project
  public function viewProject($post_id)
  {
    if ($post_id) {
      return $this->projectService->getProjectById($post_id);
    }

    $_SESSION['message_type'] = 'danger';
    $_SESSION['message'] = "Project ID is required.";
    header("Location: " . $_SERVER['REQUEST_URI']);
    exit;
  }
}
