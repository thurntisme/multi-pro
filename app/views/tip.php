<?php
require_once 'controllers/TipController.php';

$pageTitle = "Tip";

$tipController = new TipController();
$list = $tipController->listTips();

ob_start();
?>

<?php
include_once DIR . '/components/alert.php';
?>

<div class="card" id="tasksList">
    <div class="card-header border-0">
        <div class="d-flex align-items-center">
            <h5 class="card-title mb-0 flex-grow-1">All Tips</h5>
            <div class="flex-shrink-0">
                <div class="d-flex flex-wrap gap-2">
                    <a class="btn btn-danger add-btn" href="<?= App\Helpers\NetworkHelper::home_url('tip/new') ?>"><i
                            class="ri-add-line align-bottom me-1"></i> Create Tip</a>
                </div>
            </div>
        </div>
    </div>
    <div class="card-body border border-dashed border-end-0 border-start-0">
        <form method="get" action="<?= App\Helpers\NetworkHelper::home_url('tip') ?>">
            <div class="row g-3">
                <div class="col-xxl-5 col-sm-12">
                    <div class="search-box">
                        <input type="text" name="s" class="form-control search bg-light border-light"
                            placeholder="Search for tips or something...">
                        <i class="ri-search-line search-icon"></i>
                    </div>
                </div>
                <!--end col-->

                <div class="col-xxl-3 col-sm-4">
                    <input type="text" class="form-control bg-light border-light" id="demo-datepicker"
                        data-provider="flatpickr" data-date-format="d M, Y" data-range-date="true"
                        placeholder="Select date range">
                </div>
                <!--end col-->

                <div class="col-xxl-3 col-sm-4">
                    <div class="input-light">
                        <select class="form-control" data-choices data-choices-search-false
                            name="choices-single-default" id="idStatus">
                            <option value="" selected>All</option>
                            <option value="monthly">Monthly</option>
                            <option value="yearly">Yearly</option>
                        </select>
                    </div>
                </div>
                <!--end col-->
                <div class="col-xxl-1 col-sm-4">
                    <button type="submit" class="btn btn-primary w-100"> <i
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
            <table class="table align-middle table-nowrap mb-0" id="tipsTable">
                <thead class="table-light text-muted">
                    <tr>
                        <th>Title</th>
                        <th>Tags</th>
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
                                        <div class="flex-grow-1"><?= truncateString($item['title'], 50) ?></div>
                                        <div class="flex-shrink-0 ms-4">
                                            <ul class="list-inline tasks-list-menu mb-0">
                                                <li class="list-inline-item"><a
                                                        href="<?= App\Helpers\NetworkHelper::home_url('tip/detail?id=' . $item['id']) ?>"><i
                                                            class="ri-eye-fill align-bottom me-2 text-muted"></i></a></li>
                                                <li class="list-inline-item"><a class="edit-item-btn"
                                                        href="<?= App\Helpers\NetworkHelper::home_url('tip/edit?id=' . $item['id']) ?>"><i
                                                            class="ri-pencil-fill align-bottom me-2 text-muted"></i></a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </td>
                                <td><?= $item['tags'] ?></td>
                                <td class="text-end pe-5"><?= $systemController->convertDate($item['created_at']) ?></td>
                                <td class="text-end pe-5"><?= $systemController->convertDate($item['updated_at']) ?></td>
                            </tr>
                        <?php }
                    } ?>
                </tbody>
            </table>
            <!--end table-->
            <div class="noresult" style="display: none">
                <div class="text-center">
                    <lord-icon src="https://cdn.lordicon.com/msoeawqm.json" trigger="loop"
                        colors="primary:#121331,secondary:#08a88a" style="width:75px;height:75px"></lord-icon>
                    <h5 class="mt-2">Sorry! No Result Found</h5>
                    <p class="text-muted mb-0">Weve searched more than 200k+ tips We did not find any
                        tips for you search.</p>
                </div>
            </div>
        </div>
        <?php
        includeFileWithVariables('components/pagination.php', array("count" => $list['count']));
        ?>
    </div>
    <!--end card-body-->
</div>

<?php
$pageContent = ob_get_clean();
