<?php
$pageTitle = "Budget";

require_once 'controllers/BudgetController.php';
$budgetController = new BudgetController();
$commonController = new CommonController();

if (isset($_POST) && isset($_POST["action"])) {
    if ($_POST["action"] == "add_budget") {
        $budgetController->createBudget();
    }
    if ($_POST["action"] == "delete_budget") {
        $budgetController->deleteBudget();
    }
}

$list = $budgetController->listBudgets();

ob_start();

include_once DIR . "/components/finance-top.php";

echo '<div class="row mt-4">
    <div class="col-4">';

echo '<form action="' . home_url("finance/budget") . '" method="POST">
        <input type="hidden" name="action" value="add_budget" />
      <!-- Title Input -->
      <div class="form-group">
          <label for="title">Title</label>
          <input type="text" class="form-control" name="title" placeholder="Enter title">
      </div>

      <!-- Amount Input -->
      <div class="form-group">
          <label for="amount">Amount</label>
          <input type="number" class="form-control" name="amount" placeholder="Enter amount">
      </div>

      <!-- Description Input -->
      <div class="form-group">
          <label for="description">Description</label>
          <textarea class="form-control" name="description" rows="3" placeholder="Enter description"></textarea>
      </div>

      <!-- Submit Button -->
      <button type="submit" class="btn btn-primary">Submit</button>
  </form>
  </div>
  <div class="col-8">';

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

echo '<ul class="list-group">';

if (count($list) > 0) {
    foreach ($list as $item) {
        echo '
          <li class="list-group-item d-flex justify-content-between align-items-center">
              <div>
                <strong>' . $item['title'] . '</strong> - $' . $item['amount'] . ' <br>
                <small>' . $item['description'] . '</small> <br>
                <small class="text-muted">Added on: ' . $systemController->convertDateTime($item['created_at']) . '</small>
              </div>
              <form action="' . home_url("finance/budget") . '" method="POST">
                <input type="hidden" name="budget_id" value="' . $item['id'] . '" />
                <input type="hidden" name="action" value="delete_budget" />
                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
              </form>
          </li>';
    }
}

echo '</ul></div></div>';

$pageContent = ob_get_clean();
