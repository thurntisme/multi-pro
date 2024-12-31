<?php
$pageTitle = "Course";

require_once 'controllers/CourseController.php';
$courseController = new CourseController();

$modify_type = getLastSegmentFromUrl();

if (!empty($modify_type)) {
    $pageTitle .= " " . $modify_type;
    if ($modify_type === "new") {
        $back_url = home_url("plans");
    } else if ($modify_type == 'edit') {
        if (isset($_GET['id'])) {
            $post_id = $_GET['id'];
            $postData = $courseController->viewCourse($post_id);
        }
        $back_url = home_url("plans/view") . '?post_id=' . $post_id;
    }
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['action_name'])) {
            if ($_POST['action_name'] === 'delete_record') {
                $courseController->deleteCourse();
            }
        } else {
            if ($modify_type === "new") {
                $courseController->createCourse();
            }
            if ($modify_type === "edit") {
                $courseController->updateCourse();
            }
        }
    };
}

ob_start();
?>
<div class="row">
    <div class="col-xl-8 col-md-10 offset-xl-2 offset-md-1">
        <?php
        includeFileWithVariables('components/single-button-group.php', array("slug" => "course", "post_id" => $postData['id'], 'modify_type' => $modify_type));
        ?>
        <form method="POST" action="<?= $_SERVER['REQUEST_URI'] ?>" id="course">
            <div class="card">
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label" for="course-title-input">Title</label>
                        <input type="text" class="form-control" id="course-title-input" name="title"
                            placeholder="Enter title" value="<?= $postData['title'] ?? '' ?>">
                        <?php if (!empty($post_id)) { ?>
                            <input type="hidden" name="course_id" value="<?= $post_id ?>">
                        <?php } ?>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Content</label>
                        <textarea name="content" class="ckeditor-classic">
                            <?= $postData['content'] ?? '' ?>
                        </textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="course-url-input">Url</label>
                        <input type="text" class="form-control" id="course-url-input" name="url" placeholder="Enter url"
                            value="<?= $postData['url'] ?? '' ?>">
                    </div>

                    <div class="mb-3">
                        <label for="choices-status-input" class="form-label">Status</label>
                        <select class="form-select" data-choices data-choices-search-false
                            id="choices-status-input" name="status">
                            <option value="not_started" selected>Not Started</option>
                            <option value="inprogress">Inprogress</option>
                            <option value="completed">Completed</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="choices-text-input" class="form-label">Tags</label>
                        <input class="form-control" id="choices-text-input" data-choices data-choices-removeItem
                            data-choices-limit="Required Limit" placeholder="Enter Skills" type="text" name="tags"
                            value="<?= $postData['tags'] ?? '' ?>" />
                    </div>
                </div>
                <!-- end card body -->
            </div>
            <div class="text-center mb-4">
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
<script src='" . home_url("/assets/libs/@ckeditor/ckeditor5-build-classic/build/ckeditor.js") . "'></script>
";
$additionJs = ob_get_clean();
