<?php
$pageTitle = "Expenses";

require_once 'controllers/ExpenseController.php';
$expenseController = new ExpenseController();
$commonController = new CommonController();

if (isset($_POST) && isset($_POST["action"])) {
    if ($_POST["action"] == "add_expense") {
        $expenseController->createExpense();
    }
    if ($_POST["action"] == "delete_expense") {
        $expenseController->deleteExpense();
    }
}

$list = $expenseController->listExpenses();

ob_start();

include_once DIR . "/components/finance-top.php";

echo '<div class="row mt-4">
    <div class="col-4">
    <div class="card">
        <div class="card-header d-flex align-items-center justify-content-between">
            <div class="card-title mb-0">
                Overview
            </div>
            <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#staticBackdrop">Add New</button>
        </div>
        <div class="card-body">
            <canvas id="myDoughnutChart" class="mx-auto"></canvas>
            <ul class="list-group list-group-flush mt-4">
                <li class="list-group-item d-flex align-items-center px-0">An item <span class="ml-auto mr-2 pr-2 border-right border-dark">12 vnd</span> <b>20%</b></li>
                <li class="list-group-item">A second item</li>
                <li class="list-group-item">A third item</li>
                <li class="list-group-item">A fourth item</li>
                <li class="list-group-item">And a fifth one</li>
            </ul>
        </div>
    </div>
    ';

$categoryMap = [];
$cate_dropdown = '
<div class="form-group">
                    <label for="category">Category</label>
                    <select class="form-control" name="category_id" name="category" required>';
foreach (DEFAULT_EXPENSE_CATEGORIES as $category) {
    $categoryMap[$category['id']] = $category['name'];
    $cate_dropdown .= '<option value="' . $category["id"] . '">' . $category["name"] . '</option>';
}
$cate_dropdown .= '</select>
                </div>';

echo '
<div class="modal fade" id="staticBackdrop" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="staticBackdropLabel">Add new expense</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
<form action="' . home_url("finance/expenses") . '" method="POST">
        <input type="hidden" name="action" value="add_expense" />
      <!-- Title Input -->
      <div class="form-group">
          <label for="title">Title</label>
          <input type="text" class="form-control" name="title" placeholder="Enter title">
      </div>
      
      <!-- Category Dropdown -->
      ' . $cate_dropdown . '

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
    </div>
  </div>
</div>
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

echo '<ul class="list-group">';

if (count($list) > 0) {
    foreach ($list as $item) {
        echo '
          <li class="list-group-item d-flex justify-content-between align-items-center">
              <div>
                <strong>' . $item['title'] . '</strong> - $' . $item['amount'] . ' <br>
                <small>Category: ' . $categoryMap[$item['category_id']] . '</small> <br>
                <small>' . $item['description'] . '</small> <br>
                <small class="text-muted">Added on: ' . $commonController->convertDateTime($item['created_at']) . '</small>
              </div>
              <form action="' . home_url("finance/expenses") . '" method="POST">
                <input type="hidden" name="expense_id" value="' . $item['id'] . '" />
                <input type="hidden" name="action" value="delete_expense" />
                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
              </form>
          </li>';
    }
}

echo '</ul></div></div>';

$pageContent = ob_get_clean();

ob_start();
echo '<!-- Chart.js Library -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>';
$additionCss = ob_get_clean();

ob_start();
echo "<script type='text/javascript'>
    // Get the context of the canvas element to render the chart
        const ctx = document.getElementById('myDoughnutChart').getContext('2d');

        // Data and configuration for the doughnut chart
        const myDoughnutChart = new Chart(ctx, {
            type: 'doughnut',  // Specify chart type as doughnut
            data: {
                labels: ['Category A', 'Category B', 'Category C', 'Category D'],  // Labels for each segment
                datasets: [{
                    label: 'Dataset Example',
                    data: [30, 50, 70, 20],  // Data values for each category
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.6)',   // Color for Category A
                        'rgba(54, 162, 235, 0.6)',   // Color for Category B
                        'rgba(255, 206, 86, 0.6)',   // Color for Category C
                        'rgba(75, 192, 192, 0.6)'    // Color for Category D
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,  // Make the chart responsive
                plugins: {
                    legend: {
                        position: 'top',  // Show legend at the top
                    },
                    tooltip: {
                        enabled: true,  // Enable tooltips
                    }
                },
                cutout: '70%',  // Cutout percentage for the doughnut hole
            }
        });
</script>";
$additionJs = ob_get_clean();

include 'layout.php';
