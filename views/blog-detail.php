<?php
$url = extractPathFromCurrentUrl();
$parts = explode("/", $url);
$firstSlug = $parts[1] ?? '';
$pageTitle = ucfirst($firstSlug) . " Detail Page";

require_once 'controllers/BlogController.php';

$blogController = new BlogController();

$modify_type = getLastSegmentFromUrl();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['id'])) {
        $post_id = $_GET['id'];
        $postData = $blogController->viewBlog($post_id);
        $tags = !empty($postData['tags']) ? explode(',', $postData['tags']) : [];
    }
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action_name'])) {
        if ($_POST['action_name'] === 'delete_record') {
            $blogController->deleteBlog();
        }
    }
}
ob_start();
?>
    <div class="row">
        <div class="col-xl-8 col-md-10 offset-xl-2 offset-md-1">
            <?php
            includeFileWithVariables('components/single-button-group.php', array("slug" => $firstSlug, "post_id" => $postData['id'], 'modify_type' => $modify_type));
            ?>
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0"><?= $postData['title'] ?></h4>
                </div>
                <div class="card-body">
                    <div class="text-muted">
                        <h6 class="mb-3 fw-semibold text-uppercase">Content</h6>
                        <div>
                            <?= $postData['content'] ?>
                        </div>

                        <?php if ($postData['ref_url']) { ?>
                            <h6 class="mb-3 fw-semibold text-uppercase">Reference Url</h6>
                            <div>
                                <a href="<?= $postData['ref_url'] ?>" target="_blank"
                                   rel="noreferrer noopener"><?= $postData['ref_url'] ?></a>
                            </div>
                        <?php } ?>

                        <div class="pt-3 border-top border-top-dashed mt-4">
                            <div class="row">
                                <div class="col-lg-3 col-sm-6">
                                    <div>
                                        <p class="mb-2 text-uppercase fw-medium">Created At :</p>
                                        <h5 class="fs-15 mb-0">
                                            <?= $commonController->convertDate($postData['created_at']) ?>
                                        </h5>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-sm-6">
                                    <div>
                                        <p class="mb-2 text-uppercase fw-medium">Updated At :</p>
                                        <h5 class="fs-15 mb-0">
                                            <?= $commonController->convertDate($postData['updated_at']) ?>
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

include 'layout.php';
