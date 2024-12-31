<?php
global $priorities, $status;
$pageTitle = "Plan";

require_once 'controllers/PlanController.php';
$planController = new PlanController();

$modify_type = getLastSegmentFromUrl();

if (!empty($modify_type)) {
    $pageTitle .= " " . $modify_type;
    if ($modify_type === "new") {
        $back_url = home_url("plans");
    } else if ($modify_type == 'edit') {
        if (isset($_GET['id'])) {
            $post_id = $_GET['id'];
            $postData = $planController->viewPlan($post_id);
        }
        $back_url = home_url("plans/view") . '?post_id=' . $post_id;
    }
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['action_name'])) {
            if ($_POST['action_name'] === 'delete_record') {
                $planController->deletePlan();
            }
        } else {
            if ($modify_type === "new") {
                $planController->createPlan();
            }
            if ($modify_type === "edit") {
                $planController->updatePlan();
            }
        }
    }
}

ob_start();
?>
<div class="row">
    <div class="col-xl-8 col-md-10 offset-xl-2 offset-md-1">
        <?php
        includeFileWithVariables('components/single-button-group.php', array("slug" => "plan", "post_id" => $postData['id'] ?? '', 'modify_type' => $modify_type));
        ?>
        <form method="POST" action="<?= $_SERVER['REQUEST_URI'] ?>" id="plan">
            <?php csrfInput() ?>
            <div class="card">
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label" for="plan-title-input">Title</label>
                        <input type="text" class="form-control" id="plan-title-input" name="title"
                            placeholder="Enter title" value="<?= $postData['title'] ?? '' ?>">
                        <?php if (!empty($post_id)) { ?>
                            <input type="hidden" name="plan_id" value="<?= $post_id ?>">
                        <?php } ?>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Content</label>
                        <textarea name="content" class="ckeditor-classic">
                                <?= $postData['content'] ?? '' ?>
                            </textarea>
                    </div>

                    <div class="row mb-3">
                        <div class="col-lg-4">
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
                        <div class="col-lg-4">
                            <div>
                                <label for="datepicker-start-date-input" class="form-label">Start Date</label>
                                <input type="text" class="form-control" id="datepicker-start-date-input"
                                    placeholder="Enter start date" data-provider="flatpickr" name="start_date"
                                    <?= !empty($postData['start_date']) && $postData['start_date'] != '0000-00-00' ? 'value="' . $postData["start_date"] . '"' : '' ?>
                                    data-date-format="Y-m-d">
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div>
                                <label for="datepicker-end-date-input" class="form-label">End Date</label>
                                <input type="text" class="form-control" id="datepicker-end-date-input"
                                    placeholder="Enter end date" data-provider="flatpickr" name="end_date"
                                    <?= !empty($postData['end_date']) && $postData['end_date'] != '0000-00-00' ? 'value="' . $postData["end_date"] . '"' : '' ?>
                                    data-date-format="Y-m-d">
                            </div>
                        </div>
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
