<?php
global $priorities, $status, $finance_categories;
require_once 'controllers/ExpenseController.php';

$pageTitle = "Expenses";

$total_expense = 6350000;

$expenseController = new ExpenseController();
$monthlyExpenses = $expenseController->monthlyExpenses();
$dailyExpenses = $expenseController->dailyExpenses();
$list = $expenseController->listExpenses();

$percentageChange = $monthlyExpenses['lastExpense'] > 0 ? (($monthlyExpenses['expense'] - $monthlyExpenses['lastExpense']) / $monthlyExpenses['lastExpense']) * 100 : 100;
$direction = $percentageChange > 0 ? 'up' : ($percentageChange < 0 ? 'down' : 'no change');

$balance = [
    'expense' => $total_expense - $monthlyExpenses['expense'],
    'lastExpense' => $total_expense - $monthlyExpenses['lastExpense'],
    'percentageChange' => abs($percentageChange),
    'direction' => $direction
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action_name'])) {
        if ($_POST['action_name'] === 'delete_record' && isset($_GET['id'])) {
            $expenseController->deleteExpense();
        }
        if ($_POST['action_name'] === 'new_record') {
            $expenseController->createExpense();
        }
        if ($_POST['action_name'] === 'delete_record') {
            $expenseController->deleteExpense();
        }
    }
}

function renderPercentage($item)
{
    $text_color = 'muted';
    $icon = ' d-none ';
    if ($item['direction'] !== 'no change') {
        $icon = $item['direction'];
        if ($item['direction'] !== 'up') {
            $text_color = 'success';
        } else {
            $text_color = 'danger';
        }
    }

    echo '<span class="badge bg-light text-' . $text_color . ' mb-0"><i class="ri-arrow-' . $icon . '-line align-middle"></i> ' . $item['percentageChange'] . ' %</span>';
}

ob_start();
?>

