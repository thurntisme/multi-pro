<?php
$pageTitle = "Book";

require_once 'controllers/BookController.php';
$bookController = new BookController();

$modify_type = getLastSegmentFromUrl();

if (!empty($modify_type)) {
    $pageTitle .= " " . $modify_type;
    if ($modify_type === "new") {
        $back_url = home_url("plans");
    } else if ($modify_type == 'edit') {
        if (isset($_GET['id'])) {
            $post_id = $_GET['id'];
            $postData = $bookController->viewBook($post_id);
        }
        $back_url = home_url("plans/view") . '?post_id=' . $post_id;
    }
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['action_name'])) {
            if ($_POST['action_name'] === 'delete_record') {
                $bookController->deleteBook();
            }
        } else {
            if ($modify_type === "new") {
                $bookController->createBook();
            }
            if ($modify_type === "edit") {
                $bookController->updateBook();
            }
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
               href="<?= home_url('app/book/view?id=' . $postData['id']) ?>"><i
                        class="ri-book-read-fill align-bottom  me-1"></i> Read</a>
            <?php
            $additionBtn = ob_get_clean();
            includeFileWithVariables('components/single-button-group.php', array("slug" => "book", "post_id" => $postData['id'], 'modify_type' => $modify_type, 'additionBtn' => $additionBtn));
            ?>
            <form method="POST" action="<?= $_SERVER['REQUEST_URI'] ?>" id="book">
                <div class="card">
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label" for="book-title-input">Title</label>
                            <input type="text" class="form-control" id="book-title-input" name="title"
                                   placeholder="Enter title" value="<?= $postData['title'] ?? '' ?>">
                            <?php if (!empty($post_id)) { ?>
                                <input type="hidden" name="book_id" value="<?= $post_id ?>">
                            <?php } ?>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Content</label>
                            <textarea name="content" class="ckeditor-classic">
                            <?= $postData['content'] ?? '' ?>
                        </textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="book-url-input">Url</label>
                            <input type="text" class="form-control" id="book-url-input" name="url"
                                   placeholder="Enter url"
                                   value="<?= $postData['url'] ?? '' ?>">
                        </div>

                        <div class="mb-3 row">
                            <div class="col-6">
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
                            <?php if (!empty($post_id)) { ?>
                                <div class="col-6">
                                    <label for="choices-status-input" class="form-label">Current Page View</label>
                                    <input type="text" name="view_page" class="form-control"
                                           id="<?= $postData['view_page'] ?? '' ?>">
                                </div>
                            <?php } ?>
                        </div>

                        <div class="mb-3">
                            <label for="choices-text-input" class="form-label">Tags</label>
                            <input class="form-control" id="choices-text-input" data-choices data-choices-removeItem
                                   data-choices-limit="Required Limit" placeholder="Enter Tags" type="text"
                                   name="tags"
                                   value="<?= $postData['tags'] ?? '' ?>"/>
                        </div>
                    </div>
                    <!-- end card body -->
                </div>
                <!-- end card -->

                <!-- end card -->
                <div class="text-center mb-4">
                    <button type="submit"
                            class="btn btn-success w-sm"><?= $modify_type === "new" ? "Create" : "Save" ?></button>
                </div>
            </form>
        </div>
        <!-- end col -->
    </div>

<?php
include_once DIR . '/components/modal-delete.php';

$pageContent = ob_get_clean();

ob_start();
echo "
<script src='" . home_url("/assets/libs/@ckeditor/ckeditor5-build-classic/build/ckeditor.js") . "'></script>
";
$additionJs = ob_get_clean();

include 'layout.php';
