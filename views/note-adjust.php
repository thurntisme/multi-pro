<?php
$pageTitle = "Note";

require_once 'controllers/NoteController.php';
$noteController = new NoteController();

$modify_type = getLastSegmentFromUrl();

if (!empty($modify_type)) {
    $pageTitle .= " " . $modify_type;
    if ($modify_type === "new") {
        $back_url = home_url("plans");
    } else if ($modify_type == 'edit') {
        if (isset($_GET['id'])) {
            $post_id = $_GET['id'];
            $postData = $noteController->viewNote($post_id);
        }
        $back_url = home_url("plans/view") . '?post_id=' . $post_id;
    }
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['action_name'])) {
            if ($_POST['action_name'] === 'delete_record') {
                $noteController->deleteNote();
            }
        } else {
            if ($modify_type === "new") {
                $noteController->createNote();
            }
            if ($modify_type === "edit") {
                $noteController->updateNote();
            }
        }
    }
}

ob_start();
?>
    <div class="row">
        <div class="col-xl-8 col-md-10 offset-xl-2 offset-md-1">
            <?php
            includeFileWithVariables('components/single-button-group.php', array("slug" => "note", "post_id" => $postData['id'] ?? '', 'modify_type' => $modify_type));
            ?>
            <form method="POST" action="<?= $_SERVER['REQUEST_URI'] ?>" id="note">
                <div class="card">
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label" for="note-title-input">Title</label>
                            <input type="text" class="form-control" id="note-title-input" name="title"
                                   placeholder="Enter title" value="<?= $postData['title'] ?? '' ?>">
                            <?php if (!empty($post_id)) { ?>
                                <input type="hidden" name="note_id" value="<?= $post_id ?>">
                            <?php } ?>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Content</label>
                            <textarea name="content" class="ckeditor-classic">
                            <?= $postData['content'] ?? '' ?>
                        </textarea>
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
                    <a href="<?= home_url('note') ?>" class="btn btn-light w-sm">Back</a>
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
