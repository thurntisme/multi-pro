<?php
$pageTitle = "Report Working Detail";

require_once 'controllers/ReportWorkingController.php';
$reportWorkingController = new ReportWorkingController();

$modify_type = getLastSegmentFromUrl();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['id'])) {
        $post_id = $_GET['id'];
        $postData = $reportWorkingController->viewReport($post_id);
        $tags = !empty($postData['tags']) ? explode(',', $postData['tags']) : [];
    }
}
$today = date('Y-m-d');

$tasks = json_decode($postData['content'], true);
$groupedTasks = [
    "todo" => [],
    "processing" => [],
    "done" => []
];

foreach ($tasks as $task) {
    $status = isset($task['task_status']) ? $task['task_status'] : 'todo';

    if (in_array($status, ["todo", "processing", "done"])) {
        $groupedTasks[$status][] = $task;
    }
}

ob_start();
?>
<div class="row">
    <div class="col-12">
        <?php
        includeFileWithVariables('components/single-button-group.php', array("slug" => "report-working", "post_id" => $postData['id'] ?? '', 'modify_type' => $modify_type));
        ?>
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0"><?= $postData['title'] ?></h4>
            </div>
            <div class="card-body">
                <div class="text-muted">
                    <?php if (!empty($postData['note'])) { ?>
                        <h6 class="mb-3 fw-semibold text-uppercase">Note</h6>
                        <div class="mb-3">
                            <?= $postData['note'] ?>
                        </div>
                    <?php } ?>

                    <h6 class="mb-3 fw-semibold text-uppercase">Content</h6>
                    <div class="mb-3">
                        <?php
                        if (!empty($postData['content'])) {
                        ?>
                            <table class="table table-nowrap">
                                <thead class="table-light ">
                                    <tr>
                                        <th scope="col">Id</th>
                                        <th scope="col">Title</th>
                                        <th scope="col" class="text-center">Status</th>
                                        <th scope="col" class="text-center">Time ETA</th>
                                        <th scope="col" class="text-center">Time Spend</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $content_arr = json_decode($postData['content'], true);
                                    if (count($content_arr) > 0) {
                                        foreach ($content_arr as $key => $task) { ?>
                                            <tr>
                                                <th scope="row"><?= $key + 1 ?></th>
                                                <td><?= $task['task_title'] ?? '' ?></td>
                                                <td class="text-center"><?= renderStatusBadge($task['task_status'] ?? '') ?></td>
                                                <td class="text-center"><?= $task['task_eta'] ?? '' ?></td>
                                                <td class="text-center"><?= $task['task_time_spend'] ?? '' ?></td>
                                            </tr>
                                    <?php }
                                    }
                                    ?>
                                </tbody>
                                <tfoot class="table-light">
                                    <tr>
                                        <td colspan="3">Total</td>
                                        <td>coming</td>
                                        <td>coming</td>
                                    </tr>
                                </tfoot>
                            </table>
                        <?php
                        }
                        ?>
                    </div>

                    <div class="pt-3 border-top border-top-dashed mt-4">
                        <div class="row">
                            <div class="col-lg-3 col-sm-6">
                                <div>
                                    <p class="mb-2 text-uppercase fw-medium">Working Date :</p>
                                    <div class="badge bg-light fs-12 text-black">
                                        <?= $postData['working_date'] ?? '' ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-6">
                                <div>
                                    <p class="mb-2 text-uppercase fw-medium">Created At :</p>
                                    <h5 class="fs-15 mb-0">
                                        <?= $systemController->convertDate($postData['created_at']) ?>
                                    </h5>
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-6">
                                <div>
                                    <p class="mb-2 text-uppercase fw-medium">Updated At :</p>
                                    <h5 class="fs-15 mb-0">
                                        <?= $systemController->convertDate($postData['updated_at']) ?>
                                    </h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end card body -->
        </div>
        <!-- end card -->

        <div class="card">
            <div class="card-body">
                <?php includeFileWithVariables(DIR . "/views/report-working-template.php", array('today' => $today, 'groupedTasks' => $groupedTasks, 'author' => $_ENV['AUTHOR'], 'project' => $postData['project'])) ?>
            </div><!-- end card-body -->
        </div>
        <!-- end card -->
    </div>
</div>

<?php
$pageContent = ob_get_clean();

ob_start();
echo "
<script src='" . home_url("/assets/js/pages/report-working.js") . "'></script>
";
$additionJs = ob_get_clean();
