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
    <div class="col-3">
    <div class="card">
        <div class="card-header d-flex align-items-center justify-content-between">
            <div class="card-title mb-0">
                Overview
            </div>
        </div>
        <div class="card-body">
            <div id="store-visits-source" class="apex-charts" dir="ltr"></div>
        </div>
    </div>
    ';

$categoryMap = [];
$cate_dropdown = '<select class="form-control" name="category_id" name="category" data-choices data-choices-search-false>';
$cate_dropdown .= '<option value=" ">All</option>';
$categoryMap[""] = "All";
foreach (DEFAULT_EXPENSE_CATEGORIES as $category) {
    $categoryMap[$category['id']] = $category['name'];
    $cate_dropdown .= '<option value="' . $category["id"] . '">' . $category["name"] . '</option>';
}
$cate_dropdown .= '</select>';


echo '<!-- Static Backdrop -->
    <!-- staticBackdrop Modal -->
    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body p-5">
                    <h4 class="mb-3">Add new expense</h4>
                    <form action="' . home_url("finance/expenses") . '" method="POST">
                        <input type="hidden" name="action" value="add_expense" />
                        <!-- Title Input -->
                        <div class="form-group mb-3">
                            <label for="title">Title</label>
                            <input type="text" class="form-control" name="title" placeholder="Enter title">
                        </div>
                        
                        <!-- Category Dropdown -->
                        <div class="form-group mb-3">
                            <label for="category">Category</label>
                            ' . $cate_dropdown . '
                        </div>

                        <!-- Amount Input -->
                        <div class="form-group mb-3">
                            <label for="amount">Amount</label>
                            <input type="number" class="form-control" name="amount" placeholder="Enter amount">
                        </div>

                        <!-- Description Input -->
                        <div class="form-group mb-3">
                            <label for="description">Description</label>
                            <textarea class="form-control" name="description" rows="3" placeholder="Enter description"></textarea>
                        </div>

                            <div class="hstack gap-2 justify-content-center mt-2">
                            <button type="button" class="btn btn-link link-success fw-medium" data-bs-dismiss="modal"><i class="ri-close-line me-1 align-middle"></i> Close</button>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
  </div>
  </div>
  <div class="col-9">';

if (isset($_SESSION['message'])) {
    $messageType = $_SESSION['message_type'] ?? 'info';
    echo '<div class="alert alert-' . htmlspecialchars($messageType) . ' alert-dismissible fade show mb-4" role="alert">'
        . htmlspecialchars($_SESSION['message']) .
        '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>';

    unset($_SESSION['message']);
    unset($_SESSION['message_type']);
}

echo '<div class="row">
        <div class="col-lg-12">
            <div class="card" id="expenseList">
                <div class="card-header border-0">
                    <div class="d-flex align-items-center">
                        <h5 class="card-title mb-0 flex-grow-1">Expenses</h5>
                        <div class="flex-shrink-0">
                            <div class="d-flex gap-2 flex-wrap">
                                <button class="btn btn-primary" id="remove-actions" onClick="deleteMultiple()"><i class="ri-delete-bin-2-line"></i></button>
                                <a href="#" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#staticBackdrop"><i class="ri-add-line align-bottom me-1"></i> Create Expense</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body bg-light-subtle border border-dashed border-start-0 border-end-0">
                    <form action="' . home_url("finance/expenses") . '">
                        <div class="row g-3">
                            <div class="col-xxl-4 col-sm-12">
                                <div class="search-box">
                                    <input type="text" class="form-control search bg-light border-light" placeholder="Search for customer, email, country, status or something...">
                                    <i class="ri-search-line search-icon"></i>
                                </div>
                            </div>
                            <!--end col-->
                            <div class="col-xxl-3 col-sm-4">
                                <input type="text" class="form-control bg-light border-light" id="datepicker-range" placeholder="Select date">
                            </div>
                            <div class="col-xxl-3 col-sm-4">
                                <div class="input-light">
                                    ' . $cate_dropdown . '
                                </div>
                            </div>

                            <div class="col-xxl-2 col-sm-4">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="ri-equalizer-fill me-1 align-bottom"></i> Filters
                                </button>
                            </div>
                            <!--end col-->
                        </div>
                        <!--end row-->
                    </form>
                </div>
                <div class="card-body">
                    <div>
                        <div class="table-responsive table-card">
                            <table class="table align-middle table-nowrap" id="expenseTable">
                                <thead class="text-muted">
                                    <tr>
                                        <th class="sort text-uppercase" data-sort="expense_id">Title</th>
                                        <th class="sort text-uppercase" data-sort="customer_name">Description</th>
                                        <th class="sort text-uppercase" data-sort="email">Category</th>
                                        <th class="sort text-uppercase" data-sort="date">Date</th>
                                        <th class="sort text-uppercase" data-sort="expense_amount">Amount</th>
                                        <th class="text-uppercase" data-sort="action">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="list form-check-all" id="expense-list-data">';
