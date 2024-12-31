<?php
$pageTitle = "Subscription";

require_once 'controllers/SubscriptionController.php';
$subscriptionController = new SubscriptionController();

$modify_type = getLastSegmentFromUrl();

if (!empty($modify_type)) {
    $pageTitle .= " " . $modify_type;
    if ($modify_type === "new") {
        $back_url = home_url("plans");
    } else if ($modify_type == 'edit') {
        if (isset($_GET['id'])) {
            $post_id = $_GET['id'];
            $postData = $subscriptionController->viewSubscription($post_id);
        }
        $back_url = home_url("plans/view") . '?post_id=' . $post_id;
    }
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['action_name'])) {
            if ($_POST['action_name'] === 'delete_record') {
                $subscriptionController->deleteSubscription();
            }
        } else {
            if ($modify_type === "new") {
                $subscriptionController->createSubscription();
            }
            if ($modify_type === "edit") {
                $subscriptionController->updateSubscription();
            }
        }
    };
}

ob_start();
?>

<div class="row">
    <div class="col-xl-8 col-md-10 offset-xl-2 offset-md-1">
        <?php
        includeFileWithVariables('components/single-button-group.php', array("slug" => "subscription", "post_id" => $postData['id'], 'modify_type' => $modify_type));
        ?>
        <form method="POST" action="<?= $_SERVER['REQUEST_URI'] ?>" id="subscription">
            <?php csrfInput() ?>
            <div class="card">
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label" for="project-title-input">Service name</label>
                        <input type="text" class="form-control" id="project-title-input" name="service_name"
                            placeholder="Enter service name" value="<?= $postData['service_name'] ?? '' ?>" required>
                        <?php if (!empty($post_id)) { ?>
                            <input type="hidden" name="subscription_id" value="<?= $post_id ?>">
                        <?php } ?>
                    </div>

                    <div class="row">
                        <div class="col-lg-4">
                            <div class="mb-3">
                                <label class="form-label" for="currency">Currency</label>
                                <select class="form-select" name="currency" id="currency" data-choices data-choices-search-false data-choices-sorting-false>
                                    <?php
                                    foreach (DEFAULT_CURRENCY as $key => $value) { ?>
                                        <option value="<?= $key ?>"
                                            <?= empty($postData['currency']) ? ($key === 'VND' ? 'selected' : '') : (!empty($postData['currency']) && $key === $postData['currency'] ? 'selected' : '') ?>>
                                            <?= $key . ' (' . $value . ')' ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-8">
                            <div class="mb-3">
                                <label class="form-label" for="project-thumbnail-img">Amount</label>
                                <div class="input-group">
                                    <input type="number" class="form-control" aria-label="Amount "
                                        name="amount" value="<?= $postData['amount'] ?? '' ?>" min="1" required>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-4">
                            <div class="mb-3">
                                <?= generateFormControl("payment_type", "payment_type", $postData['payment_type'] ?? 'monthly', "", "select", "Payment Type", $payment_types) ?>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="mb-3">
                                <label for="payment_date" class="form-label">Payment Date</label>
                                <input type="number" id="payment_date" class="form-control"
                                    name="payment_date" min="1" max="31" value="<?= $postData['payment_date'] ?? date("j") ?>"
                                    placeholder="Select payment date">
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="mb-3 <?= $postData['payment_type'] !== 'yearly' ? 'd-none' : '' ?>">
                                <?= generateFormControl("payment_month", "payment_month", $postData['payment_month'] ?? (int)date("n"), "", "select", "Payment Month", $months) ?>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="ckeditor-classic">
                            <?= $postData['description'] ?? '' ?>
                        </textarea>
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

ob_start(); ?>
<script src='<?= home_url("/assets/libs/@ckeditor/ckeditor5-build-classic/build/ckeditor.js") ?>'></script>
<script type="text/javascript">
    $("#payment_type").on("change", function() {
        const payment_type = $(this).val();
        if (payment_type === "yearly") {
            $("#payment_month").closest('.mb-3').removeClass('d-none');
        } else {
            $("#payment_month").closest('.mb-3').addClass('d-none');
        }
    })
</script>
<?php
$additionJs = ob_get_clean();
