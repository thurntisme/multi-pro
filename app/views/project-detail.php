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
    <div class="col-12">
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
                            <a href="<?= $postData['dev_url'] ?? '#' ?>"
                                target="_blank"><?= $postData['dev_url'] ?? '' ?></a>
                        </div>
                        <div>
                            <label>Staging Url:</label>
                            <a href="<?= $postData['staging_url'] ?? '#' ?>"
                                target="_blank"><?= $postData['staging_url'] ?? '' ?></a>
                        </div>
                        <div>
                            <label>Production Url:</label>
                            <a href="<?= $postData['production_url'] ?? '#' ?>"
                                target="_blank"><?= $postData['production_url'] ?? '' ?></a>
                        </div>
                        <div>
                            <label>Source Url:</label>
                            <a href="<?= $postData['source_url'] ?? '#' ?>"
                                target="_blank"><?= $postData['source_url'] ?? '' ?></a>
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
        <?php if ($tags && count($tags) > 0): ?>
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
                    <button data-bs-toggle="modal" data-bs-target="#createTaskModal"
                        class="btn btn-soft-success add-btn"><i class="ri-add-line align-bottom me-1"></i> Create
                        Task</button>
                </div>
            </div>
            <div class="card-body border border-dashed border-end-0 border-start-0">
                <form method="get"
                    action="<?= App\Helpers\NetworkHelper::home_url("app/project/detail?id=" . $post_id) ?>">
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
                            <a href="<?= App\Helpers\NetworkHelper::home_url("app/project/detail?id=" . $post_id) ?>"
                                class="btn btn-danger ms-1"><i
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
                                <th class="text-center">Status</th>
                                <th class="text-center">Due Date</th>
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
                                                    href="<?= App\Helpers\NetworkHelper::home_url('app/task/detail?id=' . $item['id']) ?>"><?= $item['title'] ?></a>
                                                <ul class="list-inline tasks-list-menu mb-0 ms-3">
                                                    <li class="list-inline-item m-0">
                                                        <form method="POST" action="<?= $_SERVER['REQUEST_URI'] ?>">
                                                            <input type="hidden" name="action_name" value="delete_task">
                                                            <input type="hidden" name="task_id" value="<?= $item['id'] ?>">
                                                            <button type="submit" class="btn btn-link btn-sm btn-delete-record">
                                                                <i class="ri-delete-bin-5-line align-bottom text-muted"></i>
                                                            </button>
                                                        </form>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                        <td class="text-center"><?= renderStatusBadge($item['status']) ?></td>
                                        <td class="text-center"><?= $systemController->convertDate($item['due_date']) ?></td>
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

<div class="modal fade" id="createTaskModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0">
            <div class="modal-header p-3 bg-info-subtle">
                <h5 class="modal-title" id="modal-title">New Task</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <div class="modal-body p-4">
                <form method="POST" action="<?= $_SERVER['REQUEST_URI'] ?>">
                    <?php if (!empty($post_id)) { ?>
                        <input type="hidden" name="project_id" value="<?= $post_id ?>">
                        <input type="hidden" name="action_name" value="new_task">
                    <?php } ?>
                    <div class="mb-3">
                        <label class="form-label" for="task-title-input">Title</label>
                        <input type="text" class="form-control" id="task-title-input" name="task_title"
                            placeholder="Enter title">
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="task_description">Content</label>
                        <textarea name="task_description" class="ckeditor-classic">
                                </textarea>
                    </div>
                    <div class="mb-3">
                        <label for="datepicker-task-due-date-input" class="form-label">Deadline</label>
                        <input type="text" class="form-control" id="datepicker-task-due-date-input"
                            placeholder="Enter due date" data-provider="flatpickr" name="task_due_date"
                            data-date-format="Y-m-d">
                    </div>
                    <div class="mt-4 hstack gap-2 justify-content-center">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success w-sm">Create</button>
                    </div>
                </form>
            </div>
        </div> <!-- end modal-content-->
    </div> <!-- end modal dialog-->
</div>

<?php
$pageContent = ob_get_clean();

ob_start();
echo "
<script src='" . home_url("/assets/libs/@ckeditor/ckeditor5-build-classic/build/ckeditor.js") . "'></script>
";
$additionJs = ob_get_clean();
