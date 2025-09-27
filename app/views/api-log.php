<?php
global $priorities, $status;
require_once 'controllers/ApiLogController.php';

$pageTitle = "API Logs";

$apiLogController = new ApiLogController();
$list = $apiLogController->listLogs();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action_name'])) {
        if ($_POST['action_name'] === 'delete_record') {
            $apiLogController->deleteLog();
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
            <h5 class="card-title mb-0 flex-grow-1">All Logs</h5>
        </div>
    </div>
    <div class="card-body border border-dashed border-end-0 border-start-0">
        <form method="get" action="<?= App\Helpers\Network::home_url('app/api-log') ?>">
            <div class="row g-3">
                <div class="col-xxl-4 col-sm-12">
                    <div class="search-box">
                        <input type="text" name="s" class="form-control search bg-light border-light"
                            placeholder="Search for api log or something..." value="<?= $_GET['s'] ?? '' ?>">
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
                    <button type="submit" class="btn btn-primary"><i class="ri-equalizer-fill me-1 align-bottom"></i>
                        Filters
                    </button>
                    <a href="<?= App\Helpers\Network::home_url("app/api-log") ?>" class="btn btn-danger ms-1"><i
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
                        <th class="text-center">Method</th>
                        <th class="text-center">Route</th>
                        <th class="text-center">Code</th>
                        <th class="text-center">IP Address</th>
                        <th class="text-end">Timestamp</th>
                        <td></td>
                    </tr>
                </thead>
                <tbody class="list form-check-all">
                    <?php if (count($list['list']) > 0) {
                        foreach ($list['list'] as $item) { ?>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <?= get_api_log_badge($item['status']) ?><span
                                            class="text-muted"><?= truncateString($item['message']) ?></span>
                                        <ul class="list-inline tasks-list-menu mb-0 ms-3">
                                            <li class="list-inline-item m-0"><a class="btn-view-msg btn btn-link btn-sm"
                                                    href="#" data-status="<?= $item['status'] ?>"
                                                    data-msg="<?= $item['message'] ?>"><i
                                                        class="ri-eye-fill align-bottom text-muted"></i></a>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                                <td class="text-center"><?= $item['method'] ?></td>
                                <td class="text-center"><?= $item['route'] ?></td>
                                <td class="text-center"><?= $item['result_code'] ?></td>
                                <td class="text-center"><?= $item['ip_address'] ?></td>
                                <td class="text-end"><?= $systemController->convertDateTime($item['created_at']) ?></td>
                                <td class="d-flex justify-content-center gap-1">
                                    <form method="POST" action="<?= $_SERVER['REQUEST_URI'] ?>">
                                        <input type="hidden" name="post_id" value="<?= $item['id'] ?>">
                                        <input type="hidden" name="action_name" value="delete_record">
                                        <button type="submit" class="btn btn-soft-danger btn-sm btn-delete-record">
                                            <i class="ri-delete-bin-5-line align-bottom"></i>
                                        </button>
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
ob_start(); ?>
<script type="text/javascript">
    $(document).on("click", ".btn-view-msg", function (e) {
        e.preventDefault();
        Swal.fire({
            title: 'API log message',
            text: $(this).attr("data-msg"),
            icon: $(this).attr("data-status"),
            confirmButtonText: 'Okay'
        });
    })
</script>
<?php
$additionJs = ob_get_clean();

$pageContent = ob_get_clean();
