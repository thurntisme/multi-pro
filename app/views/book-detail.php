<?php
$pageTitle = "Book Detail";

require_once 'controllers/BookController.php';
$bookController = new BookController();

$modify_type = getLastSegmentFromUrl();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['id'])) {
        $post_id = $_GET['id'];
        $postData = $bookController->viewBook($post_id);
        $tags = !empty($postData['tags']) ? explode(',', $postData['tags']) : [];
    }
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action_name'])) {
        if ($_POST['action_name'] === 'delete_record') {
            $bookController->deleteBook();
        }
    }
}

ob_start();
?>

<div class="row">
    <div class="col-xl-8 col-md-10 offset-xl-2 offset-md-1">
        <?php
        ob_start();
        ?>
        <a class="btn btn-primary w-sm me-1"
            href="<?= App\Helpers\NetworkHelper::home_url('app/book/view?id=' . $postData['id']) ?>"><i
                class="ri-book-read-fill align-bottom  me-1"></i> Read</a>
        <?php
        $additionBtn = ob_get_clean();
        includeFileWithVariables('components/single-button-group.php', array("slug" => "book", "post_id" => $postData['id'], 'modify_type' => $modify_type, 'additionBtn' => $additionBtn));
        ?>
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0"><?= $postData['title'] ?></h4>
            </div>
            <div class="card-body">
                <div class="text-muted">
                    <h6 class="mb-3 fw-semibold text-uppercase">Content</h6>
                    <div class="mb-3">
                        <?= $postData['content'] ?>
                    </div>

                    <h6 class=" mb-3 fw-semibold text-uppercase">Url</h6>
                    <a href="<?= $postData['url'] ?? '#' ?>" target="_blank"
                        rel="noopener noreferrer"><?= $postData['url'] ?? '#' ?></a>

                    <div class="pt-3 border-top border-top-dashed mt-4">
                        <div class="row">
                            <div class="col-lg-3 col-sm-6">
                                <div>
                                    <p class="mb-2 text-uppercase fw-medium">Created At :</p>
                                    <h5 class="fs-15 mb-0">
                                        <?= $systemController->convertDate($postData['created_at']) ?>
                                    </h5>
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-6">
                                <div>
                                    <p class="mb-2 text-uppercase fw-medium">Updated At :</p>
                                    <h5 class="fs-15 mb-0">
                                        <?= $systemController->convertDate($postData['updated_at']) ?>
                                    </h5>
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-6">
                                <div>
                                    <p class="mb-2 text-uppercase fw-medium">Status :</p>
                                    <div class="badge bg-light fs-12 text-black">
                                        <?= $postData['status'] ? renderStatusBadge($postData['status']) : '' ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end card body -->
        </div>
        <?php if ($tags && count($tags) > 0): ?>
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
    <!-- end col -->
</div>

<?php
$pageContent = ob_get_clean();
