<?php
$pageTitle = "API logs";
$commonController = new CommonController();
$list = $commonController->convertResources(get_api_logs());

ob_start();
?>

<?php
include_once DIR . '/components/alert.php';
?>

<div class="card" id="tasksList">
    <div class="card-header border-0">
        <div class="d-flex align-items-center">
            <h5 class="card-title mb-0 flex-grow-1">API Logs</h5>
        </div>
    </div>
    <div class="card-body border border-dashed border-end-0 border-start-0">
        <form method="get" action="<?= home_url('app/api-log') ?>">
            <div class="row g-3">
                <div class="col-xxl-4 col-sm-12">
                    <div class="search-box">
                        <input type="text" name="s" class="form-control search bg-light border-light"
                            placeholder="Search for api log message or something..." value="<?= $_GET['s'] ?? '' ?>">
                        <i class="ri-search-line search-icon"></i>
                    </div>
                </div>
                <div class="col-xxl-2 col-sm-4">
                    <div class="input-light">
                        <select class="form-control" data-choices data-choices-search-false
                            name="status">
                            <?php
                            echo '<option value="" ' . (!empty($_GET['status']) ? 'selected' : "") . '>Select Status</option>';
                            foreach ($status as $value => $label) {
                                $selected = (!empty($_GET['status']) && $value === $_GET['status']) ? 'selected' : '';
                                echo "<option value=\"$value\" $selected>$label</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <!--end col-->
                <div class="col-xxl-2 col-sm-4 d-flex">
                    <button type="submit" class="btn btn-primary"><i
                            class="ri-equalizer-fill me-1 align-bottom"></i>
                        Filters
                    </button>
                    <a href="<?= home_url("app/api-log") ?>" class="btn btn-danger ms-1"><i
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
                        <th class="text-end">Timestamp</th>
                    </tr>
                </thead>
                <tbody class="list form-check-all">
                    <?php if (count($list['resources']) > 0) {
                        foreach ($list['resources'] as $item) { ?>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <?= get_api_log_badge($item['context']['result']['status']) ?><span class="text-muted"><?= $item['context']['result']['message'] ?></span>
                                    </div>
                                </td>
                                <td class="text-center"><?= $item['context']['method'] ?></td>
                                <td class="text-center"><?= $item['context']['route'] ?></td>
                                <td class="text-center"><?= $item['context']['result']['code'] ?></td>
                                <td class="text-end"><?= $commonController->convertDateTime($item['timestamp']) ?></td>
                            </tr>
                    <?php }
                    } ?>
                </tbody>
            </table>
        </div>
        <?php
        includeFileWithVariables('components/pagination.php', array("count" => $list['total_items'], "perPage" => $list['per_page']));
        ?>
    </div>
    <!--end card-body-->
</div>


<?php
$pageContent = ob_get_clean();

include 'layout.php';
