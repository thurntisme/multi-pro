<?php
$pageTitle = "Finance";

require_once 'controllers/BudgetController.php';
require_once 'controllers/ExpenseController.php';
require_once 'controllers/IncomeController.php';
$budgetController = new BudgetController();
$expenseController = new ExpenseController();
$incomeController = new IncomeController();

// Get the current month and year
$currentMonth = date('n');  // 'n' gives the numeric month without leading zero (1-12)
$currentYear = date('Y');   // 'Y' gives the full year (e.g., 2024)

// Sample data for budget, income, and expenses
$budget = $budgetController->getTotalBudget(); // Total budget for the month
$totalDaysInMonth = cal_days_in_month(CAL_GREGORIAN, $currentMonth, $currentYear);;
$budgetPerDay = $budget / $totalDaysInMonth;

$expensesLast7Days = $expenseController->listExpensesLast7Days();
$incomesLast7Days = $incomeController->listIncomesLast7Days();

$income = [
  ["source" => "Salary", "amount" => 1500],
  ["source" => "Freelance", "amount" => 300]
];
$expenses = [
  ["title" => "Groceries", "amount" => 150],
  ["title" => "Electricity Bill", "amount" => 75],
  ["title" => "Car Maintenance", "amount" => 300],
  ["title" => "Dining Out", "amount" => 50]
];

// Calculate total income and total expenses
$totalIncome = array_sum(array_column($income, 'amount'));
$totalExpenses = array_sum(array_column($expenses, 'amount'));

// Calculate remaining budget
$remainingBudget = $budget + $totalIncome - $totalExpenses;

// Prepare data for the chart
$labels = ['Average Budget', 'Income', 'Expenses'];
$data = [$budget, $totalIncome, $totalExpenses];

// Calculate total income and total expenses for the week
$totalWeeklyIncome = array_sum(array_column($incomesLast7Days, 'amount'));
$totalWeeklyExpenses = array_sum(array_column($expensesLast7Days, 'amount'));

// Prepare data for the chart
$labels = [];
$incomeData = [];
$expensesData = [];

// Loop through the week (assuming week starts on Sunday)
for ($i = 6; $i >= 0; $i--) {
  $date = date('Y-m-d', strtotime("-$i days"));
  $labels[] = date('D', strtotime($date)); // Add day of the week as label

  // Find income for the day
  $incomeForDay = array_sum(array_column(array_filter($incomesLast7Days, fn($entry) => $entry && date('Y-m-d', strtotime($entry['created_at'])) == $date), 'amount'));

  $incomeData[] = $incomeForDay;

  // Find expenses for the day
  $expensesForDay = array_sum(array_column(array_filter($expensesLast7Days, fn($entry) => $entry && date('Y-m-d', strtotime($entry['created_at'])) == $date), 'amount'));
  $expensesData[] = $expensesForDay;
}

// Reverse the arrays to show the week starting from the first day
$labels = array_reverse($labels);
$incomeData = array_reverse($incomeData);
$expensesData = array_reverse($expensesData);

ob_start();

include_once DIR . "/components/finance-top.php";

echo '<div class="mt-5">
    <div class="row mt-4">
        <div class="col-md-3">
          <div class="card text-white bg-info mb-3 border-info">
              <div class="card-header"><span class="text-dark">Budget:</span> <span class="text-dark">' . number_format($budget) . ' vnd</span></div>
          </div>
          <div class="card text-white bg-success mb-3 border-success">
              <div class="card-header"><span class="text-dark">Income:</span> <span class="text-dark">' . number_format($totalIncome) . ' vnd</span></div>
          </div>
          <div class="card text-white bg-danger mb-3 border-danger">
              <div class="card-header"><span class="text-dark">Expense:</span> <span class="text-dark">' . number_format($totalExpenses) . ' vnd</span></div>
          </div>
          <div class="card text-white bg-dark mb-3">
              <div class="card-header font-weight-bold"><span class="text-dark">Budget:</span> <span class="text-dark">' . number_format($remainingBudget) . ' vnd</span></div>
          </div>
        </div>
        <div class="col-md-9">
          <div class="card card-height-100">
              <div class="card-header align-items-center d-flex">
                  <h4 class="card-title mb-0 flex-grow-1">Balance Overview</h4>
                  <div class="flex-shrink-0">
                      <div class="dropdown card-header-dropdown">
                          <a class="text-reset dropdown-btn" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                              <span class="fw-semibold text-uppercase fs-12">Sort by: </span><span class="text-muted">Current Year<i class="mdi mdi-chevron-down ms-1"></i></span>
                          </a>
                          <div class="dropdown-menu dropdown-menu-end">
                              <a class="dropdown-item" href="#">Today</a>
                              <a class="dropdown-item" href="#">Last Week</a>
                              <a class="dropdown-item" href="#">Last Month</a>
                              <a class="dropdown-item" href="#">Current Year</a>
                          </div>
                      </div>
                  </div>
              </div><!-- end card header -->
              <div class="card-body px-0">
                  <ul class="list-inline main-chart text-center mb-0">
                      <li class="list-inline-item chart-border-left me-0 border-0">
                          <h4 class="text-primary">$584k <span class="text-muted d-inline-block fs-13 align-middle ms-2">Revenue</span></h4>
                      </li>
                      <li class="list-inline-item chart-border-left me-0">
                          <h4>$497k<span class="text-muted d-inline-block fs-13 align-middle ms-2">Expenses</span>
                          </h4>
                      </li>
                      <li class="list-inline-item chart-border-left me-0">
                          <h4><span data-plugin="counterup">3.6</span>%<span class="text-muted d-inline-block fs-13 align-middle ms-2">Profit Ratio</span></h4>
                      </li>
                  </ul>

                  <div id="revenue-expenses-charts" class="apex-charts" dir="ltr"></div>
              </div>
          </div><!-- end card -->
        </div>
    </div>
</div>';

$pageContent = ob_get_clean();

ob_start();
echo '<!-- Chart.js Library -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>';
$additionCss = ob_get_clean();

ob_start();
echo '
    <!-- apexcharts -->
    <script src="' . home_url("/assets/libs/apexcharts/apexcharts.min.js") . '"></script>

<script type="text/javascript">
      // Data for the chart
    const labels = ' . json_encode($labels) . ';
    const incomeData = ' . json_encode($incomeData) . ';
    const expensesData = ' . json_encode($expensesData) . ';
    const budget = ' . $budget . ';

    const data = {
        labels: labels,
        datasets: [
            {
                label: "Average Budget",
                data: [' . $budgetPerDay . ', ' . $budgetPerDay . ',' . $budgetPerDay . ',' . $budgetPerDay . ',' . $budgetPerDay . ',' . $budgetPerDay . ',' . $budgetPerDay . ',], // Show budget only on first label
                borderColor: "#36b9cc",
                fill: false,
            },
            {
                label: "Income",
                data: ' . json_encode($incomeData) . ', // Show income only on second label
                borderColor: "#1cc88a",
                fill: false,
            },
            {
                label: "Expenses",
                data: ' . json_encode($expensesData) . ', // Show expenses only on third label
                borderColor: "#e74a3b",
                fill: false,
            }
        ]
    };
  </script>

    <!-- Dashboard init -->
  <script src="' . home_url("/assets/js/pages/finance.js") . '" type="text/javascript"></script>
  ';
$additionJs = ob_get_clean();

include 'layout.php';
