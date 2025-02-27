<?php
$pageTitle = "Today Checklist";

require_once 'controllers/DailyChecklistController.php';
$dailyChecklistController = new DailyChecklistController();

$list = $dailyChecklistController->listDailyChecklists();
$complete_count = 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action_name'])) {
        if ($_POST['action_name'] == 'create_daily_checklist' || $_POST['action_name'] == 'add_recommended_checklist') {
            $dailyChecklistController->createDailyChecklist();
        }
        if ($_POST['action_name'] === 'update_status' && isset($_POST['post_id'])) {
            $dailyChecklistController->updateDailyChecklist($_POST['post_id']);
        }
        if ($_POST['action_name'] === 'delete_record' && isset($_POST['post_id'])) {
            $dailyChecklistController->deleteDailyChecklist($_POST['post_id']);
        }
        if ($_POST['action_name'] === 'finish_checklist') {
            $dailyChecklistController->finishDailyChecklist();
        }
    }
}

$recommendedChecklist = [
    [
        'title' => 'Do Workout',
        'content' => 'Engage in physical exercise to stay fit and maintain a healthy lifestyle.',
    ],
    [
        'title' => 'Learning WordPress',
        'content' => 'Practice building themes and plugins, and explore the latest WordPress features.',
    ],
    [
        'title' => 'Learning English',
        'content' => 'Improve grammar, expand vocabulary, and practice speaking and writing skills.',
    ],
    [
        'title' => 'Learning ReactJS',
        'content' => 'Build interactive components, explore hooks, and work on real-world projects.',
    ],
];

ob_start();

