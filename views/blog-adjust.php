<?php
$url = extractPathFromCurrentUrl();
$parts = explode("/", $url);
$firstSlug = $parts[1] ?? '';
$pageTitle = ucfirst('/app/' . $firstSlug) . " Edit Page";

require_once 'controllers/BlogController.php';
$blogController = new BlogController();

$modify_type = getLastSegmentFromUrl();

if (!empty($modify_type)) {
    if ($modify_type == 'edit') {
        if (isset($_GET['id'])) {
            $post_id = $_GET['id'];
            $postData = $blogController->viewBlog($post_id);
        }
    }
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['action_name'])) {
            if ($_POST['action_name'] === 'delete_record' && isset($_GET['id'])) {
                $blogController->deleteBlog('/app/' . $firstSlug);
            }
        } else {
            if ($modify_type === "new") {
                $blogController->createBlog('/app/' . $firstSlug);
            }
            if ($modify_type === "edit") {
                $blogController->updateBlog('/app/' . $firstSlug);
            }
        }
    }
}

ob_start();
echo '<link rel="stylesheet" href="https://cdn.ckeditor.com/ckeditor5/43.2.0/ckeditor5.css" />';
$additionCss = ob_get_clean();

ob_start();
?>
    <div class="row">
        <div class="col-xl-8 col-md-10 offset-xl-2 offset-md-1">
            <?php
            includeFileWithVariables('components/single-button-group.php', array("slug" => $firstSlug, "post_id" => $postData['id'], 'modify_type' => $modify_type));
            ?>

            <form method="POST" action="<?= $_SERVER['REQUEST_URI'] ?>" id="blog">
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
                            <textarea name="content"
                                      class="ckeditor-advanced"><?= $postData['content'] ?? '' ?></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="ref-url-input">Url</label>
                            <input type="text" class="form-control" id="ref-url-input" name="ref_url"
                                   placeholder="Enter Reference Url"
                                   value="<?= $postData['ref_url'] ?? '' ?>">
                        </div>

                        <div class="mb-3">
                            <div class="row">
                                <div class="col-6">
                                    <?php
                                    includeFileWithVariables('components/form-control.php', array("type" => "select", "id" => "category", "name" => "category", "label" => "Category", "options" => DEFAULT_BLOG_CATEGORIES, "value" => $postData['category'] ?? $firstSlug));
                                    ?>
                                </div>
                                <div class="col-6">
                                    <label for="choices-text-input" class="form-label">Tags</label>
                                    <input class="form-control" id="choices-text-input" data-choices
                                           data-choices-removeItem
                                           data-choices-limit="Required Limit" placeholder="Enter Tags"
                                           type="text"
                                           name="tags"
                                           value="<?= $postData['tags'] ?? '' ?>"/>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- end card body -->
                </div>
                <!-- end card -->
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
<script src='" . home_url("/assets/libs/@ckeditor/ckeditor5.umd.js") . "'></script>
";
$additionJs = ob_get_clean();

include 'layout.php';
