<?php

$teamMembers = [
  ["id" => 1, "name" => "John Doe", "role" => "Developer", "unitPrice" => 25],
  ["id" => 2, "name" => "Lisa Tran", "role" => "Designer", "unitPrice" => 22],
  ["id" => 3, "name" => "David Nguyen", "role" => "PM", "unitPrice" => 30],
  ["id" => 4, "name" => "Tom Le", "role" => "Tester", "unitPrice" => 18]
];
ob_start();
?>
<style>
  @media print {
    body * {
      visibility: hidden;
    }

    #estimatePreviewModal .modal-content,
    #estimatePreviewModal .modal-content * {
      visibility: visible;
    }

    #estimatePreviewModal {
      position: absolute;
      left: 0;
      top: 0;
      width: 100%;
    }
  }
</style>
<?php
$additionCss = ob_get_clean();

ob_start();
?>

<!-- Header -->
<div class="d-flex align-items-center justify-content-between mb-4">
  <h1><?= $title ?></h1>
  <div>
    <a href="/app/project/detail" class="btn btn-outline-secondary me-2"><i class="ri-arrow-go-back-line me-1"></i>Back</a>
    <button class="btn btn-outline-primary me-2" data-bs-toggle="modal" data-bs-target="#estimatePreviewModal">
      <i class="ri-eye-line me-1"></i>Preview
    </button>
    <button class="btn btn-primary"><i class="ri-save-3-line me-1"></i>Save Estimate</button>
  </div>
</div>

<div class="row g-4">
  <!-- Left: Task List -->
  <div class="col-lg-8">
    <div class="card shadow-sm">
      <div class="card-header bg-white fw-semibold d-flex justify-content-between align-items-center">
        <span><i class="ri-list-check me-1"></i>Estimate Task List</span>
        <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#addTaskModal">
          <i class="ri-add-line me-1"></i>Add Task
        </button>
      </div>

      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-bordered align-middle mb-0">
            <thead class="table-light">
              <tr>
                <th>Role</th>
                <th>Task Title</th>
                <th>Time (hrs)</th>
                <th>Description</th>
                <th style="width: 80px;">Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td><span class="badge bg-primary">Developer</span></td>
                <td>Frontend Implementation</td>
                <td>24</td>
                <td>Build responsive UI with Next.js and TailwindCSS.</td>
                <td class="text-center">
                  <button class="btn btn-sm btn-outline-secondary me-1"><i class="ri-edit-line"></i></button>
                  <button class="btn btn-sm btn-outline-danger"><i class="ri-delete-bin-line"></i></button>
                </td>
              </tr>
              <tr>
                <td><span class="badge bg-success">Designer</span></td>
                <td>UI/UX Mockups</td>
                <td>10</td>
                <td>Create Figma wireframes and visual design.</td>
                <td class="text-center">
                  <button class="btn btn-sm btn-outline-secondary me-1"><i class="ri-edit-line"></i></button>
                  <button class="btn btn-sm btn-outline-danger"><i class="ri-delete-bin-line"></i></button>
                </td>
              </tr>
              <tr>
                <td><span class="badge bg-warning text-dark">PM</span></td>
                <td>Project Planning</td>
                <td>6</td>
                <td>Prepare roadmap, milestones, and client sync meetings.</td>
                <td class="text-center">
                  <button class="btn btn-sm btn-outline-secondary me-1"><i class="ri-edit-line"></i></button>
                  <button class="btn btn-sm btn-outline-danger"><i class="ri-delete-bin-line"></i></button>
                </td>
              </tr>
              <tr>
                <td><span class="badge bg-info text-dark">Tester</span></td>
                <td>QA Review</td>
                <td>8</td>
                <td>Manual and automated testing, bug reports.</td>
                <td class="text-center">
                  <button class="btn btn-sm btn-outline-secondary me-1"><i class="ri-edit-line"></i></button>
                  <button class="btn btn-sm btn-outline-danger"><i class="ri-delete-bin-line"></i></button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <!-- Right: Summary Box -->
  <div class="col-lg-4">
    <div class="card shadow-sm mb-4">
      <div class="card-header bg-white fw-semibold">
        <i class="ri-calculator-line me-1"></i>Summary by Role
      </div>
      <div class="card-body">
        <div class="mb-2 d-flex justify-content-between">
          <span><b>Developer</b> (24h × $25/hr)</span>
          <span>$600</span>
        </div>
        <div class="mb-2 d-flex justify-content-between">
          <span><b>Designer</b> (10h × $20/hr)</span>
          <span>$200</span>
        </div>
        <div class="mb-2 d-flex justify-content-between">
          <span><b>PM</b> (6h × $30/hr)</span>
          <span>$180</span>
        </div>
        <div class="mb-2 d-flex justify-content-between">
          <span><b>Tester</b> (8h × $18/hr)</span>
          <span>$144</span>
        </div>
        <hr>
        <div class="d-flex justify-content-between fs-5">
          <span class="fw-bold">Total Estimate:</span>
          <span class="fw-bold text-success">$1,124</span>
        </div>
      </div>
    </div>

    <div class="card shadow-sm">
      <div class="card-header bg-white fw-semibold">
        <i class="ri-sticky-note-line me-1"></i>Notes
      </div>
      <div class="card-body">
        <textarea class="form-control" rows="4" placeholder="Add quick notes about estimate..."></textarea>
      </div>
    </div>
  </div>
