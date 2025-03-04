<?php
$pageTitle = "Task Detail";

require_once 'controllers/ProjectController.php';
$projectController = new ProjectController();

$modify_type = getLastSegmentFromUrl();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['id'])) {
        $post_id = $_GET['id'];
        $postData = $projectController->viewTask($post_id);
    }
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action_name'])) {
        if ($_POST['action_name'] === 'delete_record') {
            $projectController->deleteTask();
        }
        if ($_POST['action_name'] === 'change_status_record') {
            $projectController->changeStatusTask();
        }
    }
}

ob_start();
?>
<div class="row">
    <div class="col-xl-8 col-md-10 offset-xl-2 offset-md-1">
        <?php
        include_once DIR . '/components/alert.php';
        ?>
        <div class="mb-3 d-flex gap-1 justify-content-between align-items-center">
            <a href="<?= home_url('app/project/detail?id=' . $postData['project_id']) ?>" class="btn btn-soft-primary btn-label waves-effect waves-light me-auto"><i
                    class="ri-arrow-left-line label-icon align-middle fs-16 me-2"></i>Back to project</a>
            <form method="POST" action="<?= $_SERVER['REQUEST_URI'] ?>">
                <input type="hidden" name="task_id" value="<?= $postData['id'] ?>">
                <input type="hidden" name="action_name" value="change_status_record">
                <?php if ($postData['status'] === 'pending') { ?>
                    <button type="submit" class="btn btn-success">
                        <i
                            class="ri-check-line align-bottom"></i> Mark Complete
                    </button>
                <?php } else { ?>
                    <button type="submit" class="btn btn-warning">
                        <i
                            class="ri-close-line align-bottom"></i> Remove Complete
                    </button>
                <?php } ?>
            </form>
            <form method="POST" action="<?= $_SERVER['REQUEST_URI'] ?>">
                <input type="hidden" name="task_id" value="<?= $postData['id'] ?>">
                <input type="hidden" name="project_id" value="<?= $postData['project_id'] ?>">
                <input type="hidden" name="action_name" value="delete_record">
                <button type="submit" class="btn btn-danger btn-delete-record">
                    <i
                        class="ri-delete-bin-5-line align-bottom"></i>
                </button>
            </form>
        </div>
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0"><?= $postData['title'] ?></h4>
            </div>
            <div class="card-body">
                <div class="text-muted">
                    <h6 class="mb-3 fw-semibold text-uppercase">Description:</h6>
                    <?php if (!empty($postData['description'])) { ?>
                        <div class="mb-3">
                            <?= $postData['description'] ?>
                        </div>
                    <?php } ?>

                    <div class="pt-3 border-top border-top-dashed mt-4">
                        <div class="row">
                            <div class="col-lg-6">
                                <div>
                                    <p class="mb-2 text-uppercase fw-medium">Created At :</p>
                                    <h5 class="fs-15 mb-0">
                                        <?= $systemController->convertDateTime($postData['created_at']) ?>
                                    </h5>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div>
                                    <p class="mb-2 text-uppercase fw-medium">Updated At :</p>
                                    <h5 class="fs-15 mb-0">
                                        <?= $systemController->convertDateTime($postData['updated_at']) ?>
                                    </h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$pageContent = ob_get_clean();

ob_start();
echo "
<script src='" . home_url("/assets/libs/@ckeditor/ckeditor5-build-classic/build/ckeditor.js") . "'></script>
";
$additionJs = ob_get_clean();
