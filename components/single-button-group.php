<div class="mb-3 d-flex justify-content-between align-items-center">
  <a href="<?= home_url('app/' . $slug) ?>" class="btn btn-soft-primary btn-label waves-effect waves-light me-auto"><i
      class="ri-arrow-left-line label-icon align-middle fs-16 me-2"></i>Back to List</a>
  <?php if ($modify_type !== 'new') { ?>
    <?php if ($modify_type !== 'detail') { ?>
      <a href="<?= home_url('app/' . $slug . '/detail?id=' . $post_id) ?>"
        class="btn btn-secondary w-sm me-1"><i class="ri-eye-fill align-bottom me-1"></i> View</a>
    <?php } else { ?>
      <a href="<?= home_url('app/' . $slug . '/edit?id=' . $post_id) ?>"
        class="btn btn-info w-sm me-1"><i class="ri-pencil-fill align-bottom me-1"></i> Edit</a>
    <?php } ?>
    <form method="POST" action="<?= $_SERVER['REQUEST_URI'] ?>">
      <input type="hidden" name="action_name" value="delete_record">
      <input type="hidden" name="post_id" value="<?= $post_id ?>">
      <button type="submit" class="btn btn-danger w-sm btn-delete-record"><i
          class="ri-delete-bin-5-fill align-bottom me-1"></i> Delete
      </button>
    </form>
  <?php } ?>
</div>

<?php
include_once DIR . '/components/alert.php';
?>