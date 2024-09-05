<?php
$pageTitle = "Plan";
$modify_type = getLastSegmentFromUrl();

require_once 'controllers/PlanController.php';

$planController = new PlanController();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if ($modify_type === "new") {
    $planController->createPlan();
  }
  if ($modify_type === "edit") {
    $planController->updatePlan();
  }
};
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
  if (isset($_GET['post_id'])) {
    $post_id = $_GET['post_id'];
    $planData = $planController->viewPlan($post_id);
  }
}
if ($modify_type === "new") {
  $back_url = home_url("plans");
} else if ($modify_type == 'edit') {
  $back_url = home_url("plans/view") . '?post_id=' . $post_id;
}

ob_start();

echo '<div class="jumbotron bg-light">
  <div class="row">
    <div class="col-6 mx-auto">';

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

echo '<form method="POST" action="' . home_url("plans/" . $modify_type) . '">
        <div class="form-group">
          <label for="title">Title:</label>
          <input type="text" class="form-control" id="title" name="title" value="' . ($planData['title'] ?? "") . '">
        </div>
        <div class="form-group">
          <label for="name">Content:</label>
          <textarea name="content" class="form-control">' . ($planData['content'] ?? "") . '</textarea>
        </div>';

if ($modify_type == 'edit') {
  echo '<input type="hidden" name="plan_id" value="' . $post_id . '" />';

  $options = '';
  foreach (DEFAULT_PLAN_STATUS as $status) {
    $selected = 'pending' === $status[0] ? 'selected' : '';
    if (isset($planData['status'])) {
      $selected = $planData['status'] === $status[0] ? 'selected' : '';
    }
    $options .= '<option value="' . htmlspecialchars($status[0]) . '" ' . $selected . '>' .
      htmlspecialchars($status[1]) .
      '</option>';
  }
  echo '<div class="form-row">
    <div class="form-group col-md-6">
    <label for="status">Status:</label>
    <select class="form-control" class="form-control" name="status" id="status">'
    . $options .
    '</select>
  </div></div>';
}

echo    '<div class="form-row">
          <div class="form-group col-md-6">
            <label for="start_date">Start Date:</label>
            <input type="date" class="form-control" id="start_date" name="start_date" min="' . date('Y-m-d') . '" value="' . ($planData['start_date'] ?? "") . '">
          </div>
          <div class="form-group col-md-6">
            <label for="end_date">End Date:</label>
            <input type="date" class="form-control" id="end_date" name="end_date" min="' . date('Y-m-d') . '" value="' . ($planData['end_date'] ?? "") . '">
          </div>
        </div>
        <a href="' . $back_url . '" class="btn btn-light">Back</a>
        <button type="submit" class="btn btn-primary">Save</button>
      </form>';

echo '</div>
  </div>
</div>';

$pageContent = ob_get_clean();

include 'layout.php';