include_once DIR . "/components/alert.php";
?>
    <div class="row mt-4">
        <div class="col-4">
            <form method="POST" action="<?= $_SERVER['REQUEST_URI'] ?>">
                <?php csrfInput() ?>
                <input type="hidden" name="action_name" value="create_daily_checklist">
                <div class="card">
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-lg-3 d-flex">
                                <label for="title-input" class="form-label mb-0">Title</label>
                            </div>
                            <div class="col-lg-9">
                                <input type="text" class="form-control" id="title-input" name="title"
                                       placeholder="Enter title" value="" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-3 d-flex">
                                <label for="datepicker-deadline-input" class="form-label mb-0">Due Date</label>
                            </div>
                            <div class="col-lg-9">
                                <input type="text" class="form-control" id="datepicker-deadline-input"
                                       placeholder="Enter due date" data-provider="flatpickr" name="due_date"
                                       data-date-format="Y-m-d" data-minDate="<?= date('Y-m-d'); ?>" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-3 d-flex">
                                <label for="content" class="form-label mb-0">Content</label>
                            </div>
                            <div class="col-lg-9">
                                <textarea name="content" class="form-control" id="content" rows="3"
                                          placeholder="Enter content"></textarea>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-3">
                            </div>
                            <div class="col-lg-9">
                                <div class="text-center">
                                    <button type="submit" class="btn btn-success w-sm">Add to checklist</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            <div class="card mt-3">
                <div class="card-body p-0">
                    <div class="align-items-center p-3 justify-content-between d-flex">
                        <div class="flex-shrink-0">
                            <div class="text-muted">Recommended Checklist (for tomorrow)</div>
                        </div>
                    </div><!-- end card header -->
                    <div data-simplebar style="max-height: 226px;">
                        <ul class="list-group list-group-flush border-dashed px-3">
                            <?php if (count($recommendedChecklist) > 0) {
                                foreach ($recommendedChecklist as $checklist) { ?>
                                    <li class="list-group-item ps-0">
                                        <div class="d-flex align-items-start">
                                            <div class="flex-grow-1">
                                                <label class="form-check-label mb-0 ps-2"><?= $checklist['title'] ?></label>
                                            </div>
                                            <div class="flex-shrink-0 ms-2">
                                                <form method="POST" action="<?= $_SERVER['REQUEST_URI'] ?>">
                                                    <?php csrfInput() ?>
                                                    <input type="hidden" name="action_name"
                                                           value="add_recommended_checklist">
                                                    <input type="hidden" name="title"
                                                           value="<?= $checklist['title'] ?>">
                                                    <input type="hidden" name="due_date"
                                                           value="<?= date('Y-m-d', strtotime('+1 day')); ?>">
                                                    <input type="hidden" name="content"
                                                           value="<?= $checklist['content'] ?>">
                                                    <button type="submit" class="btn btn-sm btn-info">Add Item</button>
                                                </form>
                                            </div>
                                        </div>
                                    </li>
                                <?php }
                            } ?>
                        </ul><!-- end ul -->
                    </div>
                </div><!-- end card body -->
            </div>
        </div>
        <div class="col-8">
            <div class="card card-height-100">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">Daily Checklist</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive table-card">
                        <table class="table table-hover table-nowrap align-middle mb-0">
                            <thead class="table-light">
                            <tr class="text-muted">
                                <th scope="col">No</th>
                                <th scope="col">Title</th>
                                <th scope="col">Content</th>
                                <th scope="col" class="text-center">Status</th>
                                <th scope="col" class="text-center" style="width: 120px">Due Date</th>
                                <th scope="col" class="text-center" style="width: 180px"></th>
                            </tr>
                            </thead>

                            <tbody>
                            <?php
                            if (count($list) > 0) {
                                foreach ($list as $index => $row) {
                                    if ($row['status'] == 'done') {
                                        $complete_count += 1;
                                    }
                                    $is_previous_date = $systemController->checkPreviousDateTime($row['due_date'] . ' 23:59:59');
                                    ?>
                                    <tr>
                                        <td><?= $index < 10 ? '0' . $index + 1 : $index ?></td>
                                        <td><?= $row['title'] ?></td>
                                        <td><?= $row['content'] ?></td>
                                        <td class="text-center"><span
                                                    class="badge bg-<?= $row['status'] == 'done' ? 'success' : 'secondary' ?>-subtle text-<?= $row['status'] == 'done' ? 'success' : 'secondary' ?> text-uppercase"><?= $row['status'] ?></span>
                                        </td>
                                        <td class="text-center"><span>
                                            <span class="text-<?= $is_previous_date ? 'danger' : '' ?> text-capitalize"><?= $systemController->checkDateRelation($row['due_date']) ?></span>
                                        </td>
                                        <td class="text-center d-flex align-items-center justify-content-center">
                                            <?php if (!$is_previous_date) { ?>
                                                <form method="POST" action="<?= $_SERVER['REQUEST_URI'] ?>">
                                                    <input type="hidden" name="action_name" value="update_status">
                                                    <input type="hidden" name="post_id" value="<?= $row['id'] ?>">
                                                    <button class="btn btn-sm btn-<?= $row['status'] !== 'done' ? 'success' : 'secondary' ?>">
                                                        Mark <?= $row['status'] == 'done' ? 'Incomplete' : 'Complete' ?>
                                                    </button>
                                                </form>
                                            <?php } ?>
                                            <form method="POST" action="<?= $_SERVER['REQUEST_URI'] ?>">
                                                <input type="hidden" name="action_name" value="delete_record">
                                                <input type="hidden" name="post_id" value="<?= $row['id'] ?>">
                                                <button type="submit"
                                                        class="btn btn-danger ms-1 btn-sm btn-delete-record">
                                                    <i
                                                            class="ri-delete-bin-5-line align-bottom"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php }
                            }
                            ?>
                            </tbody><!-- end tbody -->
                        </table><!-- end table -->
                    </div>
                    <div class="align-items-center mt-3 row g-3 text-center text-sm-start">
                        <div class="col-sm">
                            <?php if (count($list) > 0) {
                                $remaining = count($list) - $complete_count;
                                if ($remaining > 0) {
                                    ?>
                                    <div class="text-muted"><span class="fw-semibold"><?= $remaining ?></span> of <span
                                                class="fw-semibold"><?= count($list) ?></span> remaining
                                    </div>
                                <?php } else { ?>
                                    <form method="POST" action="<?= $_SERVER['REQUEST_URI'] ?>">
                                        <input type="hidden" name="action_name" value="finish_checklist">
                                        <button type="submit"
                                                data-confirm-text="Finish Checklist and Get Coin"
                                                class="btn btn-soft-warning ms-1 btn-confirm-action d-flex align-items-center justify-content-center">
                                            Finish And Get Coin <i
                                                    class="ri ri-money-dollar-circle-line fs-16 ms-2"></i>
                                        </button>
                                    </form>
                                <?php }
                            } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php
$pageContent = ob_get_clean();
