<?php
$pageTitle = "Plan";
$modify_type = getLastSegmentFromUrl();

require_once 'controllers/PlanController.php';

$planController = new PlanController();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
  if (isset($_GET['post_id'])) {
    $post_id = $_GET['post_id'];
    $planData = $planController->viewPlan($post_id);
  } else {
    echo "No post ID found in the URL.";
  }
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (isset($_POST['action_name'])) {
    if ($_POST['action_name'] === 'delete_plan' && isset($_GET['post_id'])) {
      $planController->deletePlan($_GET['post_id']);
    }
  }
}

ob_start();

echo '
<div class="jumbotron bg-light">
  <div class="row">
    <div class="col-10 mx-auto">';

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

echo '<div class="row">';
echo '<div class="col-8">
          <div class="card">
            <div class="card-body pt-3 pb-1 px-3">
              <h1 class="text-gray-900">' . $planData['title'] . '</h1>
              <div class="mt-4">' . $planData['content'] . '</div>
              <div class="mt-4 border-top">
                <div class="row py-3">
                  <div class="col-4">
                    <h5 class="text-uppercase text-gray-800 font-weight-bold">Start Date:</h5>
                    <h6>' . ($planData['start_date'] ? $commonController->convertDate($planData['start_date']) : "") . '</h6>
                  </div>
                  <div class="col-4">
                    <h5 class="text-uppercase text-gray-800 font-weight-bold">End Date:</h5>
                    <h6>' . ($planData['end_date'] ? $commonController->convertDate($planData['end_date']) : "") . '</h6>
                  </div>
                  <div class="col-4">
                    <h5 class="text-uppercase text-gray-800 font-weight-bold">Status:</h5>
                    <h6>' . renderStatusBadge($planData['status']) . '</h6>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>';
echo '<div class="col-4">
          <div class="card">
            <div class="card-body p-3">
              <h6 class="text-gray-800">
            Created Date: ' . $commonController->convertDateTime($planData['created_at']) . '</h6>
              <h6 class="text-gray-800">
            Updated Date: ' . timeAgo($commonController->convertDateTime($planData['updated_at'])) . '</h6>
              <div class="d-flex border-top mt-3 pt-3">
                <a href="' . home_url("plans/edit") . '?post_id=' . $post_id . '" class="btn btn-primary btn-sm" style="width: 60px">Edit</a>

                <form method="POST" action="' . home_url("plans/view") . '?post_id=' . $post_id . '">
                  <input type="hidden" name="action_name" value="delete_plan">
                  <button type="submit" class="btn btn-danger btn-sm ml-2" style="width: 60px">Delete</button>
                </form>
              </div>
            </div>
          </div>
        </div>';
echo '</div>';

echo '
      </div>
    </div>
  </div>';

$pageContent = ob_get_clean();

include 'layout.php';