<?php
include_once DIR . '/components/alert.php';
?>

    <div class="row">
        <div class="col-md-12">
            <?php
            include_once DIR . '/components/alert.php';
            ?>
        </div>
        <div class="col-md-3">
            <div class="card">
                <form method="POST" action="<?= $_SERVER['REQUEST_URI'] ?>">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Create Finance</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-4">
                            <input type="hidden" name="action_name" value="new_record">
                            <div class="col-lg-12">
                                <div>
                                    <label for="title-Input" class="form-label">Title <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="title-Input" name="title"
                                           placeholder="Enter title" required/>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div>
                                    <label for="job-type-Input" class="form-label">Category<span
                                                class="text-danger">*</span></label>
                                    <select class="form-control" name="category" required data-choices
                                            data-choices-sorting-false>
                                        <?php foreach ($finance_categories as $category): ?>
                                            <option value="<?php echo htmlspecialchars($category['slug']); ?>" <?= $category['slug'] === 'other' ? 'selected' : '' ?>>
                                                <?php echo htmlspecialchars($category['icon'] . '  ' . $category['title']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div>
                                    <label for="date-expense-Input" class="form-label">Date of Expense <span
                                                class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="date-expense-Input"
                                           data-provider="flatpickr" data-date-format="Y-m-d" placeholder="Select date"
                                           name="date_expense" value="<?= date('Y-m-d') ?>"
                                           required/>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div>
                                    <label for="amount-Input" class="form-label">Amount <span
                                                class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="amount-Input" name="amount"
                                           placeholder="Enter start salary" required/>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div>
                                    <label for="description-field" class="form-label">Description</label>
                                    <textarea class="form-control" name="description" rows="3"
                                              placeholder="Enter description"></textarea>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div>
                                    <label for="tags-field" class="form-label">Tags</label>
                                    <input class="form-control" id="tags-field" name="tags" data-choices
                                           data-choices-removeItem multiple
                                           data-choices-text-unique-true type="text"/>
                                </div>
                            </div>

                            <div class="col-lg-12">
                                <div class="hstack justify-content-center gap-2">
                                    <button type="reset" class="btn btn-ghost-danger"><i
                                                class="ri-close-line align-bottom"></i> Reset
                                    </button>
                                    <button type="submit" class="btn btn-primary">Add Finance</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="col-md-9">
            <div class="row">
                <div class="col-xl-3 col-md-6">
                    <!-- card -->
                    <div class="card card-animate card-height-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1 overflow-hidden">
                                    <p class="text-uppercase fw-medium text-muted text-truncate mb-0"> Total
                                        Budget</p>
                                </div>
                                <div class="flex-shrink-0">
                                    <h5 class="text-success fs-14 mb-0">
                                        <i class="ri-arrow-right-up-line fs-13 align-middle"></i> +100 %
                                    </h5>
                                </div>
                            </div>
                            <div class="d-flex align-items-end justify-content-between mt-4">
                                <div>
                                    <h4 class="fs-22 fw-semibold ff-secondary mb-4"><?= convertAmount($total_expense); ?>
                                        vnd</h4>
                                    <a href="" class="text-decoration-underline" data-bs-toggle="modal"
                                       data-bs-target="#budgetModal">View Budget Detail</a>
                                </div>
                                <div class="avatar-sm flex-shrink-0">
                                                        <span class="avatar-title bg-success-subtle rounded fs-3">
                                                            <i class="bx bx-dollar-circle text-success"></i>
                                                        </span>
                                </div>
                            </div>
                        </div><!-- end card body -->
                    </div><!-- end card -->
                </div><!-- end col -->

                <div class="col-xl-3 col-md-6">
                    <div class="card card-animate card-height-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <p class="fw-medium text-muted mb-0">Monthly Expense</p>
                                    <h2 class="mt-3 ff-secondary fw-semibold fs-22"><?= convertAmount($monthlyExpenses['expense']); ?>
                                        vnd
                                    </h2><?php if (!empty($monthlyExpenses['lastExpense']) && $monthlyExpenses['lastExpense'] > 0) { ?>
                                        <span class="text-muted fs-12">/ <?= convertAmount($monthlyExpenses['lastExpense']); ?> vnd</span>
                                    <?php } ?>
                                    <p class="mb-0 mt-2 text-muted"><?= renderPercentage($monthlyExpenses) ?>
                                        vs. previous month</p>
                                </div>
                            </div>
                        </div><!-- end card body -->
                    </div>
                </div><!-- end col -->

                <div class="col-xl-3 col-md-6">
                    <div class="card card-animate card-height-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <p class="fw-medium text-muted mb-0">Daily Expense</p>
                                    <h2 class="mt-3 ff-secondary fw-semibold fs-22"><?= convertAmount($dailyExpenses['expense']); ?>
                                        vnd</h2>
                                    <?php if (!empty($dailyExpenses['lastExpense']) && $dailyExpenses['lastExpense'] > 0) { ?>
                                        <span class="text-muted fs-12">/ <?= convertAmount($dailyExpenses['lastExpense']); ?> vnd</span>
                                    <?php } ?>
                                    <p class="mb-0 mt-1 text-muted"><?= renderPercentage($dailyExpenses) ?>
                                        vs. yesterday</p>
                                </div>
                            </div>
                        </div><!-- end card body -->
                    </div>
                </div><!-- end col -->

                <div class="col-xl-3 col-md-6">
                    <div class="card card-animate card-height-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <p class="fw-medium text-muted mb-0">My Balance</p>
                                    <h2 class="mt-3 ff-secondary fw-semibold fs-22"><?= convertAmount($balance['expense']); ?>
                                        vnd</h2>
                                    <?php if (!empty($balance['lastExpense']) && $balance['lastExpense'] > 0) { ?>
                                        <span class="text-muted fs-12">/ <?= convertAmount($balance['lastExpense']); ?> vnd</span>
                                    <?php } ?>
                                    <p class="mb-0 mt-1 text-muted"><?= renderPercentage($balance) ?>
                                        vs. previous month</p>
                                </div>
                            </div>
                        </div><!-- end card body -->
                    </div>
                </div><!-- end col -->
            </div>
            <div class="card">
                <div class="card-body border border-dashed border-end-0 border-start-0 border-top-0">
                    <form method="get" action="<?= $_SERVER['REQUEST_URI'] ?>">
                        <div class="row g-3">
                            <div class="col-xxl-3 col-sm-12">
                                <div class="search-box">
                                    <input type="text" name="s" class="form-control search bg-light border-light"
                                           placeholder="Search for expenses or something..."
                                           value="<?= $_GET['s'] ?? '' ?>">
                                    <i class="ri-search-line search-icon"></i>
                                </div>
                            </div>
                            <!--end col-->
                            <div class="col-xxl-3 col-sm-4">
                                <input type="text" class="form-control bg-light border-light" name="date_expense"
                                       data-provider="flatpickr" data-date-format="Y-m-d" data-range-date="true"
                                       placeholder="Select date range" value="<?= $_GET['date_expense'] ?? '' ?>">
                            </div>
                            <div class="col-xxl-3 col-sm-4">
                                <div class="input-light">
                                    <select class="form-control" data-choices
                                            name="category">
                                        <option value="">Select Category</option>
                                        <?php
                                        foreach ($finance_categories as $category):
                                            $selected = (!empty($_GET['category']) ? $category['slug'] === $_GET['category'] : $category['slug'] === '') ? 'selected' : '';
                                            ?>
                                            <option value="<?php echo htmlspecialchars($category['slug']); ?>" <?= $selected ?>>
                                                <?php echo htmlspecialchars($category['icon'] . '  ' . $category['title']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <!--end col-->
                            <div class="col-xxl-3 col-sm-4 d-flex">
                                <button type="submit" class="btn btn-primary"><i
                                            class="ri-equalizer-fill me-1 align-bottom"></i>
                                    Filters
                                </button>
                                <a href="<?= home_url("finance") ?>" class="btn btn-danger ms-1"><i
                                            class="ri-delete-bin-2-fill me-1 align-bottom"></i>Reset</a>
                            </div>
                            <!--end col-->
                        </div>
                        <!--end row-->
                    </form>
                </div>
                <!--end card-body-->
                <div class="card-body">
                    <div class="table-responsive table-card mb-4">
                        <table class="table align-middle table-nowrap mb-0" id="expensesTable">
                            <thead class="table-light text-muted">
                            <tr>
                                <th>Title</th>
                                <th>Description</th>
                                <th class="text-center">Category</th>
                                <th class="text-center">Amount</th>
                                <th class="text-center">Date of Expense</th>
                                <th class="text-center">Tags</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody class="list form-check-all">
                            <?php if (count($list['list']) > 0) {
                                foreach ($list['list'] as $item) { ?>
                                    <tr>
                                        <td><?= $item['title'] ?></td>
                                        <td><?= $item['description'] ?></td>
                                        <td class="text-center"><?= $item['category'] ?></td>
                                        <td class="text-center"><?= convertAmount($item['amount']) ?></td>
                                        <td class="text-center"><?= $commonController->convertDate($item['date_expense']) ?></td>
                                        <td class="text-center"><?= $item['tags'] ?></td>
                                        <td class="text-center">
                                            <form method="POST" action="<?= $_SERVER['REQUEST_URI'] ?>">
                                                <input type="hidden" name="action_name" value="delete_record">
                                                <input type="hidden" name="expense_id" value="<?= $item['id'] ?>">
                                                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php }
                            } ?>
                            </tbody>
                        </table>
                        <!--end table-->
                        <div class="noresult" style="display: none">
                            <div class="text-center">
                                <lord-icon src="https://cdn.lordicon.com/msoeawqm.json" trigger="loop"
                                           colors="primary:#121331,secondary:#08a88a"
                                           style="width:75px;height:75px"></lord-icon>
                                <h5 class="mt-2">Sorry! No Result Found</h5>
                                <p class="text-muted mb-0">Weve searched more than 200k+ expenses We did not find any
                                    expenses for you search.</p>
                            </div>
                        </div>
                    </div>
                    <?php
                    includeFileWithVariables('components/pagination.php', array("count" => $list['count'], 'perPage' => 6));
                    ?>
                </div>
                <!--end card-body-->
            </div>
        </div>
    </div>

    <!-- Default Modals -->
    <div id="budgetModal" class="modal fade" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true"
         style="display: none;">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="myModalLabel">Budget Detail</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Striped Rows -->
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th scope="col">Id</th>
                            <th scope="col">Title</th>
                            <th scope="col" class="text-end">Amount</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($finance_categories as $index => $item) { ?>
                            <tr>
                                <td><?= $index + 1 ?></td>
                                <td><?= $item['icon'] . '&nbsp;&nbsp;' . $item['title'] ?></td>
                                <td class="text-end"><?= convertAmount($item['amount']) ?> vnd</td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer d-flex justify-content-center">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                </div>

            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

<?php
$pageContent = ob_get_clean();

include 'layout.php';