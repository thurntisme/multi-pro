<?php
require_once 'controllers/ReportWorkingController.php';

$pageTitle = "Report Working";

$reportWorkingController = new ReportWorkingController();
$list = $reportWorkingController->listReportWorkings();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action_name'])) {
        if ($_POST['action_name'] === 'delete_record' && isset($_POST['post_id'])) {
            $reportWorkingController->deleteReport();
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
            <h5 class="card-title mb-0 flex-grow-1">All Reports</h5>
            <div class="flex-shrink-0">
                <div class="d-flex flex-wrap gap-2">
                    <a class="btn btn-soft-success add-btn" href="<?= home_url('app/report-working/new') ?>"><i
                            class="ri-add-line align-bottom me-1"></i> Create Report</a>
                </div>
            </div>
        </div>
    </div>
    <div class="card-body border border-dashed border-end-0 border-start-0">
        <form method="get" action="<?= home_url('app/report-working') ?>">
            <div class="row g-3">
                <div class="col-xxl-5 col-sm-12">
                    <div class="search-box">
                        <input type="text" name="s" class="form-control search bg-light border-light"
                            placeholder="Search for report or something..." value="<?= $_GET['s'] ?? '' ?>">
                        <i class="ri-search-line search-icon"></i>
                    </div>
                </div>
                <!--end col-->

                <div class="col-xxl-3 col-sm-4">
                    <input type="text" class="form-control bg-light border-light" id="demo-datepicker"
                        data-provider="flatpickr" data-date-format="Y-m-d" data-range-date="true" name="working_date"
                        placeholder="Select date range" value="<?= $_GET['working_date'] ?? '' ?>">
                </div>
                <!--end col-->

                <!--end col-->
                <div class="col-xxl-2 col-sm-4 d-flex">
                    <button type="submit" class="btn btn-primary w-100"> <i
                            class="ri-equalizer-fill me-1 align-bottom"></i>
                        Filters
                    </button>
                    <a href="<?= home_url("app/report-working") ?>" class="btn btn-danger ms-1"><i
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
                        <th>Project</th>
                        <th class="text-center">Working Date</th>
                        <th class="sort text-end pe-5">Created Date</th>
                        <th class="sort text-end pe-5">Updated Date</th>
                    </tr>
                </thead>
                <tbody class="list form-check-all">
                    <?php if (count($list['list']) > 0) {
                        foreach ($list['list'] as $item) { ?>
                            <tr>
                                <td>
                                    <div class="d-flex">
                                        <?= $item['title'] ?>
                                        <div class="flex-shrink-0 ms-4">
                                            <ul class="list-inline tasks-list-menu mb-0">
                                                <li class="list-inline-item m-0"><a
                                                        class="edit-item-btn btn btn-link btn-sm"
                                                        href="<?= home_url('app/report-working/detail?id=' . $item['id']) ?>"><i
                                                            class="ri-eye-fill align-bottom text-muted"></i></a>
                                                </li>
                                                <li class="list-inline-item m-0"><a
                                                        class="edit-item-btn btn btn-link btn-sm"
                                                        href="<?= home_url('app/report-working/edit?id=' . $item['id']) ?>"><i
                                                            class="ri-pencil-fill align-bottom text-muted"></i></a>
                                                </li>
                                                <li class="list-inline-item m-0">
                                                    <form method="POST" action="<?= $_SERVER['REQUEST_URI'] ?>">
                                                        <input type="hidden" name="action_name" value="delete_record">
                                                        <input type="hidden" name="post_id" value="<?= $item['id'] ?>">
                                                        <button type="submit" class="btn btn-link btn-sm btn-delete-record">
                                                            <i
                                                                class="ri-delete-bin-5-line align-bottom text-muted"></i>
                                                        </button>
                                                    </form>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </td>
                                <td><?= $item['project'] ?></td>
                                <td class="text-center"><?= $item['working_date'] ?></td>
                                <td class="text-end pe-5"><?= $systemController->convertDate($item['created_at']) ?></td>
                                <td class="text-end pe-5"><?= $systemController->convertDate($item['updated_at']) ?></td>
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
