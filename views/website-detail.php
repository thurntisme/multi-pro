<?php
$pageTitle = "Website Detail";

require_once 'controllers/WebsiteController.php';
$websiteController = new WebsiteController();

$modify_type = getLastSegmentFromUrl();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['id'])) {
        $post_id = $_GET['id'];
        $postData = $websiteController->viewWebsite($post_id);
        $tags = !empty($postData['tags']) ? explode(',', $postData['tags']) : [];
    }
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action_name'])) {
        if ($_POST['action_name'] === 'delete_record') {
            $websiteController->deleteWebsite();
        }
    }
}

ob_start();
?>
<div class="row">
    <div class="col-xl-8 col-md-10 offset-xl-2 offset-md-1">
        <?php
        includeFileWithVariables('components/single-button-group.php', array("slug" => "website", "post_id" => $postData['id'], 'modify_type' => $modify_type));
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
                    <?php } ?>

                    <?php if (!empty($postData['url'])) { ?>
                        <h6 class="mb-3 fw-semibold text-uppercase">Url</h6>
                        <div class="mb-3">
                            <?= $postData['url'] ?> <a href="<?= $postData['url'] ?>" target="_blank" rel="noopener noreferrer" class="btn btn-primary btn-sm ms-2">Visit</a>
                        </div>
                    <?php } ?>

                    <?php if (!empty($postData['due_date']) && $postData['due_date'] != '0000-00-00') { ?>
                        <h6 class="mb-3 fw-semibold text-uppercase">Due Date </h6>
                        <div class="mb-3">
                            <?= $postData['due_date'] ?>
                        </div>
                    <?php } ?>

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
