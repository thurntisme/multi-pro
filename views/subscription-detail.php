<?php
$pageTitle = "Subscription Detail";

require_once 'controllers/SubscriptionController.php';
$subscriptionController = new SubscriptionController();

$modify_type = getLastSegmentFromUrl();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['id'])) {
        $post_id = $_GET['id'];
        $postData = $subscriptionController->viewSubscription($post_id);
    }
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action_name'])) {
        if ($_POST['action_name'] === 'delete_record') {
            $subscriptionController->deleteSubscription();
        }
    }
}

ob_start();
?>
<div class="row">
    <div class="col-xl-8 col-md-10 offset-xl-2 offset-md-1">
        <?php
        includeFileWithVariables('components/single-button-group.php', array("slug" => "subscription", "post_id" => $postData['id'], 'modify_type' => $modify_type));
        ?>
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0"><?= $postData['service_name'] ?></h4>
            </div>
            <div class="card-body">
                <div class="text-muted">
                    <h6 class="mb-3 fw-semibold text-uppercase">Description</h6>
                    <div>
                        <?= $postData['description'] ?>
                    </div>

                    <div class="pt-3 border-top border-top-dashed mt-4">
                        <h6 class="mb-3 fw-semibold text-uppercase">Variation</h6>
                        <div>
                            <ul class="list-group">
                                <li class="list-group-item"><span class="w-50 d-inline-block">Payment
                                        Type:</span>
                                    <strong
                                        class="text-capitalize"><?= $postData['payment_type'] ?></strong>
                                </li>
                                <li class="list-group-item"><span class="w-50 d-inline-block">Payment
                                        Date:</span>
                                    <strong><?= $postData['payment_date'] ?></strong>
                                </li>
                                <?php if ($postData['payment_type'] == 'yearly') { ?>
                                    <li class="list-group-item"><span class="w-50 d-inline-block">Payment
                                            Month:</span>
                                        <strong><?= $months[$postData['payment_month']] ?></strong>
                                    </li>
                                <?php } ?>
                                <li class=" list-group-item"><span class="w-50 d-inline-block">Payment
                                        Amount:</span>
                                    <strong><?= $commonController->getCurrencySymbol($postData['currency']) . ' ' . $postData['amount'] ?>
                                        </td></strong>
                                </li>
                            </ul>
                        </div>
                        <!-- end row -->
                    </div>

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
            <!-- end card body -->
        </div>
    </div>
</div>

<?php
$pageContent = ob_get_clean();
