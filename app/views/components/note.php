<?php
require "controllers/NoteController.php";

$noteController = new NoteController();
$noteList = $noteController->getLatestTasks();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (isset($_POST['action_name'])) {
    if ($_POST['action_name'] === 'delete_note' && isset($_POST['note_id'])) {
      $noteController->deleteNote($_POST['note_id']);
    }
    if ($_POST['action_name'] === 'add_new_note') {
      $noteController->createNote();
    }
  }
}
;

?>

<style>
  #note-list .list-group-item {
    cursor: context-menu;
  }

  #note-list .list-group-item h5 {
    font-weight: 600;
  }
</style>

<div class="card" id="note-list">
  <div class="card-header">
    <div class="d-flex justify-content-between align-items-center">
      Note<button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#addNoteModal">
        Add new
      </button>
    </div>
  </div>
  <div class="card-body p-0">
    <div class="list-group list-group-flush">
      <?php
      if (count($noteList) > 0) {
        foreach ($noteList as $note) {
          ?>
          <div class="list-group-item list-group-item-action">
            <div class="d-flex">
              <div class="flex-grow-1">
                <h5 class="card-title mb-0"><?= $note['title'] ?? '' ?></h5>
                <small>Updated: <?= isset($note['updated_at']) ? timeAgo($note['updated_at']) : '' ?></small>
                <p class="card-text mt-2"><?= $note['content'] ?? '' ?></p>
              </div>
              <div>
                <button type="button" class="btn btn-sm"><i class="fas fa-pen"></i></button>
                <form method="POST" action="<?= App\Helpers\NetworkHelper::home_url("/") ?>">
                  <input type="hidden" name="note_id" value="<?= $note['id'] ?>">
                  <input type="hidden" name="action_name" value="delete_note">
                  <button type="submit" class="btn btn-sm"><i class="fas fa-trash text-danger"></i></button>
                </form>
              </div>
            </div>
          </div>
        <?php }
      } else { ?>
        <p class="m-0 px-4 py-3">No notes</p>
      <?php } ?>
    </div>
  </div>
</div>

<div class="modal fade" id="addNoteModal" tabindex="-1" aria-labelledby="addNoteModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addNoteModalLabel">Add a new note</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form method="POST" action="<?= App\Helpers\NetworkHelper::home_url("/") ?>">
          <input type="hidden" name="action_name" value="add_new_note">
          <div class="form-group">
            <label for="title">Title:</label>
            <input type="text" class="form-control" id="title" name="title">
          </div>
          <div class="form-group">
            <label for="name">Content:</label>
            <textarea name="content" id="content" class="form-control"></textarea>
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