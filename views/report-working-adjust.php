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
    }
    ;
}

ob_start();
?>
<form method="POST" action="<?= $_SERVER['REQUEST_URI'] ?>" id="report-working">
    <div class="row">
        <div class="col-lg-8">

            <?php
            include_once DIR . '/components/alert.php';
            ?>

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
                                                    <select class="form-select" data-choices data-choices-search-false
                                                        name="task_status">
                                                        <option value="todo" <?php echo ($task['task_status'] == 'todo') ? 'selected' : ''; ?>>Todo</option>
                                                        <option value="processing" <?php echo ($task['task_status'] == 'processing') ? 'selected' : ''; ?>>Processing</option>
                                                        <option value="done" <?php echo ($task['task_status'] == 'done') ? 'selected' : ''; ?>>Done</option>
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
                                        <?php }
                                        ;
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
        </div>
        <!-- end col -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Action</h5>
                </div>
                <div class="card-body">
                    <a href="<?= home_url('report-working') ?>" class="btn btn-light w-sm">Back</a>
                    <a href="<?= home_url('report-working/detail?id=' . $postData['id']) ?>"
                        class="btn btn-info w-sm mx-2">View</a>
                    <?php if (!empty($post_id)) { ?>
                        <button type="button" class="btn btn-danger w-sm" data-bs-toggle="modal"
                            data-bs-target="#deleteRecordModal">Delete</button>
                    <?php } ?>
                </div>
                <!-- end card body -->
            </div>
            <!-- end card -->

            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Privacy</h5>
                </div>
                <div class="card-body">
                    <div>
                        <label for="choices-privacy-status-input" class="form-label">Status</label>
                        <select class="form-select" data-choices data-choices-search-false
                            id="choices-privacy-status-input">
                            <option value="Private" selected>Private</option>
                            <option value="Team">Team</option>
                            <option value="Public">Public</option>
                        </select>
                    </div>
                </div>
                <!-- end card body -->
            </div>
            <!-- end card -->

            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Tags</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="choices-categories-input" class="form-label">Categories</label>
                        <select class="form-select" data-choices data-choices-search-false
                            id="choices-categories-input">
                            <option value="Designing" selected>Designing</option>
                            <option value="Development">Development</option>
                        </select>
                    </div>

                    <div>
                        <label for="choices-text-input" class="form-label">Tags</label>
                        <input class="form-control" id="choices-text-input" data-choices
                            data-choices-limit="Required Limit" placeholder="Enter Skills" type="text" name="tags"
                            value="<?= $postData['tags'] ?? '' ?>" />
                    </div>
                </div>
                <!-- end card body -->
            </div>
            <!-- end card -->

            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Members</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="choices-lead-input" class="form-label">Team Lead</label>
                        <select class="form-select" data-choices data-choices-search-false id="choices-lead-input">
                            <option value="Brent Gonzalez" selected>Brent Gonzalez</option>
                            <option value="Darline Williams">Darline Williams</option>
                            <option value="Sylvia Wright">Sylvia Wright</option>
                            <option value="Ellen Smith">Ellen Smith</option>
                            <option value="Jeffrey Salazar">Jeffrey Salazar</option>
                            <option value="Mark Williams">Mark Williams</option>
                        </select>
                    </div>

                    <div>
                        <label class="form-label">Team Members</label>
                        <div class="avatar-group">
                            <a href="javascript: void(0);" class="avatar-group-item" data-bs-toggle="tooltip"
                                data-bs-trigger="hover" data-bs-placement="top" title="Brent Gonzalez">
                                <div class="avatar-xs">
                                    <img src="assets/images/users/avatar-3.jpg" alt="" class="rounded-circle img-fluid">
                                </div>
                            </a>
                            <a href="javascript: void(0);" class="avatar-group-item" data-bs-toggle="tooltip"
                                data-bs-trigger="hover" data-bs-placement="top" title="Sylvia Wright">
                                <div class="avatar-xs">
                                    <div class="avatar-title rounded-circle bg-secondary">
                                        S
                                    </div>
                                </div>
                            </a>
                            <a href="javascript: void(0);" class="avatar-group-item" data-bs-toggle="tooltip"
                                data-bs-trigger="hover" data-bs-placement="top" title="Ellen Smith">
                                <div class="avatar-xs">
                                    <img src="assets/images/users/avatar-4.jpg" alt="" class="rounded-circle img-fluid">
                                </div>
                            </a>
                            <a href="javascript: void(0);" class="avatar-group-item" data-bs-toggle="tooltip"
                                data-bs-trigger="hover" data-bs-placement="top" title="Add Members">
                                <div class="avatar-xs" data-bs-toggle="modal" data-bs-target="#inviteMembersModal">
                                    <div
                                        class="avatar-title fs-16 rounded-circle bg-light border-dashed border text-primary">
                                        +
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
                <!-- end card body -->
            </div>
            <!-- end card -->
        </div>
        <!-- end col -->
    </div>
</form>

<?php
include_once DIR . '/components/modal-delete.php';

$pageContent = ob_get_clean();

ob_start();
echo "
<script src='" . home_url("/assets/js/pages/report-working.js") . "'></script>
";
$additionJs = ob_get_clean();

include 'layout.php';