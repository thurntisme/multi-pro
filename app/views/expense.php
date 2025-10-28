<?php
ob_start();
?>

<div class="d-flex justify-content-between align-items-center mb-4">
  <h1><?= $title ?></h1>
  <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addExpenseModal">
    <i class="ri-add-line"></i> Add New
  </button>
</div>

<!-- Summary Boxes -->
<div class="row g-4 mb-4">

  <!-- Income -->
  <div class="col-md-4">
    <div class="card border-0 shadow-sm summary-card h-100">
      <div class="card-body d-flex align-items-center">
        <div class="rounded-circle bg-success-subtle text-success d-flex align-items-center justify-content-center me-3" style="width: 60px; height: 60px;">
          <i class="ri-money-dollar-circle-line fs-3"></i>
        </div>
        <div>
          <h6 class="text-muted mb-1">Total Income</h6>
          <h3 class="fw-bold mb-0">$5,000</h3>
        </div>
      </div>
    </div>
  </div>

  <!-- Expense -->
  <div class="col-md-4">
    <div class="card border-0 shadow-sm summary-card h-100">
      <div class="card-body d-flex align-items-center">
        <div class="rounded-circle bg-danger-subtle text-danger d-flex align-items-center justify-content-center me-3" style="width: 60px; height: 60px;">
          <i class="ri-bank-card-2-line fs-3"></i>
        </div>
        <div>
          <h6 class="text-muted mb-1">Total Expense</h6>
          <h3 class="fw-bold mb-0">$3,200</h3>
        </div>
      </div>
    </div>
  </div>

  <!-- Balance -->
  <div class="col-md-4">
    <div class="card border-0 shadow-sm summary-card h-100">
      <div class="card-body d-flex align-items-center">
        <div class="rounded-circle bg-info-subtle text-info d-flex align-items-center justify-content-center me-3" style="width: 60px; height: 60px;">
          <i class="ri-wallet-3-line fs-3"></i>
        </div>
        <div>
          <h6 class="text-muted mb-1">Balance</h6>
          <h3 class="fw-bold mb-0">$1,800</h3>
        </div>
      </div>
    </div>
  </div>

</div>

<!-- Expense Table -->
<div class="card shadow-sm">
  <div class="card-body">
    <h5 class="card-title mb-3">Expense List</h5>
    <div class="table-responsive">
      <table class="table table-hover align-middle">
        <thead class="table-light">
          <tr>
            <th>#</th>
            <th>Date</th>
            <th>Category</th>
            <th>Description</th>
            <th>Amount</th>
            <th class="text-center">Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>1</td>
            <td>2025-10-24</td>
            <td>Food</td>
            <td>Lunch at restaurant</td>
            <td>$12</td>
            <td class="text-center d-flex align-items-center justify-content-center">
              <button class="btn btn-sm btn-warning me-1">
                <i class="bi bi-pencil"></i> Edit
              </button>
              <form method="POST" action="<?= $_SERVER['REQUEST_URI'] ?>">
                <input type="hidden" name="post_id" value="<?= $item['id'] ?>">
                <input type="hidden" name="action_name" value="delete_record">
                <button type="submit" class="btn btn-soft-danger btn-sm btn-delete-record">
                  <i class="ri-delete-bin-5-line align-bottom"></i>
                  <i class="bi bi-trash"></i> Delete
                </button>
              </form>
            </td>
          </tr>
          <tr>
            <td>2</td>
            <td>2025-10-23</td>
            <td>Transport</td>
            <td>Bus ticket</td>
            <td>$3</td>
            <td class="text-center">
              <button class="btn btn-sm btn-warning me-1">
                <i class="bi bi-pencil"></i> Edit
              </button>
              <button class="btn btn-sm btn-danger">
                <i class="bi bi-trash"></i> Delete
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- Add New Expense Modal -->
<div class="modal fade" id="addExpenseModal" tabindex="-1" aria-labelledby="addExpenseLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h5 class="modal-title" id="addExpenseLabel">Add New Expense</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <!-- Modal Body -->
      <div class="modal-body">
        <form id="addExpenseForm">
          <div class="mb-3">
            <label for="expenseDate" class="form-label">Date</label>
            <input type="date" class="form-control" id="expenseDate" required>
          </div>

          <div class="mb-3">
            <label for="expenseCategory" class="form-label">Category</label>
            <select class="form-select" id="expenseCategory" required>
              <option value="">Select category</option>
              <option>Food</option>
              <option>Transport</option>
              <option>Shopping</option>
              <option>Entertainment</option>
              <option>Other</option>
            </select>
          </div>

          <div class="mb-3">
            <label for="expenseDescription" class="form-label">Description</label>
            <input type="text" class="form-control" id="expenseDescription" placeholder="Enter details..." required>
          </div>

          <div class="mb-3">
            <label for="expenseAmount" class="form-label">Amount ($)</label>
            <input type="number" class="form-control" id="expenseAmount" min="0" step="0.01" required>
          </div>
        </form>
      </div>

      <!-- Modal Footer -->
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-primary" form="addExpenseForm">Save Expense</button>
      </div>
    </div>
  </div>
</div>

<!-- Weekly Expense Chart -->
<div class="row g-4 mb-4">
  <div class="col-lg-7">
    <div class="card shadow-sm h-100">
      <div class="card-body">
        <h5 class="card-title mb-3">Weekly Expense Comparison</h5>
        <div id="expenseLineChart" style="height: 350px;"></div>
      </div>
    </div>
  </div>
  <div class="col-lg-5">
    <div class="card shadow-sm h-100">
      <div class="card-body">
        <h5 class="card-title mb-3">Breakdown by Category</h5>
        <div id="expensePieChart" style="height: 350px;"></div>
      </div>
    </div>
  </div>
</div>


<?php
$pageContent = ob_get_clean();

ob_start(); ?>
<!-- ECharts -->
<script src="https://cdn.jsdelivr.net/npm/echarts@5.5.0/dist/echarts.min.js"></script>
<script>
  const days = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
  const thisWeek = [45, 32, 50, 40, 60, 75, 30];
  const lastWeek = [35, 40, 42, 38, 55, 65, 45];

  const cateBreakdownData = [{
      value: 150,
      name: 'Food'
    },
    {
      value: 80,
      name: 'Transport'
    },
    {
      value: 120,
      name: 'Shopping'
    },
    {
      value: 60,
      name: 'Entertainment'
    },
    {
      value: 40,
      name: 'Other'
    }
  ];
</script>
<script src="<?= \App\Helpers\Network::home_url('assets/js/pages/expense.js') ?>"></script>
<?php
$additionJs = ob_get_clean();

include_once LAYOUTS_DIR . 'dashboard.php';
