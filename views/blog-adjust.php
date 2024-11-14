<?php
$pageTitle = "Blog";

require_once 'controllers/BlogController.php';
$blogController = new BlogController();

$modify_type = getLastSegmentFromUrl();

if (!empty($modify_type)) {
    $pageTitle .= " " . $modify_type;
    if ($modify_type === "new") {
        $back_url = home_url("plans");
    } else if ($modify_type == 'edit') {
        if (isset($_GET['id'])) {
            $post_id = $_GET['id'];
            $postData = $blogController->viewBlog($post_id);
        }
        $back_url = home_url("plans/view") . '?post_id=' . $post_id;
    }
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['action_name'])) {
            if ($_POST['action_name'] === 'delete_record' && isset($_GET['id'])) {
                $blogController->deleteBlog();
            }
        } else {
            if ($modify_type === "new") {
                $blogController->createBlog();
            }
            if ($modify_type === "edit") {
                $blogController->updateBlog();
            }
        }
    };
}

ob_start();
echo '<link rel="stylesheet" href="https://cdn.ckeditor.com/ckeditor5/43.2.0/ckeditor5.css" />';
$additionCss = ob_get_clean();

ob_start();
?>
<form method="POST" action="<?= $_SERVER['REQUEST_URI'] ?>" id="blog">
    <div class="row">
        <div class="col-lg-8">

            <?php
            include_once DIR . '/components/alert.php';
            ?>

            <div class="card">
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label" for="project-title-input">Title</label>
                        <input type="text" class="form-control" id="project-title-input" name="title"
                            placeholder="Enter title" value="<?= $postData['title'] ?? '' ?>">
                        <?php if (!empty($post_id)) { ?>
                            <input type="hidden" name="blog_id" value="<?= $post_id ?>">
                        <?php } ?>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Content</label>
                        <textarea name="content" class="ckeditor-advanced">
                            <?= $postData['content'] ?? '' ?>
                        </textarea>
                    </div>

                    <div class="row">
                        <div class="col-lg-4">
                            <div class="mb-3 mb-lg-0">
                                <label for="choices-priority-input" class="form-label">Priority</label>
                                <select class="form-select" data-choices data-choices-search-false
                                    id="choices-priority-input">
                                    <option value="High" selected>High</option>
                                    <option value="Medium">Medium</option>
                                    <option value="Low">Low</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="mb-3 mb-lg-0">
                                <label for="choices-status-input" class="form-label">Status</label>
                                <select class="form-select" data-choices data-choices-search-false
                                    id="choices-status-input">
                                    <option value="Inprogress" selected>Inprogress</option>
                                    <option value="Completed">Completed</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div>
                                <label for="datepicker-deadline-input" class="form-label">Deadline</label>
                                <input type="text" class="form-control" id="datepicker-deadline-input"
                                    placeholder="Enter due date" data-provider="flatpickr">
                            </div>
                        </div>
                    </div>
                </div>
                <!-- end card body -->
            </div>
            <!-- end card -->

            <!-- end card -->
            <div class="text-center mb-4">
                <a href="<?= home_url('blog') ?>" class="btn btn-light w-sm">Back</a>
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
                    <a href="<?= home_url('blog') ?>" class="btn btn-light w-sm">Back</a>
                    <a href="<?= home_url('blog/detail?id=' . $postData['id']) ?>"
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
                    <?php
                    includeFileWithVariables('components/form-control.php', array("type" => "select", "id" => "category", "name" => "category", "label" => "Category", "options" => DEFAULT_BLOG_CATEGORIES, "value" => $postData['category'] ?? ''));
                    ?>

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
<script src='" . home_url("/assets/libs/@ckeditor/ckeditor5.umd.js") . "'></script>
";
$additionJs = ob_get_clean();

include 'layout.php';
