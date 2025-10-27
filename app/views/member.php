<?php
ob_start();
?>

<!-- Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
  <h1><?= $title ?></h1>
  <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addMemberModal">
    <i class="ri-user-add-line me-1"></i>Add Member
  </button>
</div>

<!-- Filter Bar -->
<div class="card shadow-sm mb-4">
  <div class="card-body">
    <div class="row g-3 align-items-center">
      <div class="col-md-4">
        <input type="text" class="form-control" placeholder="Search by name or role...">
      </div>
      <div class="col-md-3">
        <select class="form-select">
          <option value="">All Roles</option>
          <option>Developer</option>
          <option>Designer</option>
          <option>PM</option>
          <option>Tester</option>
        </select>
      </div>
      <div class="col-md-2">
        <button class="btn btn-outline-secondary w-100"><i class="ri-filter-2-line me-1"></i>Filter</button>
      </div>
    </div>
  </div>
</div>

<!-- Member List -->
<div class="card shadow-sm">
  <div class="card-header bg-white fw-semibold">
    <i class="ri-list-unordered me-1"></i>Member List
  </div>
  <div class="card-body p-0">
    <table class="table table-hover align-middle mb-0">
      <thead class="table-light">
        <tr>
          <th>Name</th>
          <th>Role</th>
          <th>Unit Price / Hour</th>
          <th class="text-end" style="width: 100px;">Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td><strong>John Doe</strong></td>
          <td><span class="badge bg-primary">Developer</span></td>
          <td>$25</td>
          <td class="text-end">
            <button class="btn btn-sm btn-outline-secondary me-1"><i class="ri-edit-line"></i></button>
            <button class="btn btn-sm btn-outline-danger"><i class="ri-delete-bin-line"></i></button>
          </td>
        </tr>
        <tr>
          <td><strong>Lisa Tran</strong></td>
          <td><span class="badge bg-success">Designer</span></td>
          <td>$22</td>
          <td class="text-end">
            <button class="btn btn-sm btn-outline-secondary me-1"><i class="ri-edit-line"></i></button>
            <button class="btn btn-sm btn-outline-danger"><i class="ri-delete-bin-line"></i></button>
          </td>
        </tr>
        <tr>
          <td><strong>David Nguyen</strong></td>
          <td><span class="badge bg-warning text-dark">PM</span></td>
          <td>$30</td>
          <td class="text-end">
            <button class="btn btn-sm btn-outline-secondary me-1"><i class="ri-edit-line"></i></button>
            <button class="btn btn-sm btn-outline-danger"><i class="ri-delete-bin-line"></i></button>
          </td>
        </tr>
        <tr>
          <td><strong>Tom Le</strong></td>
          <td><span class="badge bg-danger">Tester</span></td>
          <td>$18</td>
          <td class="text-end">
            <button class="btn btn-sm btn-outline-secondary me-1"><i class="ri-edit-line"></i></button>
            <button class="btn btn-sm btn-outline-danger"><i class="ri-delete-bin-line"></i></button>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</div>

<!-- Add/Edit Member Modal -->
<div class="modal fade" id="addMemberModal" tabindex="-1" aria-labelledby="addMemberModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><i class="ri-user-add-line me-1"></i>Add New Member</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form>
          <div class="mb-3">
            <label class="form-label">Full Name</label>
            <input type="text" class="form-control" placeholder="Enter full name">
          </div>
          <div class="mb-3">
            <label class="form-label">Role</label>
            <select class="form-select">
              <option>Developer</option>
              <option>Designer</option>
              <option>PM</option>
              <option>Tester</option>
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label">Unit Price / Hour ($)</label>
            <input type="number" class="form-control" placeholder="e.g. 25">
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button class="btn btn-primary"><i class="ri-save-line me-1"></i>Save Member</button>
      </div>
    </div>
  </div>
</div>

<?php
$pageContent = ob_get_clean();

include_once LAYOUTS_DIR . 'dashboard.php';
