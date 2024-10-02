<?php

require 'services/PlanService.php';

class PlanController
{
  private $planService;

  public function __construct()
  {
    global $pdo;
    $this->planService = new PlanService($pdo);
  }

  // Handle creating a new plan
  public function createPlan()
  {
    $title = $_POST['title'] ?? '';
    $content = $_POST['content'] ?? '';
    $status = $_POST['status'] ?? 'pending';
    $start_date = $_POST['start_date'] ?? '';
    $end_date = $_POST['end_date'] ?? '';

    if ($title) {
      $this->planService->createPlan($title, $content, $status, $start_date, $end_date);
      $_SESSION['message_type'] = 'success';
      $_SESSION['message'] = "Plan created successfully";
    } else {
      $_SESSION['message_type'] = 'danger';
      $_SESSION['message'] = "Plan title is required.";
    }

    header("Location: " . home_url("plans"));
    exit;
  }

  // Handle updating a plan
  public function updatePlan()
  {
    $id = $_POST['plan_id'] ?? null;
    $title = $_POST['title'] ?? '';
    $content = $_POST['content'] ?? '';
    $status = $_POST['status'] ?? 'pending';
    $start_date = $_POST['start_date'] ?? '';
    $end_date = $_POST['end_date'] ?? '';

    if ($id && $title) {
      $rowsAffected = $this->planService->updatePlan($id, $title, $content, $status, $start_date, $end_date);
      if ($rowsAffected) {
        $_SESSION['message_type'] = 'success';
        $_SESSION['message'] = "Plan updated successfully.";
      } else {
        $_SESSION['message_type'] = 'danger';
        $_SESSION['message'] = "Failed to update plan.";
      }
    } else {
      $_SESSION['message_type'] = 'danger';
      $_SESSION['message'] = "Plan ID and title are required.";
    }

    header("Location: " . home_url("plans/edit") . '?post_id=' . $id);
    exit;
  }

  // Handle deleting a plan
  public function deletePlan($id)
  {
    if ($id) {
      $rowsAffected = $this->planService->deletePlan($id);
      if ($rowsAffected) {
        $_SESSION['message_type'] = 'success';
        $_SESSION['message'] = "Plan deleted successfully.";
        header("Location: " . home_url("plans"));
        exit;
      } else {
        $_SESSION['message_type'] = 'danger';
        $_SESSION['message'] = "Failed to delete plan.";
      }
    } else {
      $_SESSION['message_type'] = 'danger';
      $_SESSION['message'] = "Failed to delete plan.";
    }

    header("Location: " . $_SERVER['REQUEST_URI']);
    exit;
  }

  // Handle listing all plans
  public function listPlans()
  {
    return $this->planService->getAllPlans();
  }

  // Handle viewing a single plan
  public function viewPlan($id)
  {
    if ($id) {
      return $this->planService->getPlanById($id);
    }

    $_SESSION['message_type'] = 'danger';
    $_SESSION['message'] = "Plan ID is required.";
    header("Location: " . $_SERVER['REQUEST_URI']);
    exit;
  }
}
