<?php
$pageTitle = "Project Detail";

require_once 'controllers/ProjectController.php';
$projectController = new ProjectController();

$modify_type = getLastSegmentFromUrl();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['id'])) {
        $post_id = $_GET['id'];
        $postData = $projectController->viewProject($post_id);
        $tags = !empty($postData['tags']) ? explode(',', $postData['tags']) : [];
    }
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action_name'])) {
        if ($_POST['action_name'] === 'delete_record') {
            $projectController->deleteProject();
        }
    }
}

ob_start();
?>
<div class="row">
    <div class="col-xl-8 col-md-10 offset-xl-2 offset-md-1">
        <?php
        includeFileWithVariables('components/single-button-group.php', array("slug" => "project", "post_id" => $postData['id'], 'modify_type' => $modify_type));
        ?>
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0"><?= $postData['title'] ?></h4>
            </div>
            <div class="card-body">
                <div class="text-muted">
                    <?php if (!empty($postData['content'])) { ?>
                        <h6 class="mb-3 fw-semibold text-uppercase">Content</h6>
                        <div class="mb-3">
                            <?= $postData['content'] ?>
                        </div>
                        <div class="col-lg-12">
                            <div>
                                <label>Dev Url:</label>
                                <a href="' . ($projectData['dev_url'] ?? '#') . '" target="_blank">' . ($projectData['dev_url'] ?? '') . '</a>
                            </div>
                            <div>
                                <label>Staging Url:</label>
                                <a href="' . ($projectData['staging_url'] ?? '#') . '" target="_blank">' . ($projectData['staging_url'] ?? '') . '</a>
                            </div>
                            <div>
                                <label>Production Url:</label>
                                <a href="' . ($projectData['production_url'] ?? '#') . '" target="_blank">' . ($projectData['production_url'] ?? '') . '</a>
                            </div>
                            <div>
                                <label>Source Url:</label>
                                <a href="' . ($projectData['source_url'] ?? '#') . '" target="_blank">' . ($projectData['source_url'] ?? '') . '</a>
                            </div>
                        </div>
                    <?php } ?>

                    <?php if (!empty($postData['due_date']) && $postData['due_date'] != '0000-00-00') { ?>
                        <h6 class="mb-3 fw-semibold text-uppercase">Due Date </h6>
                        <div class="mb-3">
                            <?= $postData['due_date'] ?>
                        </div>
                    <?php } ?>

                    <div class="pt-3 border-top border-top-dashed mt-4">
                        <div class="row">
                            <div class="col-lg-3 col-sm-6">
                                <div>
                                    <p class="mb-2 text-uppercase fw-medium">Type :</p>
                                    <span class="fw-bold text-capitalize"><?= $postData['type'] ?? '' ?></span>
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-6">
                                <div>
                                    <p class="mb-2 text-uppercase fw-medium">Status :</p>
                                    <?= $postData['status'] ? renderStatusBadge($postData['status'], 'div', 12) : '' ?>
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-6">
                                <div>
                                    <p class="mb-2 text-uppercase fw-medium">Created At :</p>
                                    <h5 class="fs-15 mb-0">
                                        <?= $systemController->convertDateTime($postData['created_at']) ?>
                                    </h5>
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-6">
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
            <!-- end card body -->
        </div>
        <?php if ($tags && count($tags) > 0) : ?>
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-4">Tags</h5>
                    <div class="d-flex flex-wrap gap-2 fs-16">
                        <?php foreach ($tags as $key => $value) { ?>
                            <div class="badge fw-medium bg-secondary-subtle text-secondary"><?= $value ?>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php
$pageContent = ob_get_clean();
