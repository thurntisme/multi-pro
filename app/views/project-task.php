<?php
$teamMembers = [
  ["id" => 1, "name" => "John Doe", "role" => "Developer", "unitPrice" => 25],
  ["id" => 2, "name" => "Lisa Tran", "role" => "Designer", "unitPrice" => 22],
  ["id" => 3, "name" => "David Nguyen", "role" => "PM", "unitPrice" => 30],
  ["id" => 4, "name" => "Tom Le", "role" => "Tester", "unitPrice" => 18]
];

ob_start();
?>

<!-- Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
  <h1><?= $title ?></h1>
  <div>
    <a href="/app/project/detail" class="btn btn-outline-secondary me-2"><i class="ri-arrow-go-back-line me-1"></i>Back</a>
  </div>
</div>

<div class="row g-2 mb-4">
  <!-- Total Tasks -->
  <div class="col-6 col-md-4">
    <div class="card border-0 shadow-sm h-100">
      <div class="card-body d-flex align-items-center">
        <div class="bg-primary bg-opacity-10 p-2 rounded me-2">
          <i class="ri-list-check-2 line ri-2x text-primary"></i>
        </div>
        <div>
          <h6 class="mb-1 text-muted">Total Tasks</h6>
          <h5 class="fw-bold mb-0">24</h5>
        </div>
      </div>
    </div>
  </div>

  <!-- Completed -->
  <div class="col-6 col-md-4">
    <div class="card border-0 shadow-sm h-100">
      <div class="card-body d-flex align-items-center">
        <div class="bg-success bg-opacity-10 p-2 rounded me-2">
          <i class="ri-check-line ri-2x text-success"></i>
        </div>
        <div>
          <h6 class="mb-1 text-muted">Completed</h6>
          <h5 class="fw-bold text-success mb-0">16</h5>
        </div>
      </div>
    </div>
  </div>

  <!-- In Progress -->
  <div class="col-6 col-md-4">
    <div class="card border-0 shadow-sm h-100">
      <div class="card-body d-flex align-items-center">
        <div class="bg-warning bg-opacity-10 p-2 rounded me-2">
          <i class="ri-timer-line ri-2x text-warning"></i>
        </div>
        <div>
          <h6 class="mb-1 text-muted">In Progress</h6>
          <h5 class="fw-bold text-warning mb-0">6</h5>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Filter + Summary -->
<div class="row mb-4 g-3 align-items-end">
  <!-- Filter Section -->
  <div class="col-lg-8 col-md-6">
    <div class="d-flex gap-2">
      <select class="form-select w-auto">
        <option>All Status</option>
        <option>In Progress</option>
        <option>Completed</option>
        <option>Pending</option>
      </select>
      <select class="form-select w-auto">
        <option>All Members</option>
        <?php foreach ($teamMembers as $member): ?>
          <option value="<?= $member['id'] ?>"><?= $member['name'] ?> (<?= $member['role'] ?>)</option>
        <?php endforeach; ?>
      </select>
    </div>
  </div>

  <!-- Summary Section -->
  <div class="col-lg-4 col-md-6 d-flex justify-content-end">
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addTaskModal">
      <i class="ri-add-line me-1"></i>Add New Task
    </button>
  </div>
</div>

<!-- Task List -->
<div class="card shadow-sm mb-3 task-card p-3">
  <div class="d-flex justify-content-between align-items-start">
    <div>
      <div class="d-flex align-items-center gap-2 mb-1">
        <h6 class="fw-semibold mb-0">Design Homepage UI</h6>
        <span class="badge text-bg-primary badge-status">In Progress</span>
      </div>
      <div class="task-meta mb-2">
        <small><i class="ri-user-line me-1"></i>Assigned: <strong>Lisa (Designer)</strong></small> |
        <small><i class="ri-calendar-line me-1"></i>Due: 2025-10-30</small> |
        <small><i class="ri-time-line me-1"></i>Est: 10h</small>
      </div>
      <p class="mb-1">Create responsive homepage layout in Figma following the client brand guide.</p>
    </div>
    <div class="btn-group">
      <button class="btn btn-sm btn-outline-success"><i class="ri-check-line"></i></button>
      <button class="btn btn-sm btn-outline-primary"><i class="ri-edit-line"></i></button>
      <button class="btn btn-sm btn-outline-danger"><i class="ri-delete-bin-line"></i></button>
    </div>
  </div>
</div>

<div class="card shadow-sm mb-3 task-card p-3 border-left-success">
  <div class="d-flex justify-content-between align-items-start">
    <div>
      <div class="d-flex align-items-center gap-2 mb-1">
        <h6 class="fw-semibold mb-0">Implement Contact Form</h6>
        <span class="badge text-bg-success badge-status">Completed</span>
      </div>
      <div class="task-meta mb-2">
        <small><i class="ri-user-line me-1"></i>Assigned: <strong>Nam (Developer)</strong></small> |
        <small><i class="ri-calendar-line me-1"></i>Due: 2025-10-25</small> |
        <small><i class="ri-time-line me-1"></i>Est: 6h</small>
      </div>
      <p class="mb-1">Integrate contact form using Next.js API routes and connect to client email.</p>
    </div>
    <div class="btn-group">
      <button class="btn btn-sm btn-outline-success"><i class="ri-check-line"></i></button>
      <button class="btn btn-sm btn-outline-primary"><i class="ri-edit-line"></i></button>
      <button class="btn btn-sm btn-outline-danger"><i class="ri-delete-bin-line"></i></button>
    </div>
  </div>
</div>

<!-- Add Task Modal -->
<div class="modal fade" id="addTaskModal" tabindex="-1" aria-labelledby="addTaskModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-md modal-dialog-centered">
    <div class="modal-content border-0 shadow">
      <div class="modal-header bg-light">
        <h5 class="modal-title fw-semibold">
          <i class="ri-add-line me-1"></i>Add New Task
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        <form>
          <div class="row g-3">
            <!-- Task Title -->
            <div class="col-12">
              <label class="form-label fw-medium">Task Title</label>
              <input type="text" class="form-control" placeholder="Enter task title">
            </div>

            <!-- Role -->
            <div class="col-6">
              <label class="form-label fw-medium">Member</label>
              <select class="form-select">
                <?php foreach ($teamMembers as $member): ?>
                  <option value="<?= $member['id'] ?>"><?= $member['name'] ?> (<?= $member['role'] ?>)</option>
                <?php endforeach; ?>
              </select>
            </div>

            <!-- Estimated Time -->
            <div class="col-6">
              <label class="form-label fw-medium">Est. Time (hrs)</label>
              <input type="number" class="form-control" placeholder="e.g. 10">
            </div>

            <!-- Status -->
            <div class="col-12">
              <label class="form-label fw-medium">Status</label>
              <select class="form-select">
                <option>Pending</option>
                <option>In Progress</option>
                <option>Completed</option>
              </select>
            </div>

            <!-- Due Date -->
            <div class="col-12">
              <label class="form-label fw-medium">Due Date</label>
              <input type="date" class="form-control">
            </div>

            <!-- Description -->
            <div class="col-12">
              <label class="form-label fw-medium">Description</label>
              <textarea class="form-control" rows="4" placeholder="Task details..."></textarea>
            </div>
          </div>
        </form>
      </div>

      <div class="modal-footer">
        <button class="btn btn-light border" data-bs-dismiss="modal">Cancel</button>
        <button class="btn btn-primary"><i class="ri-save-line me-1"></i>Save Task</button>
      </div>
    </div>
  </div>
</div>

<?php
$pageContent = ob_get_clean();

include_once LAYOUTS_DIR . 'dashboard.php';
