<?php
global $priorities, $status;
require_once 'controllers/TodoController.php';

$pageTitle = "Todos";

$todoController = new TodoController();
$list = $todoController->listTodos();

ob_start();
?>

<?php
include_once DIR . '/components/alert.php';
?>

    <div class="card" id="tasksList">
        <div class="card-header border-0">
            <div class="d-flex align-items-center">
                <h5 class="card-title mb-0 flex-grow-1">All Todos</h5>
                <div class="flex-shrink-0">
                    <div class="d-flex flex-wrap gap-2">
                        <a class="btn btn-soft-success add-btn" href="<?= home_url('todo/new') ?>"><i
                                    class="ri-add-line align-bottom me-1"></i> Create Todo</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body border border-dashed border-end-0 border-start-0">
            <form method="get" action="<?= home_url('todo') ?>">
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
                        <input type="text" class="form-control bg-light border-light" name="due_date"
                               data-provider="flatpickr" data-date-format="Y-m-d" data-range-date="true"
                               placeholder="Select date range" value="<?= $_GET['due_date'] ?? '' ?>">
                    </div>
                    <div class="col-xxl-2 col-sm-4">
                        <div class="input-light">
                            <select class="form-control" data-choices data-choices-search-false
                                    name="priority">
                                <?php
                                echo '<option value="" ' . (!empty($_GET['priority']) ? 'selected' : "") . '>Select Priority</option>';
                                foreach ($priorities as $value => $label) {
                                    $selected = (!empty($_GET['priority']) && $value === $_GET['priority']) ? 'selected' : '';
                                    echo "<option value=\"$value\" $selected>$label</option>";
                                }
                                ?>
                            </select>
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
                        <a href="<?= home_url("todo") ?>" class="btn btn-danger ms-1"><i
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
                        <th class="text-center">Tags</th>
                        <th class="text-center">Due Date</th>
                        <th class="text-center">Priority</th>
                        <th class="text-center">Status</th>
                        <th class="text-end">Created At</th>
                        <th class="text-end">Updated At</th>
                    </tr>
                    </thead>
                    <tbody class="list form-check-all">
                    <?php if (count($list['list']) > 0) {
                        foreach ($list['list'] as $item) { ?>
                            <tr>
                                <td>
                                    <div class="d-flex">
                                        <a class="text-black"
                                           href="<?= home_url('todo/detail?id=' . $item['id']) ?>"><?= truncateString($item['title'], 50) ?></a>
                                        <ul class="list-inline tasks-list-menu mb-0 ms-1">
                                            <li class="list-inline-item"><a class="edit-item-btn"
                                                                            href="<?= home_url('todo/edit?id=' . $item['id']) ?>"><i
                                                            class="ri-pencil-fill align-bottom me-2 text-muted"></i></a>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                                <td class="text-center"><?= $item['tags'] ?></td>
                                <td class="text-center"><?= !empty($item['due_date']) && $item['due_date'] != '0000-00-00' ? $item['due_date'] : '' ?></td>
                                <td class="text-center"><?= $item['priority'] ?></td>
                                <td class="text-center"><?= $item['status'] ?></td>
                                <td class="text-end"><?= $commonController->convertDateTime($item['created_at']) ?></td>
                                <td class="text-end"><?= $commonController->convertDateTime($item['updated_at']) ?></td>
                            </tr>
                        <?php }
                    } ?>
                    </tbody>
                </table>
                <!--end table-->
                <div class="noresult" style="display: none">
                    <div class="text-center">
                        <lord-icon src="https://cdn.lordicon.com/msoeawqm.json" trigger="loop"
                                   colors="primary:#121331,secondary:#08a88a"
                                   style="width:75px;height:75px"></lord-icon>
                        <h5 class="mt-2">Sorry! No Result Found</h5>
                        <p class="text-muted mb-0">Weve searched more than 200k+ todos We did not find any
                            todos for you search.</p>
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

include 'layout.php';