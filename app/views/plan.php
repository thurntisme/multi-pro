<?php
ob_start();
?>

<!-- Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
  <h1><?= $title ?></h1>
  <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addPlanModal">
    <i class="ri-add-line me-1"></i> Add New
  </button>
</div>

<!-- List Plans -->
<div class="vstack gap-1">

  <!-- Plan card -->
  <div class="card border-0 shadow-sm p-3 d-flex flex-row align-items-center justify-content-between">
    <div class="d-flex align-items-center">
      <div class="rounded-circle bg-primary-subtle text-primary d-flex align-items-center justify-content-center me-3" style="width: 55px; height: 55px;">
        <i class="ri-focus-line fs-4"></i>
      </div>
      <div>
        <h5 class="fw-bold mb-1">Finish React Component Refactor</h5>
        <div class="text-muted small mb-1"><i class="ri-time-line me-1"></i>Tomorrow • 09:00 AM</div>
        <div class="text-secondary small">Clean up old code, optimize hooks, test new reusable UI parts.</div>
      </div>
    </div>
    <div class="d-flex flex-column align-items-end">
      <span class="badge bg-success mb-2">In Progress</span>
      <div class="btn-group btn-group-sm">
        <button class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#reviewModal">
          <i class="ri-chat-check-line"></i> Review
        </button>
        <button class="btn btn-outline-primary"><i class="ri-edit-line"></i></button>
        <button class="btn btn-outline-danger"><i class="ri-delete-bin-line"></i></button>
      </div>
    </div>
  </div>

  <!-- Another plan -->
  <div class="card border-0 shadow-sm p-3 d-flex flex-row align-items-center justify-content-between">
    <div class="d-flex align-items-center">
      <div class="rounded-circle bg-warning-subtle text-warning d-flex align-items-center justify-content-center me-3" style="width: 55px; height: 55px;">
        <i class="ri-lightbulb-line fs-4"></i>
      </div>
      <div>
        <h5 class="fw-bold mb-1">Review Today’s Achievements</h5>
        <div class="text-muted small mb-1"><i class="ri-time-line me-1"></i>Today • 08:00 PM</div>
        <div class="text-secondary small">Summarize what went well, what to improve, and prepare tomorrow’s plan.</div>
      </div>
    </div>
    <div class="d-flex flex-column align-items-end">
      <span class="badge bg-warning text-dark mb-2">Pending</span>
      <div class="btn-group btn-group-sm">
        <button class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#reviewModal">
          <i class="ri-chat-check-line"></i> Review
        </button>
        <button class="btn btn-outline-primary"><i class="ri-edit-line"></i></button>
        <button class="btn btn-outline-danger"><i class="ri-delete-bin-line"></i></button>
      </div>
    </div>
  </div>

</div>
</div>

<!-- Modal: Add New Plan -->
<div class="modal fade" id="addPlanModal" tabindex="-1" aria-labelledby="addPlanModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow">
      <div class="modal-header">
        <h5 class="modal-title fw-bold"><i class="ri-add-line me-1 text-primary"></i>Add New Plan</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form>
          <div class="mb-3">
            <label class="form-label fw-semibold">Title</label>
            <input type="text" class="form-control" placeholder="Enter plan title..." required>
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold">Description</label>
            <textarea class="form-control" rows="3" placeholder="Describe your plan..."></textarea>
          </div>
          <div class="row mb-3">
            <div class="col-md-6">
              <label class="form-label fw-semibold">Date</label>
              <input type="date" class="form-control">
            </div>
            <div class="col-md-6">
              <label class="form-label fw-semibold">Time</label>
              <input type="time" class="form-control">
            </div>
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold">Status</label>
            <select class="form-select">
              <option>Pending</option>
              <option>In Progress</option>
              <option>Completed</option>
            </select>
          </div>
          <div class="text-end">
            <button class="btn btn-success"><i class="ri-save-3-line me-1"></i> Save</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Modal: Review -->
<div class="modal fade" id="reviewModal" tabindex="-1" aria-labelledby="reviewModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow">
      <div class="modal-header">
        <h5 class="modal-title fw-bold" id="reviewModalLabel"><i class="ri-chat-check-line me-1 text-success"></i>Review Plan</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <form>
          <div class="mb-3">
            <label class="form-label fw-semibold">Your Rating</label>
            <div class="d-flex gap-2">
              <i class="ri-star-line fs-4 text-warning"></i>
              <i class="ri-star-line fs-4 text-warning"></i>
              <i class="ri-star-line fs-4 text-warning"></i>
              <i class="ri-star-line fs-4 text-warning"></i>
              <i class="ri-star-line fs-4 text-warning"></i>
            </div>
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold">Your Thoughts</label>
            <textarea class="form-control" rows="3" placeholder="What went well or needs improvement?"></textarea>
          </div>
          <div class="text-end">
            <button type="submit" class="btn btn-success">
              <i class="ri-send-plane-line me-1"></i>Submit Review
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<?php
$pageContent = ob_get_clean();

include_once LAYOUTS_DIR . 'dashboard.php';
