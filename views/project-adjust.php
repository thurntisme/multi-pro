<?php
$pageTitle = "Project";
$modify_type = getLastSegmentFromUrl();

require_once 'controllers/ProjectController.php';
$projectController = new ProjectController();

if (!empty($modify_type)) {
    $pageTitle .= " " . $modify_type;
    if ($modify_type === 'edit') {
        if (isset($_GET['id'])) {
            $post_id = $_GET['id'];
            $postData = $projectController->viewProject($post_id);
        }
    }
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['action_name'])) {
            if ($_POST['action_name'] === 'delete_record') {
                $projectController->deleteProject();
            }
        } else {
            if ($modify_type === "new") {
                $projectController->createProject();
            }
            if ($modify_type === "edit") {
                $projectController->updateProject();
            }
        }
    }
}

ob_start();

echo '<link href="' . home_url('assets/libs/dropzone/dropzone.css') . '" rel="stylesheet" type="text/css" />';
$additionCss = ob_get_clean();

ob_start();
?>
<div class="row">
    <div class="col-10 offset-md-1">
        <?php
        includeFileWithVariables('components/single-button-group.php', array("slug" => "project", "post_id" => $postData['id'] ?? '', 'modify_type' => $modify_type));
        ?>
        <form method="post" action="<?= $_SERVER['REQUEST_URI'] ?>">
            <?php if (!empty($post_id)) { ?>
                <input type="hidden" name="project_id" value="<?= $post_id ?>">
            <?php } ?>
            <div class="card mb-3">
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label" for="project-title-input">Project Title</label>
                        <input type="text" class="form-control" name="title" id="project-title-input" placeholder="Enter project title" value="<?= $postData['title'] ?? "" ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="project-thumbnail-img">Thumbnail Image</label>
                        <input class="form-control" id="project-thumbnail-img" type="file" accept="image/png, image/gif, image/jpeg">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Project Description</label>
                        <div id="ckeditor-classic" name="description">
                            <?= $postData['description'] ?? "" ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-3">
                            <div class="mb-3 mb-lg-0">
                                <label for="choices-type-input" class="form-label">Type</label>
                                <select class="form-select" data-choices data-choices-search-false
                                    data-choices-sorting-false
                                    id="choices-type-input" name="type">
                                    <?php
                                    $types = ['owner' => 'Owner', 'freelancer' => 'Freelancer'];
                                    foreach ($types as $value => $label) {
                                        $selected = (!empty($postData['type']) ? $value === $postData['type'] : $value === 'owner') ? 'selected' : '';
                                        echo "<option value=\"$value\" $selected>$label</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-3">
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
                        <div class="col-lg-3">
                            <div>
                                <label for="datepicker-start-date-input" class="form-label">Start Date</label>
                                <input type="text" name="start_date" class="form-control" id="datepicker-start-date-input"
                                    placeholder="Enter Start Date" data-provider="flatpickr" value="<?= !empty($postData['start_date']) && ($postData['start_date'] !== '0000-00-00') ? $postData['start_date'] : "" ?>">
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div>
                                <label for="datepicker-end-date-input" class="form-label">End Date</label>
                                <input type="text" name="end_date" class="form-control" id="datepicker-end-date-input"
                                    placeholder="Enter End Date" data-provider="flatpickr" value="<?= !empty($postData['end_date']) && ($postData['end_date'] !== '0000-00-00') ? $postData['end_date'] : "" ?>">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="card-title mb-0">Links</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Dev Url</label>
                        <input type="text" class="form-control" name="dev_url" placeholder="Enter development url" value="<?= $postData['dev_url'] ?? "" ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Staging Url</label>
                        <input type="text" class="form-control" name="staging_url" placeholder="Enter staging url" value="<?= $postData['staging_url'] ?? "" ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Production Url</label>
                        <input type="text" class="form-control" name="production_url" placeholder="Enter production url" value="<?= $postData['production_url'] ?? "" ?>">
                    </div>
                    <hr />
                    <div class="mb-3">
                        <label class="form-label">Source (Git) Url</label>
                        <input type="text" class="form-control" name="source_url" placeholder="Enter source url" value="<?= $postData['source_url'] ?? "" ?>">
                    </div>
                </div>
            </div>

            <div class="text-center mb-4">
                <a href="<?= home_url("app/projects/list") ?>" class="btn btn-light w-sm">Back</a>
                <button type="submit" class="btn btn-success w-sm"><?= $modify_type === "create" ? "Create" : "Save" ?></button>
            </div>
        </form>
    </div>
</div>

<?php

$pageContent = ob_get_clean();

ob_start();
echo '<!-- ckeditor -->
    <script src="' . home_url('assets/libs/@ckeditor/ckeditor5-build-classic/build/ckeditor.js') . ' "></script>

    <!-- dropzone js -->
    <script src="' . home_url('assets/libs/dropzone/dropzone-min.js') . ' "></script>
    <!-- project-create init -->
    <script src="' . home_url('assets/js/pages/project-create.init.js') . ' "></script>';

$additionJs = ob_get_clean();
