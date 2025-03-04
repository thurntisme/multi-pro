<?php
$pageTitle = "Project Detail";

require_once 'controllers/ProjectController.php';
$projectController = new ProjectController();

$modify_type = getLastSegmentFromUrl();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['id'])) {
        $post_id = $_GET['id'];
        $postData = $projectController->viewProject($post_id);
        $tags = !empty($postData['tags']) ? explode(',', $postData['tags']) : [];
    }
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action_name'])) {
        if ($_POST['action_name'] === 'delete_record') {
            $projectController->deleteProject();
        }
        if ($_POST['action_name'] === 'new_task') {
            $projectController->createTask();
        }
        if ($_POST['action_name'] === 'delete_task') {
            $projectController->deleteTask();
        }
    }
}

ob_start();
?>
<div class="row">
    <div class="col-xl-8 col-md-10 offset-xl-2 offset-md-1">
        <?php
        includeFileWithVariables('components/single-button-group.php', array("slug" => "project", "post_id" => $postData['id'], 'modify_type' => $modify_type));
        ?>
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0"><?= $postData['title'] ?></h4>
            </div>
            <div class="card-body">
                <div class="text-muted">
                    <?php if (!empty($postData['content'])) { ?>
                        <h6 class="mb-3 fw-semibold text-uppercase">Content</h6>
                        <div class="mb-3">
                            <?= $postData['content'] ?>
                        </div>
                    <?php } ?>

                    <div class="mb-3">
                        <div>
                            <label>Dev Url:</label>
                            <a href="<?= $postData['dev_url'] ?? '#' ?>" target="_blank"><?= $postData['dev_url'] ?? '' ?></a>
                        </div>
                        <div>
                            <label>Staging Url:</label>
                            <a href="<?= $postData['staging_url'] ?? '#' ?>" target="_blank"><?= $postData['staging_url'] ?? '' ?></a>
                        </div>
                        <div>
                            <label>Production Url:</label>
                            <a href="<?= $postData['production_url'] ?? '#' ?>" target="_blank"><?= $postData['production_url'] ?? '' ?></a>
                        </div>
                        <div>
                            <label>Source Url:</label>
                            <a href="<?= $postData['source_url'] ?? '#' ?>" target="_blank"><?= $postData['source_url'] ?? '' ?></a>
                        </div>
                    </div>

                    <?php if (!empty($postData['due_date']) && $postData['due_date'] != '0000-00-00') { ?>
                        <h6 class="mb-3 fw-semibold text-uppercase">Due Date </h6>
                        <div class="mb-3">
                            <?= $postData['due_date'] ?>
                        </div>
                    <?php } ?>

                    <div class="pt-3 border-top border-top-dashed mt-4">
                        <div class="row">
                            <div class="col-lg-3 col-sm-6">
                                <div>
                                    <p class="mb-2 text-uppercase fw-medium">Type :</p>
                                    <span class="fw-bold text-capitalize"><?= $postData['type'] ?? '' ?></span>
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-6">
                                <div>
                                    <p class="mb-2 text-uppercase fw-medium">Status :</p>
                                    <?= $postData['status'] ? renderStatusBadge($postData['status'], 'div', 12) : '' ?>
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-6">
                                <div>
                                    <p class="mb-2 text-uppercase fw-medium">Start Date :</p>
                                    <h5 class="fs-15 mb-0">
                                        <?= $systemController->convertDate($postData['start_date']) ?>
                                    </h5>
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-6">
                                <div>
                                    <p class="mb-2 text-uppercase fw-medium">End Date :</p>
                                    <h5 class="fs-15 mb-0">
                                        <?= $systemController->convertDate($postData['end_date']) ?>
                                    </h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end card body -->
        </div>
        <?php if ($tags && count($tags) > 0) : ?>
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-4">Tags</h5>
                    <div class="d-flex flex-wrap gap-2 fs-16">
                        <?php foreach ($tags as $key => $value) { ?>
                            <div class="badge fw-medium bg-secondary-subtle text-secondary"><?= $value ?>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <div class="card">
            <div class="card-header border-0">
                <div class="d-flex align-items-center">
                    <h5 class="card-title mb-0 flex-grow-1">All Tasks</h5>
                </div>
            </div>
            <div class="card-body border border-dashed border-end-0 border-start-0">
                <form method="post" action="<?= $_SERVER['REQUEST_URI'] ?>">
                    <?php if (!empty($post_id)) { ?>
                        <input type="hidden" name="project_id" value="<?= $post_id ?>">
                        <input type="hidden" name="action_name" value="new_task">
                    <?php } ?>
                    <div class="row">
                        <div class="col-4">
                            <input type="text" name="task_title" class="form-control search bg-light border-light"
                                placeholder="Input task title">
                        </div>
                        <div class="col-4">
                            <input type="text" class="form-control" id="datepicker-deadline-input"
                                placeholder="Enter due date" data-provider="flatpickr" name="task_due_date" data-date-format="Y-m-d">
                        </div>
                        <button type="submit" class="btn btn-soft-success add-btn w-25"><i
                                class="ri-add-line align-bottom me-1"></i> Create Task</button>
                    </div>
                </form>
            </div>
            <div class="card-body border border-dashed border-end-0 border-start-0">
                <form method="get" action="<?= home_url("app/project/detail?id=" . $post_id) ?>">
                    <div class="row g-3">
                        <div class="col-xxl-4 col-sm-12">
                            <div class="search-box">
                                <input type="text" name="s" class="form-control search bg-light border-light"
                                    placeholder="Search for task..." value="<?= $_GET['s'] ?? '' ?>">
                                <i class="ri-search-line search-icon"></i>
                            </div>
                        </div>
                        <!--end col-->
                        <div class="col-xxl-4 col-sm-4">
                            <input type="text" class="form-control bg-light border-light" name="due_date"
                                data-provider="flatpickr" data-date-format="Y-m-d" data-range-date="true"
                                placeholder="Select date range" value="<?= $_GET['due_date'] ?? '' ?>">
                        </div>
                        <!--end col-->
                        <div class="col-xxl-4 col-sm-4 d-flex">
                            <button type="submit" class="btn btn-primary"><i
                                    class="ri-equalizer-fill me-1 align-bottom"></i>
                                Filters
                            </button>
                            <a href="<?= home_url("app/project/detail?id=" . $post_id) ?>" class="btn btn-danger ms-1"><i
                                    class="ri-delete-bin-2-fill me-1 align-bottom"></i>Reset</a>
                        </div>
                        <!--end col-->
                    </div>
                    <!--end row-->
                </form>
            </div>
            <!--end card-body-->
            <div class="card-body" id="tasksList">
                <div class="table-responsive table-card mb-4">
                    <table class="table align-middle table-nowrap mb-0" id="projectsTable">
                        <thead class="table-light text-muted">
                            <tr>
                                <th>Title</th>
                                <th>Description</th>
                                <th class="text-center">Due Date</th>
                                <th class="text-center">Status</th>
                                <th class="text-end">Last Updated</th>
                            </tr>
                        </thead>
                        <tbody class="list form-check-all">
                            <?php if (!empty($postData['tasks']) && count($postData['tasks']) > 0) {
                                foreach ($postData['tasks'] as $item) { ?>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-baseline">
                                                <a class="text-black"
                                                    href="<?= home_url('app/project/detail?id=' . $item['id']) ?>"><?= $item['title'] ?></a>
                                                <ul class="list-inline tasks-list-menu mb-0 ms-3">
                                                    <li class="list-inline-item m-0">
                                                        <form method="POST" action="<?= $_SERVER['REQUEST_URI'] ?>">
                                                            <input type="hidden" name="action_name" value="delete_task">
                                                            <input type="hidden" name="task_id" value="<?= $item['id'] ?>">
                                                            <button type="submit" class="btn btn-link btn-sm btn-delete-record">
                                                                <i
                                                                    class="ri-delete-bin-5-line align-bottom text-muted"></i>
                                                            </button>
                                                        </form>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                        <td><?= truncateString($item['desciption'], 50) ?></td>
                                        <td class="text-center"><?= $systemController->convertDate($item['due_date']) ?></td>
                                        <td class="text-center"><?= renderStatusBadge($item['status']) ?></td>
                                        <td class="text-end"><?= $systemController->convertDateTime($item['updated_at']) ?></td>
                                    </tr>
                            <?php }
                            } ?>
                        </tbody>
                    </table>
                    <!--end table-->
                </div>
            </div>
            <!--end card-body-->
        </div>
    </div>
</div>

<?php
$pageContent = ob_get_clean();