</div>

<!-- Add Task Modal -->
<div class="modal fade" id="addTaskModal" tabindex="-1" aria-labelledby="addTaskModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addTaskModalLabel"><i class="ri-add-line me-1"></i>Add New Task</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="taskForm">
          <div class="row g-3">
            <div class="col-12">
              <label class="form-label">Task Title</label>
              <input type="text" id="taskTitle" class="form-control" placeholder="Enter task title">
            </div>
            <div class="col-6">
              <label class="form-label">Assign To (Member)</label>
              <select id="memberSelect" class="form-select">
                <?php foreach ($teamMembers as $member): ?>
                  <option value="<?= $member['id'] ?>"><?= $member['name'] ?> (<?= $member['role'] ?>)</option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="col-6">
              <label class="form-label">Time (hrs)</label>
              <input type="number" id="taskTime" class="form-control" placeholder="e.g. 10">
            </div>
            <div class="col-12">
              <label class="form-label">Description</label>
              <textarea id="taskDesc" class="form-control" rows="2" placeholder="Brief description..."></textarea>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
          <i class="ri-close-line me-1"></i>Cancel</button>
        <button type="button" id="saveTaskBtn" class="btn btn-primary">
          <i class="ri-save-line me-1"></i>Save Task
        </button>
      </div>
    </div>
  </div>
</div>

<!-- 🔍 Preview Modal -->
<div class="modal fade" id="estimatePreviewModal" tabindex="-1" aria-labelledby="estimatePreviewModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title fw-bold"><i class="ri-eye-line me-1"></i>Estimate Preview</h5>
        <div>
          <button class="btn btn-outline-secondary btn-sm me-2" onclick="window.print()">
            <i class="ri-printer-line me-1"></i>Print
          </button>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
      </div>
      <div class="modal-body">
        <div id="estimatePreviewContent">
          <h4 class="fw-bold mb-3">Project: NextJS + WordPress Headless Website</h4>
          <p class="text-muted mb-4">Generated on: <?= date('F d, Y') ?></p>

          <table class="table table-bordered align-middle">
            <thead class="table-light">
              <tr>
                <th>Role</th>
                <th>Task</th>
                <th>Time (hrs)</th>
                <th>Unit Price</th>
                <th>Total</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>Developer</td>
                <td>Frontend Implementation</td>
                <td>24</td>
                <td>$25</td>
                <td>$600</td>
              </tr>
              <tr>
                <td>Designer</td>
                <td>UI/UX Mockup</td>
                <td>10</td>
                <td>$22</td>
                <td>$220</td>
              </tr>
              <tr>
                <td>PM</td>
                <td>Project Planning & Review</td>
                <td>8</td>
                <td>$30</td>
                <td>$240</td>
              </tr>
              <tr>
                <td>Tester</td>
                <td>Quality Assurance</td>
                <td>6</td>
                <td>$18</td>
                <td>$108</td>
              </tr>
            </tbody>
          </table>

          <div class="text-end mt-3">
            <h5 class="fw-bold">Total Estimate: <span class="text-success">$1,168</span></h5>
          </div>

          <hr class="my-4">
          <p class="small text-muted">
            * This is a rough estimate. Actual effort and cost may vary depending on project scope adjustments.
          </p>
        </div>
      </div>
    </div>
  </div>
</div>

<?php
$pageContent = ob_get_clean();

include_once LAYOUTS_DIR . 'dashboard.php';
