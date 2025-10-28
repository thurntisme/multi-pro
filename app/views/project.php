<?php
ob_start();
?>

<!-- Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><?= $title ?> Page</h1>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addProjectModal">
        <i class="ri-add-line me-1"></i> Add New
    </button>
</div>

<!-- Filter/Search Bar -->
<div class="row mb-4">
    <div class="col-md-4">
        <input type="text" class="form-control" placeholder="🔍 Search projects...">
    </div>
    <div class="col-md-3">
        <select class="form-select">
            <option value="">All Status</option>
            <option>Ongoing</option>
            <option>Completed</option>
            <option>Pending</option>
        </select>
    </div>
</div>

<!-- Project Grid -->
<div class="row g-4" id="projectGrid">
    <!-- Sample Card -->
    <div class="col-md-4 col-lg-3">
        <div class="card project-card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h5 class="card-title mb-0">Website Redesign</h5>
                    <span class="project-status status-ongoing">Ongoing</span>
                </div>
                <p class="client-name mb-1"><i class="ri-user-3-line me-1"></i>Client: <strong>Acme Corp</strong></p>
                <p class="card-text text-muted small mb-3">
                    Revamping the corporate website for better UX.
                </p>
                <div class="d-flex justify-content-between align-items-center">
                    <div class="text-muted small">
                        <i class="bi bi-calendar-event me-1"></i>Due: 2025-11-05
                    </div>
                    <div class="dropdown">
                        <button class="btn btn-sm btn-light" data-bs-toggle="dropdown">
                            <i class="ri-more-line"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="/app/project/detail">View</a></li>
                            <li><a class="dropdown-item" href="/app/project/edit">Edit</a></li>
                            <li><a class="dropdown-item text-danger" href="#">Delete</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4 col-lg-3">
        <div class="card project-card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h5 class="card-title mb-0">Mobile App</h5>
                    <span class="project-status status-pending">Pending</span>
                </div>
                <p class="client-name mb-1"><i class="bi bi-person-circle me-1"></i>Client: <strong>XYZ Studio</strong></p>
                <p class="card-text text-muted small mb-3">
                    Cross-platform app for task management.
                </p>
                <div class="d-flex justify-content-between align-items-center">
                    <div class="text-muted small">
                        <i class="bi bi-calendar-event me-1"></i>Due: 2025-12-10
                    </div>
                    <div class="dropdown">
                        <button class="btn btn-sm btn-light" data-bs-toggle="dropdown">
                            <i class="bi bi-three-dots"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="#">View</a></li>
                            <li><a class="dropdown-item" href="#">Edit</a></li>
                            <li><a class="dropdown-item text-danger" href="#">Delete</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<!-- Modal Add Project -->
<div class="modal fade" id="addProjectModal" tabindex="-1" aria-labelledby="addProjectModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="addProjectForm">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Project</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Project Name</label>
                        <input type="text" id="projectName" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Client Name</label>
                        <input type="text" id="clientName" class="form-control" placeholder="e.g. Acme Corp" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea id="projectDesc" class="form-control" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select id="projectStatus" class="form-select">
                            <option>Ongoing</option>
                            <option>Completed</option>
                            <option>Pending</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Due Date</label>
                        <input type="date" id="projectDueDate" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
$pageContent = ob_get_clean();

include_once LAYOUTS_DIR . 'dashboard.php';
