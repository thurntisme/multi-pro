<?php
require_once 'controllers/NotificationController.php';

$pageTitle = "My Notification";

$notificationController = new NotificationController();
$list = $notificationController->listNotifications();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action_name'])) {
        if ($_POST['action_name'] === 'mark_read_record') {
            $notificationController->readNotification();
        }
    }
}

ob_start();
?>

<?php
include_once DIR . '/components/alert.php';
?>

<div class="card" id="tasksList">
    <div class="card-header border-0">
        <div class="d-flex align-items-center">
            <h5 class="card-title mb-0 flex-grow-1">All Notifications</h5>
            <div class="flex-shrink-0">
                <div class="d-flex flex-wrap gap-2">
                    <a class="btn btn-soft-success add-btn" href="<?= home_url('app/todo/new') ?>"><i
                            class="ri-add-line align-bottom me-1"></i> Create Todo</a>
                </div>
            </div>
        </div>
    </div>
    <div class="card-body border border-dashed border-end-0 border-start-0">
        <form method="get" action="<?= home_url('app/todo') ?>">
            <div class="row g-3">
                <div class="col-xxl-4 col-sm-12">
                    <div class="search-box">
                        <input type="text" name="s" class="form-control search bg-light border-light"
                            placeholder="Search for todos or something..." value="<?= $_GET['s'] ?? '' ?>">
                        <i class="ri-search-line search-icon"></i>
                    </div>
                </div>
                <!--end col-->
                <div class="col-xxl-2 col-sm-4">
                    <input type="text" class="form-control bg-light border-light" name="log_date"
                        data-provider="flatpickr" data-date-format="Y-m-d" data-range-date="true"
                        placeholder="Select date range" value="<?= $_GET['log_date'] ?? '' ?>">
                </div>
                <!--end col-->
                <div class="col-xxl-2 col-sm-4 d-flex">
                    <button type="submit" class="btn btn-primary"><i
                            class="ri-equalizer-fill me-1 align-bottom"></i>
                        Filters
                    </button>
                    <a href="<?= home_url("app/todo") ?>" class="btn btn-danger ms-1"><i
                            class="ri-delete-bin-2-fill me-1 align-bottom"></i>Reset</a>
                </div>
                <!--end col-->
            </div>
            <!--end row-->
        </form>
    </div>
    <!--end card-body-->
    <div class="card-body">
        <div class="table-responsive table-card mb-4">
            <table class="table align-middle table-nowrap mb-0" id="todosTable">
                <thead class="table-light text-muted">
                    <tr>
                        <th>Title</th>
                        <th class="text-center">Type</th>
                        <th>Message</th>
                        <th class="text-end">Log Time</th>
                        <th class="text-end">Update Time</th>
                        <th class="text-center"></th>
                    </tr>
                </thead>
                <tbody class="list form-check-all">
                    <?php if (count($list['list']) > 0) {
                        foreach ($list['list'] as $item) { ?>
                            <tr>
                                <td>
                                    <span class="text-black"><?= $item['title'] ?></span>
                                </td>
                                <td class="text-center"><?= $item['type'] ?></td>
                                <td><?= $item['message'] ?></td>
                                <td class="text-end"><?= $systemController->convertDateTime($item['created_at']) ?></td>
                                <td class="text-end"><?= $systemController->convertDateTime($item['updated_at']) ?></td>
                                <td class="d-flex justify-content-center gap-1">
                                    <form method="POST" action="<?= $_SERVER['REQUEST_URI'] ?>">
                                        <input type="hidden" name="post_id" value="<?= $item['id'] ?>">
                                        <input type="hidden" name="action_name" value="mark_read_record">
                                        <?php if ($item['is_read'] === 0) { ?>
                                            <button type="submit" class="btn btn-soft-success btn-sm">
                                                <i
                                                    class="ri-check-line align-bottom"></i> Mark read
                                            </button>
                                        <?php } else { ?>
                                            <button type="submit" class="btn btn-soft-warning btn-sm">
                                                <i
                                                    class="ri-close-line align-bottom"></i> Mark unread
                                            </button>
                                        <?php } ?>
                                    </form>
                                </td>
                            </tr>
                    <?php }
                    } ?>
                </tbody>
            </table>
            <!--end table-->
        </div>
        <?php
        includeFileWithVariables('components/pagination.php', array("count" => $list['count']));
        ?>
    </div>
    <!--end card-body-->
</div>


<?php
$pageContent = ob_get_clean();
