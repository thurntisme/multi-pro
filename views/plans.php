<?php
$pageTitle = "Plans";

require_once 'controllers/PlanController.php';
$planController = new PlanController();
$planLists = $planController->listPlans();

ob_start();

if (isset($_SESSION['message'])) {
  $messageType = $_SESSION['message_type'] ?? 'info';
  echo '<div class="alert alert-' . htmlspecialchars($messageType) . ' alert-dismissible fade show mb-4" role="alert">'
    . htmlspecialchars($_SESSION['message']) .
    '<button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>';

  unset($_SESSION['message']);
  unset($_SESSION['message_type']);
}

echo '<a class="btn btn-success" href="' . home_url("plans/new") . '">Add new</a>';

if (count($planLists) > 0) {
  echo '<div class="row mt-4">';
  foreach ($planLists as $plan) {
    $plan_id = $plan['id'];
    echo '<div class="col-xl-3 col-md-6 mb-4">
        <div class="card text-center">
          <div class="card-header">
            ' . $plan['title'] . '
          </div>
          <div class="card-body">
            <p class="card-text">' . $plan['content'] . '</p>
            <a href="' . home_url("plans/view") . '?post_id=' . $plan_id . '" class="btn btn-primary btn-sm">View</a>
          </div>
          <div class="card-footer text-muted">
            ' . timeAgo($commonController->convertDateTime($plan['updated_at'])) . '
          </div>
        </div></div>';
  }
  echo '</div>';
}

$pageContent = ob_get_clean();

include 'layout.php';
