<?php
global $priorities, $status, $finance_categories;
require_once 'controllers/ExpenseController.php';

$pageTitle = "Expenses";

$totalExpenses = 6350000;

$expenseController = new ExpenseController();
$monthlyExpenses = $expenseController->monthlyExpenses();
$total_expense = $monthlyExpenses['total_expense'] ?? 0;
$list = $expenseController->listExpenses();

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
                                            data-choices-sorting-false data-choices-search-false>
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
                                           data-choices-text-unique-true type="text" required/>
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
            <div class="card" id="tasksList">
                <div class="card-header border-0">
                    <div class="d-flex align-items-center justify-content-between">
                        <h5 class="card-title mb-0 text-muted">Max Budget: <?= convertAmount($totalExpenses); ?>
                            vnd</h5>
                        <h5 class="card-title mb-0 text-muted">Monthly
                            Expenses (<?= date('Y/m') . '): ' . convertAmount($total_expense); ?>
                            vnd</h5>
                        <h5 class="card-title mb-0">Monthly
                            Remaining: <?= convertAmount($totalExpenses - $total_expense); ?>
                            vnd</h5>
                    </div>
                </div>
                <div class="card-body border border-dashed border-end-0 border-start-0">
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
                                    <select class="form-control" data-choices data-choices-search-false
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
                                <a href="<?= home_url("expense") ?>" class="btn btn-danger ms-1"><i
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
                    includeFileWithVariables('components/pagination.php', array("count" => $list['count']));
                    ?>
                </div>
                <!--end card-body-->
            </div>
        </div>
    </div>


<?php
$pageContent = ob_get_clean();

include 'layout.php';