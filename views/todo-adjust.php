<?php
global $priorities, $status;
$pageTitle = "Todo";

require_once 'controllers/TodoController.php';
$todoController = new TodoController();

$modify_type = getLastSegmentFromUrl();

if (!empty($modify_type)) {
    $pageTitle .= " " . $modify_type;
    if ($modify_type === "new") {
        $back_url = home_url("plans");
    } else if ($modify_type == 'edit') {
        if (isset($_GET['id'])) {
            $post_id = $_GET['id'];
            $postData = $todoController->viewTodo($post_id);
        }
        $back_url = home_url("plans/view") . '?post_id=' . $post_id;
    }
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['action_name'])) {
            if ($_POST['action_name'] === 'delete_record' && isset($_GET['id'])) {
                $todoController->deleteTodo();
            }
        } else {
            if ($modify_type === "new") {
                $todoController->createTodo();
            }
            if ($modify_type === "edit") {
                $todoController->updateTodo();
            }
        }
    }
}

ob_start();
?>
    <form method="POST" action="<?= $_SERVER['REQUEST_URI'] ?>" id="todo">
        <div class="mb-3">
            <a href="<?= home_url('app/todo') ?>" class="btn btn-soft-primary btn-label waves-effect waves-light"><i
                        class="ri-arrow-left-line label-icon align-middle fs-16 me-2"></i>Back to List</a>
        </div>
        <div class="row">
            <div class="col-lg-8">

                <?php
                include_once DIR . '/components/alert.php';
                ?>

                <div class="card">
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label" for="todo-title-input">Title</label>
                            <input type="text" class="form-control" id="todo-title-input" name="title"
                                   placeholder="Enter title" value="<?= $postData['title'] ?? '' ?>">
                            <?php if (!empty($post_id)) { ?>
                                <input type="hidden" name="todo_id" value="<?= $post_id ?>">
                            <?php } ?>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Content</label>
                            <textarea name="content" class="ckeditor-classic">
                            <?= $postData['content'] ?? '' ?>
                        </textarea>
                        </div>

                        <div class="row">
                            <div class="col-lg-4">
                                <div class="mb-3 mb-lg-0">
                                    <label for="choices-priority-input" class="form-label">Priority</label>
                                    <select class="form-select" data-choices data-choices-search-false
                                            data-choices-sorting-false
                                            id="choices-priority-input" name="priority">
                                        <?php
                                        foreach ($priorities as $value => $label) {
                                            $selected = (!empty($postData['priority']) ? $value === $postData['priority'] : $value === 'medium') ? 'selected' : '';
                                            echo "<option value=\"$value\" $selected>$label</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="mb-3 mb-lg-0">
                                    <label for="choices-status-input" class="form-label">Status</label>
                                    <select class="form-select" data-choices data-choices-search-false
                                            data-choices-sorting-false
                                            id="choices-status-input" name="status">
                                        <?php
                                        foreach ($status as $value => $label) {
                                            $selected = (!empty($postData['status']) ? $value === $postData['status'] : $value === 'not_started') ? 'selected' : '';
                                            echo "<option value=\"$value\" $selected>$label</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div>
                                    <label for="datepicker-deadline-input" class="form-label">Deadline</label>
                                    <input type="text" class="form-control" id="datepicker-deadline-input"
                                           placeholder="Enter due date" data-provider="flatpickr" name="due_date"
                                        <?= !empty($postData['due_date']) && $postData['due_date'] != '0000-00-00' ? 'value="' . $postData["due_date"] . '"' : '' ?>
                                           data-date-format="Y-m-d">
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- end card body -->
                </div>
                <!-- end card -->

                <!-- end card -->
                <div class=" text-center mb-4">
                    <a href="<?= home_url('app/todo') ?>" class="btn btn-light w-sm">Back</a>
                    <button type="submit"
                            class="btn btn-success w-sm"><?= $modify_type === "new" ? "Create" : "Save" ?></button>
                </div>
            </div>
            <!-- end col -->
            <div class="col-lg-4">
                <?php if (!empty($post_id)) { ?>
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Action</h5>
                        </div>
                        <div class="card-body">
                            <a href="<?= home_url('app/todo/detail?id=' . $post_id) ?>"
                               class="btn btn-info w-sm mx-2">View</a>
                            <button type="button" class="btn btn-danger w-sm" data-bs-toggle="modal"
                                    data-bs-target="#deleteRecordModal">Delete
                            </button>
                        </div>
                        <!-- end card body -->
                    </div>
                    <!-- end card -->
                <?php } ?>

                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Privacy</h5>
                    </div>
                    <div class="card-body">
                        <div>
                            <label for="choices-privacy-status-input" class="form-label">Status</label>
                            <select class="form-select" data-choices data-choices-search-false
                                    data-choices-sorting-false
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
                                    data-choices-sorting-false
                                    id="choices-categories-input">
                                <option value="Designing" selected>Designing</option>
                                <option value="Development">Development</option>
                            </select>
                        </div>

                        <div>
                            <label for="choices-text-input" class="form-label">Tags</label>
                            <input class="form-control" id="choices-text-input" data-choices data-choices-removeItem
                                   multiple data-choices-limit="Required Limit" placeholder="Enter Skills"
                                   type="text"
                                   name="tags"
                                   value="<?= $postData['tags'] ?? '' ?>"/>
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
<script src='" . home_url("/assets/libs/@ckeditor/ckeditor5-build-classic/build/ckeditor.js") . "'></script>
";
$additionJs = ob_get_clean();

include 'layout.php';
