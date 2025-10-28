<?php
ob_start();
?>

<!-- Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
  <h1><?= $title ?></h1>
</div>

<!-- Journal Form -->
<div class="card border-0 shadow-sm mb-4">
  <div class="card-body">
    <form id="journalForm">
      <div class="row g-3 align-items-center">

        <!-- Title -->
        <div class="col-md-6">
          <label class="form-label fw-semibold">Title</label>
          <input type="text" class="form-control" placeholder="Enter journal title..." required>
        </div>

        <!-- Emoji -->
        <div class="col-md-3">
          <label class="form-label fw-semibold">Mood</label>
          <select class="form-select">
            <option>😊 Happy</option>
            <option>😐 Neutral</option>
            <option>😔 Sad</option>
            <option>😤 Stressed</option>
            <option>🤩 Excited</option>
          </select>
        </div>

        <!-- Rating -->
        <div class="col-md-3">
          <label class="form-label fw-semibold">Rating</label>
          <div class="d-flex align-items-center gap-2">
            <select class="form-select" style="max-width: 120px;">
              <option>1 ⭐</option>
              <option>2 ⭐⭐</option>
              <option>3 ⭐⭐⭐</option>
              <option>4 ⭐⭐⭐⭐</option>
              <option selected>5 ⭐⭐⭐⭐⭐</option>
            </select>
          </div>
        </div>

        <!-- Content -->
        <div class="col-12">
          <label class="form-label fw-semibold">Content</label>
          <textarea class="form-control" rows="4" placeholder="Write your thoughts..." required></textarea>
        </div>

        <!-- Submit -->
        <div class="col-12 text-end">
          <button type="submit" class="btn btn-success">
            <i class="ri-save-3-line me-1"></i> Save Journal
          </button>
        </div>
      </div>
    </form>
  </div>
</div>

<!-- Journal List (Row layout) -->
<div class="list-group shadow-sm">

  <!-- Journal Item -->
  <div class="list-group-item list-group-item-action d-flex justify-content-between align-items-start">
    <div>
      <h5 class="fw-bold mb-1">Morning Reflections <span class="small text-muted">😊</span></h5>
      <p class="text-muted small mb-1"><i class="ri-calendar-line me-1"></i> Oct 13, 2025</p>
      <p class="mb-1 text-secondary">Started the day feeling grateful and focused. Learned something new about mindfulness...</p>
      <span class="text-warning">⭐⭐⭐⭐⭐</span>
    </div>
    <div class="d-flex flex-column align-items-end">
      <div class="btn-group btn-group-sm">
        <button class="btn btn-outline-primary"><i class="ri-edit-line"></i></button>
        <button class="btn btn-outline-danger"><i class="ri-delete-bin-line"></i></button>
      </div>
    </div>
  </div>

  <!-- Journal Item -->
  <div class="list-group-item list-group-item-action d-flex justify-content-between align-items-start">
    <div>
      <h5 class="fw-bold mb-1">Evening Thoughts <span class="small text-muted">😐</span></h5>
      <p class="text-muted small mb-1"><i class="ri-calendar-line me-1"></i> Oct 12, 2025</p>
      <p class="mb-1 text-secondary">Felt a bit tired but completed all goals for today. Planning for tomorrow’s sprint...</p>
      <span class="text-warning">⭐⭐⭐</span>
    </div>
    <div class="d-flex flex-column align-items-end">
      <div class="btn-group btn-group-sm">
        <button class="btn btn-outline-primary"><i class="ri-edit-line"></i></button>
        <button class="btn btn-outline-danger"><i class="ri-delete-bin-line"></i></button>
      </div>
    </div>
  </div>

</div>

<?php
$pageContent = ob_get_clean();

include_once LAYOUTS_DIR . 'dashboard.php';
