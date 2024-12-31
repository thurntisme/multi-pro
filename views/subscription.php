<?php
require_once 'controllers/SubscriptionController.php';

$pageTitle = "Subscription";

$subscriptionController = new SubscriptionController();
$list = $subscriptionController->listSubscriptions();

ob_start();
?>

<?php
include_once DIR . '/components/alert.php';
?>

<div class="card" id="tasksList">
    <div class="card-header border-0">
        <div class="d-flex align-items-center">
            <h5 class="card-title mb-0 flex-grow-1">All Subscriptions</h5>
            <div class="flex-shrink-0">
                <div class="d-flex flex-wrap gap-2">
                    <a class="btn btn-danger add-btn" href="<?= home_url('subscription/new') ?>"><i
                            class="ri-add-line align-bottom me-1"></i> Create Subscription</a>
                </div>
            </div>
        </div>
    </div>
    <div class="card-body border border-dashed border-end-0 border-start-0">
        <form method="get" action="<?= home_url('subscription') ?>">
            <div class="row g-3">
                <div class="col-xxl-5 col-sm-12">
                    <div class="search-box">
                        <input type="text" name="s" class="form-control search bg-light border-light"
                            placeholder="Search for subscriptions or something..." value="<?= $_GET['s'] ?? '' ?>">
                        <i class="ri-search-line search-icon"></i>
                    </div>
                </div>
                <!--end col-->

                <div class="col-xxl-3 col-sm-4">
                    <div class="input-light">
                        <select class="form-control" data-choices data-choices-search-false
                            name="payment_type">
                            <?php
                            echo '<option value="" ' . (empty($_GET['payment_type']) ? 'selected' : "") . '>All</option>';
                            foreach ($payment_types as $value => $label) {
                                $selected = (!empty($_GET['payment_type']) && $value === $_GET['payment_type']) ? 'selected' : '';
                                echo "<option value=\"$value\" $selected>$label</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <!--end col-->
                <div class="col-xxl-2 col-sm-4">
                    <button type="submit" class="btn btn-primary"> <i
                            class="ri-equalizer-fill me-1 align-bottom"></i>
                        Filters
                    </button>
                </div>
                <!--end col-->
            </div>
            <!--end row-->
        </form>
    </div>
    <!--end card-body-->
    <div class="card-body">
        <div class="table-responsive table-card mb-4">
            <table class="table align-middle table-nowrap mb-0" id="subscriptionsTable">
                <thead class="table-light text-muted">
                    <tr>
                        <th>Service Name</th>
                        <th class="sort text-end pe-5">Amount</th>
                        <th class="sort text-center">Payment Type</th>
                        <th class="sort text-center">Payment Date</th>
                        <th class="sort text-center">Payment Month</th>
                        <th class="sort text-end pe-5">Updated At</th>
                    </tr>
                </thead>
                <tbody class="list form-check-all">
                    <?php if (count($list['list']) > 0) {
                        foreach ($list['list'] as $item) { ?>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-baseline">
                                        <a class="text-black"
                                            href="<?= home_url('app/subscription/detail?id=' . $item['id']) ?>"><?= $item['service_name'] ?></a>
                                        <ul class="list-inline tasks-list-menu mb-0 ms-3">
                                            <li class="list-inline-item m-0"><a
                                                    class="edit-item-btn btn btn-link btn-sm"
                                                    href="<?= home_url('app/subscription/detail?id=' . $item['id']) ?>"><i
                                                        class="ri-eye-fill align-bottom text-muted"></i></a>
                                            </li>
                                            <li class="list-inline-item m-0"><a
                                                    class="edit-item-btn btn btn-link btn-sm"
                                                    href="<?= home_url('app/subscription/edit?id=' . $item['id']) ?>"><i
                                                        class="ri-pencil-fill align-bottom text-muted"></i></a>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                                <td class="text-end pe-5">
                                    <?= $commonController->getCurrencySymbol($item['currency']) . ' ' . $item['amount'] ?></td>
                                <td class="text-center text-capitalize"><?= $item['payment_type'] ?></td>
                                <td class="text-center"><?= $item['payment_date'] ?></td>
                                <td class="text-center"><?= $item['payment_type'] === 'yearly' ? $months[$item['payment_month']] : '' ?></td>
                                <td class="text-end pe-5"><?= $systemController->convertDateTime($item['updated_at']) ?></td>
                            </tr>
                    <?php }
                    } ?>
                </tbody>
            </table>
        </div>
        <?php
        includeFileWithVariables('components/pagination.php', array("count" => $list['count']));
        ?>
    </div>
    <!--end card-body-->
</div>

<?php
$pageContent = ob_get_clean();
