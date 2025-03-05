<?php
$pageTitle = "Report Working";

require_once 'controllers/ReportWorkingController.php';
$reportWorkingController = new ReportWorkingController();

$modify_type = getLastSegmentFromUrl();
$today = date('Y-m-d');

if (!empty($modify_type)) {
    $pageTitle .= " " . $modify_type;
    if ($modify_type == 'edit') {
        if (isset($_GET['id'])) {
            $post_id = $_GET['id'];
            $postData = $reportWorkingController->viewReport($post_id);
        }
    }
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['action_name'])) {
            if ($_POST['action_name'] === 'delete_record' && isset($_GET['id'])) {
                $reportWorkingController->deleteReport();
            }
        } else {
            if ($modify_type === "new") {
                $reportWorkingController->createReport();
            }
            if ($modify_type === "edit") {
                $reportWorkingController->updateReport();
            }
        }
    };
}

ob_start();
?>
<div class="row">
    <div class="col-xl-8 col-md-10 offset-xl-2 offset-md-1">
        <?php
        includeFileWithVariables('components/single-button-group.php', array("slug" => "report-working", "post_id" => $postData['id'] ?? '', 'modify_type' => $modify_type));
        ?>
        <form method="POST" action="<?= $_SERVER['REQUEST_URI'] ?>" id="report-working">
            <?php csrfInput() ?>

            <div class="card">
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label" for="report-working-title-input">Title</label>
                        <input type="text" class="form-control" id="report-working-title-input" name="title"
                            placeholder="Enter title" value="<?= $postData['title'] ?? '' ?>">
                        <?php if (!empty($post_id)) { ?>
                            <input type="hidden" name="report_id" value="<?= $post_id ?>">
                        <?php } ?>
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="report-working-project-input">Project</label>
                        <input type="text" class="form-control" id="report-working-project-input" name="project"
                            placeholder="Enter project name" value="<?= $postData['project'] ?? '' ?>">
                    </div>
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="mb-3">
                                <label for="datepicker-working_date-input" class="form-label">Working Date</label>
                                <input type="text" class="form-control" id="datepicker-working_date-input"
                                    placeholder="Enter working date" data-provider="flatpickr" name="working_date"
                                    value="<?= $postData['working_date'] ?? $today ?>">
                            </div>
                        </div>
                        <div class="col-lg-8">
                            <div class="mb-3">
                                <label class="form-label" for="report-working-note-input">Note</label>
                                <input type="text" class="form-control" id="report-working-note-input" name="note"
                                    placeholder="Enter note" value="<?= $postData['note'] ?? '' ?>">
                            </div>
                        </div>
                    </div>

                    <div class="mb-3 border-top border-top-dashed pt-3">
                        <label class="form-label">Content</label>
                        <table class="invoice-table table table-borderless table-nowrap mb-0">
                            <thead class="align-middle">
                                <tr class="table-active">
                                    <th scope="col" style="width: 40px;">#</th>
                                    <th scope="col">Task</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">ETA</th>
                                    <th scope="col">TimeSpend</th>
                                    <td></td>
                                </tr>
                            </thead>
                            <tbody id="newlink">

                                <?php
                                if (!empty($postData['content'])) {
                                    $content_arr = json_decode($postData['content'], true);
                                    if (count($content_arr) > 0) {
                                        foreach ($content_arr as $key => $task) {
                                ?>
                                            <tr id="<?= $key + 1 ?>" class="product">
                                                <th scope="row" class="product-id align-items-center"><?= $key + 1 ?></th>
                                                <td class="text-start">
                                                    <div class="mb-2">
                                                        <input type="text" class="form-control bg-light border-0"
                                                            placeholder="Task Title" name="task_title"
                                                            value="<?= $task['task_title'] ?>" required />
                                                        <div class="invalid-feedback">
                                                            Please enter a product title
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <select class="form-select mb-0" data-choices data-choices-search-false
                                                        data-choices-sorting-false
                                                        id="choices-status-input" name="task_status">
                                                        <?php
                                                        foreach ($status as $value => $label) {
                                                            $selected = (!empty($task['task_status']) ? $value === $task['task_status'] : $value === 'not_started') ? 'selected' : '';
                                                            echo "<option value=\"$value\" $selected>$label</option>";
                                                        }
                                                        ?>
                                                    </select>
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control bg-light border-0" placeholder="h:m"
                                                        name="task_eta" value="<?= $task['task_eta'] ?>" required />
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control bg-light border-0" placeholder="h:m"
                                                        name="task_time_spend" value="<?= $task['task_time_spend'] ?>" required />
                                                </td>
                                                <td class="product-removal text-center">
                                                    <a href="javascript:void(0)" class="btn btn-success">Delete</a>
                                                </td>
                                            </tr>
                                    <?php };
                                    }
                                    ?>
                                <?php
                                } else { ?>
                                    <tr id="1" class="product">
                                        <th scope="row" class="product-id align-items-center">1</th>
                                        <td class="text-start">
                                            <div class="mb-2">
                                                <input type="text" class="form-control bg-light border-0"
                                                    placeholder="Task Title" name="task_title" required />
                                                <div class="invalid-feedback">
                                                    Please enter a product title
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <select class="form-select" data-choices data-choices-search-false
                                                name="task_status">
                                                <option value="todo" selected>Todo</option>
                                                <option value="processing">Processing</option>
                                                <option value="done">Done</option>
                                            </select>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control bg-light border-0" placeholder="h:m"
                                                name="task_eta" required />
                                        </td>
                                        <td>
                                            <input type="text" class="form-control bg-light border-0" placeholder="h:m"
                                                name="task_time_spend" required />
                                        </td>
                                        <td class="product-removal text-center">
                                            <a href="javascript:void(0)" class="btn btn-success">Delete</a>
                                        </td>
                                    </tr>
                                <?php }
                                ?>
                            </tbody>
                            <tbody>
                                <tr id="newForm" style="display: none;">
                                    <td class="d-none" colspan="5">
                                        <p>Add New Form</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="5">
                                        <a href="javascript:new_link()" id="add-item"
                                            class="btn btn-soft-secondary fw-medium"><i
                                                class="ri-add-fill me-1 align-bottom"></i> Add Item</a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <input type="hidden" name="content" value='<?= $postData['content'] ?? "" ?>'>
                    </div>
                </div>
                <!-- end card body -->
            </div>
            <!-- end card -->

            <!-- end card -->
            <div class="text-center mb-4">
                <a href="<?= home_url('report-working') ?>" class="btn btn-light w-sm">Back</a>
                <button type="submit"
                    class="btn btn-success w-sm"><?= $modify_type === "new" ? "Create" : "Save" ?></button>
            </div>
        </form>
    </div>
</div>

<?php
include_once DIR . '/components/modal-delete.php';

$pageContent = ob_get_clean();

ob_start();
echo "
<script src='" . home_url("/assets/js/pages/report-working.js") . "'></script>
";
$additionJs = ob_get_clean();