if (count($list) > 0) {
    foreach ($list as $item) {
        echo '<tr>
                <td class="id">' . $item['title'] . '</td>
                <td class="customer_name">' . $item['description'] . '</td>
                <td class="email">' . $categoryMap[$item['category_id']] . '</td>
                <td class="date">' . $systemController->convertDateTime($item['created_at']) . '</td>
                <td class="invoice_amount">' . convertAmount($item['amount']) . ' vnd</td>
                <td>
                    <form action="' . home_url("finance/expenses") . '" method="POST">
                        <input type="hidden" name="expense_id" value="' . $item['id'] . '" />
                        <input type="hidden" name="action" value="delete_expense" />
                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                    </form>
                </td>
            </tr>';
    }
}
echo '</tbody>
                            </table>
                            <div class="noresult" style="display: none">
                                <div class="text-center">
                                    <lord-icon src="https://cdn.lordicon.com/msoeawqm.json" trigger="loop" colors="primary:#121331,secondary:#08a88a" style="width:75px;height:75px"></lord-icon>
                                    <h5 class="mt-2">Sorry! No Result Found</h5>
                                    <p class="text-muted mb-0">We ve searched more than 150+ expenses We did not find any expenses for you search.</p>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-end mt-3">
                            <div class="pagination-wrap hstack gap-2">
                                <a class="page-item pagination-prev disabled" href="#">
                                    Previous
                                </a>
                                <ul class="pagination listjs-pagination mb-0"></ul>
                                <a class="page-item pagination-next" href="#">
                                    Next
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Modal -->
                    <div class="modal fade flip" id="deleteOrder" tabindex="-1" aria-labelledby="deleteOrderLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-body p-5 text-center">
                                    <lord-icon src="https://cdn.lordicon.com/gsqxdxog.json" trigger="loop" colors="primary:#405189,secondary:#f06548" style="width:90px;height:90px"></lord-icon>
                                    <div class="mt-4 text-center">
                                        <h4>You are about to delete a order ?</h4>
                                        <p class="text-muted fs-15 mb-4">Deleting your order will remove all of your information from our database.</p>
                                        <div class="hstack gap-2 justify-content-center remove">
                                            <button class="btn btn-link link-success fw-medium text-decoration-none" id="deleteRecord-close" data-bs-dismiss="modal"><i class="ri-close-line me-1 align-middle"></i> Close</button>
                                            <button class="btn btn-danger" id="delete-record">Yes, Delete It</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--end modal -->
                </div>
            </div>

        </div>
        <!--end col-->
    </div>';

$pageContent = ob_get_clean();

ob_start();
echo '<!-- Chart.js Library -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    ';
$additionCss = ob_get_clean();

ob_start();
echo "
<!-- apexcharts -->
<script src='" . home_url("/assets/libs/apexcharts/apexcharts.min.js") . "'></script>
<script src='" . home_url("/assets/js/pages/finance.js") . "'></script>
<script src='" . home_url("/assets/js/pages/modal.init.js") . "'></script>
<script src='" . home_url("/assets/libs/flatpickr/flatpickr.min.js") . "'></script>
";
$additionJs = ob_get_clean();
