<?php
require_once "controllers/TaskController.php";
require_once "controllers/CommonController.php";

$taskController = new TaskController();
$taskList = $taskController->getLatestTasks();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (isset($_POST['action_name'])) {
    if ($_POST['action_name'] === 'complete_task' && isset($_POST['task_id'])) {
      $taskController->completeTask($_POST['task_id']);
    }
    if ($_POST['action_name'] === 'add_new_task') {
      $taskController->createTask();
    }
  }
}
;

$commonController = new CommonController();

?>

<style>
  #task-list .list-group-item {
    cursor: context-menu;
  }

  #task-list .list-group-item h5 {
    font-weight: 600;
  }
</style>

<div class="card overflow-hidden" id="task-list">
  <div class="card-header">
    <div class="d-flex justify-content-between align-items-center">
      Task<button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#addTaskModal">
        Add new
      </button>
    </div>
  </div>
  <div class="card-body p-0">
    <div class="list-group list-group-flush">
      <?php
      if (count($taskList) > 0) {
        foreach ($taskList as $task) {
          ?>
          <div class="list-group-item list-group-item-action pt-3">
            <div class="d-flex">
              <div class="flex-grow-1">
                <h5 class="mb-1 text-gray-900"><?= $task['title'] ?? '' ?></h5>
                <p class="mb-1"><?= $task['description'] ?? '' ?></p>
                <div class="row">
                  <small class="col-6"><i class="fas fa-pen text-gray-500 mr-1"></i>
                    <?= isset($task['updated_at']) ? timeAgo($systemController->convertDateTime($task['updated_at'])) : '' ?></small>
                  <?php if (!empty($task['due_date'])) { ?>
                    <small class="ml-4"><i class="fas fa-calendar text-gray-500 mr-1"></i>
                      <?= date("d-m-Y", strtotime($task['due_date'])) ?></small>
                  <?php } ?>
                </div>
              </div>
              <div class="p-0 pr-1">
                <form method="POST" action="<?= App\Helpers\NetworkHelper::home_url("/") ?>">
                  <input type="hidden" name="task_id" value="<?= $task['id'] ?>">
                  <input type="hidden" name="action_name" value="complete_task">
                  <button type="submit" class="btn btn-sm btn-outline-success"><i class="fas fa-check"></i></button>
                </form>
              </div>
            </div>
          </div>
        <?php }
      } else { ?>
        <p class="m-0 px-4 py-3">No tasks</p>
      <?php } ?>
    </div>
  </div>
</div>

<div class="modal fade" id="addTaskModal" tabindex="-1" aria-labelledby="addTaskModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addTaskModalLabel">Add a new task</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form method="POST" action="<?= App\Helpers\NetworkHelper::home_url("/") ?>">
          <input type="hidden" name="action_name" value="add_new_task">
          <div class="form-group">
            <label for="title">Title:</label>
            <input type="text" class="form-control" id="title" name="title">
          </div>
          <div class="form-group">
            <label for="due_date">Due Date:</label>
            <input type="date" class="form-control" id="" name="due_date" min="<?php echo date('Y-m-d'); ?>">
          </div>
          <div class="form-group">
            <label for="name">Description:</label>
            <textarea name="description" class="form-control"></textarea>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary" id="btn-save">Save</button>
      </div>
    </div>
  </div>
</div>