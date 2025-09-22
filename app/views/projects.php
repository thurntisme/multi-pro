<?php
global $priorities, $status;
require_once 'controllers/ProjectController.php';

$pageTitle = "Project";

$projectController = new ProjectController();
$list = $projectController->listProjects();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action_name'])) {
        if ($_POST['action_name'] === 'delete_record' && isset($_POST['post_id'])) {
            $projectController->deleteProject();
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
            <h5 class="card-title mb-0 flex-grow-1">All Projects</h5>
            <div class="flex-shrink-0">
                <div class="d-flex flex-wrap gap-2">
                    <a class="btn btn-soft-success add-btn"
                        href="<?= App\Helpers\NetworkHelper::home_url('app/project/new') ?>"><i
                            class="ri-add-line align-bottom me-1"></i> Create Project</a>
                </div>
            </div>
        </div>
    </div>
    <div class="card-body border border-dashed border-end-0 border-start-0">
        <form method="get" action="<?= App\Helpers\NetworkHelper::home_url('app/project') ?>">
            <div class="row g-3">
                <div class="col-xxl-4 col-sm-12">
                    <div class="search-box">
                        <input type="text" name="s" class="form-control search bg-light border-light"
                            placeholder="Search for projects or something..." value="<?= $_GET['s'] ?? '' ?>">
                        <i class="ri-search-line search-icon"></i>
                    </div>
                </div>
                <!--end col-->
                <div class="col-xxl-2 col-sm-4">
                    <input type="text" class="form-control bg-light border-light" name="due_date"
                        data-provider="flatpickr" data-date-format="Y-m-d" data-range-date="true"
                        placeholder="Select date range" value="<?= $_GET['due_date'] ?? '' ?>">
                </div>
                <div class="col-xxl-2 col-sm-4">
                    <div class="input-light">
                        <select class="form-control" data-choices data-choices-search-false name="status">
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
                    <button type="submit" class="btn btn-primary"><i class="ri-equalizer-fill me-1 align-bottom"></i>
                        Filters
                    </button>
                    <a href="<?= App\Helpers\NetworkHelper::home_url("app/project") ?>" class="btn btn-danger ms-1"><i
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
            <table class="table align-middle table-nowrap mb-0" id="projectsTable">
                <thead class="table-light text-muted">
                    <tr>
                        <th>Title</th>
                        <th class="text-center">Start Date</th>
                        <th class="text-center">End Date</th>
                        <th class="text-center">Type</th>
                        <th class="text-end">Last Updated</th>
                    </tr>
                </thead>
                <tbody class="list form-check-all">
                    <?php if (count($list['list']) > 0) {
                        foreach ($list['list'] as $item) { ?>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-baseline">
                                        <a class="text-black"
                                            href="<?= App\Helpers\NetworkHelper::home_url('app/project/detail?id=' . $item['id']) ?>"><?= truncateString($item['title'], 50) ?></a>
                                        <ul class="list-inline tasks-list-menu mb-0 ms-3">
                                            <li class="list-inline-item m-0"><a class="edit-item-btn btn btn-link btn-sm"
                                                    href="<?= App\Helpers\NetworkHelper::home_url('app/project/detail?id=' . $item['id']) ?>"><i
                                                        class="ri-eye-fill align-bottom text-muted"></i></a>
                                            </li>
                                            <li class="list-inline-item m-0"><a class="edit-item-btn btn btn-link btn-sm"
                                                    href="<?= App\Helpers\NetworkHelper::home_url('app/project/edit?id=' . $item['id']) ?>"><i
                                                        class="ri-pencil-fill align-bottom text-muted"></i></a>
                                            </li>
                                            <li class="list-inline-item m-0">
                                                <form method="POST" action="<?= $_SERVER['REQUEST_URI'] ?>">
                                                    <input type="hidden" name="action_name" value="delete_record">
                                                    <input type="hidden" name="post_id" value="<?= $item['id'] ?>">
                                                    <button type="submit" class="btn btn-link btn-sm btn-delete-record">
                                                        <i class="ri-delete-bin-5-line align-bottom text-muted"></i>
                                                    </button>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                                <td class="text-center"><?= $systemController->convertDate($item['start_date']) ?></td>
                                <td class="text-center"><?= $systemController->convertDate($item['end_date']) ?></td>
                                <td class="text-center fw-bold text-capitalize"><?= $item['type'] ?></td>
                                <td class="text-end"><?= $systemController->convertDateTime($item['updated_at']) ?></td>
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
