<?php
ob_start();
?>

<!-- Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><?= $title ?></h1>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addTaskModal">
        <i class="bi bi-plus-lg me-1"></i> Add New Task
    </button>
</div>

<!-- Summary Boxes -->
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="card summary-card text-center shadow-sm border-0">
            <div class="card-body">
                <h6 class="text-muted mb-1">Total Tasks</h6>
                <h3 class="fw-bold text-primary">24</h3>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card summary-card text-center shadow-sm border-0">
            <div class="card-body">
                <h6 class="text-muted mb-1">Pending</h6>
                <h3 class="fw-bold text-warning">8</h3>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card summary-card text-center shadow-sm border-0">
            <div class="card-body">
                <h6 class="text-muted mb-1">In Progress</h6>
                <h3 class="fw-bold text-success">10</h3>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card summary-card text-center shadow-sm border-0">
            <div class="card-body">
                <h6 class="text-muted mb-1">Completed</h6>
                <h3 class="fw-bold text-secondary">6</h3>
            </div>
        </div>
    </div>
</div>

<!-- Task List (Row-based layout) -->
<div class="list-group shadow-sm">

    <!-- Task item -->
    <div class="list-group-item d-flex justify-content-between align-items-center">
        <div class="flex-grow-1">
            <h6 class="mb-1 fw-semibold">Design Landing Page</h6>
            <small class="text-muted">Create wireframe and implement responsive layout</small>
        </div>
        <div class="text-end me-3">
            <span class="badge bg-success mb-1">In Progress</span><br />
            <small class="text-secondary">Due: 27 Oct 2025</small>
        </div>
        <div class="btn-group btn-group-sm">
            <button class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#viewTaskModal">
                <i class="ri-eye-line"></i>
            </button>
            <button class="btn btn-outline-danger">
                <i class="ri-delete-bin-line"></i>
            </button>
        </div>
    </div>

    <div class="list-group-item d-flex justify-content-between align-items-center">
        <div class="flex-grow-1">
            <h6 class="mb-1 fw-semibold">Fix Stripe Webhook</h6>
            <small class="text-muted">Debug checkout.session.completed handler</small>
        </div>
        <div class="text-end me-3">
            <span class="badge bg-warning text-dark mb-1">Pending</span><br />
            <small class="text-secondary">Due: 28 Oct 2025</small>
        </div>
        <div class="btn-group btn-group-sm">
            <button class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#viewTaskModal">
                <i class="ri-eye-line"></i>
            </button>
            <button class="btn btn-outline-danger">
                <i class="ri-delete-bin-line"></i>
            </button>
        </div>
    </div>

    <div class="list-group-item d-flex justify-content-between align-items-center">
        <div class="flex-grow-1">
            <h6 class="mb-1 fw-semibold">Deploy React App</h6>
            <small class="text-muted">Set up CI/CD and deploy to Vercel</small>
        </div>
        <div class="text-end me-3">
            <span class="badge bg-secondary mb-1">Completed</span><br />
            <small class="text-secondary">Due: 20 Oct 2025</small>
        </div>
        <div class="btn-group btn-group-sm">
            <button class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#viewTaskModal">
                <i class="ri-eye-line"></i>
            </button>
            <button class="btn btn-outline-danger">
                <i class="ri-delete-bin-line"></i>
            </button>
        </div>
    </div>
</div>

<!-- Modal: Add New Task -->
<div class="modal fade" id="addTaskModal" tabindex="-1" aria-labelledby="addTaskModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="addTaskModalLabel">Add New Task</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="taskName" class="form-label">Task Name</label>
                        <input type="text" class="form-control" id="taskName" placeholder="Enter task name" required>
                    </div>
                    <div class="mb-3">
                        <label for="taskDescription" class="form-label">Description</label>
                        <textarea class="form-control" id="taskDescription" rows="3" placeholder="Enter description"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="taskDueDate" class="form-label">Due Date</label>
                        <input type="date" class="form-control" id="taskDueDate">
                    </div>
                    <div class="mb-3">
                        <label for="taskStatus" class="form-label">Status</label>
                        <select id="taskStatus" class="form-select">
                            <option value="pending" selected>Pending</option>
                            <option value="inprogress">In Progress</option>
                            <option value="completed">Completed</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Task</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal: View Task -->
<div class="modal fade" id="viewTaskModal" tabindex="-1" aria-labelledby="viewTaskModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="viewTaskModalLabel">Task Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h6 class="fw-semibold">Task Name:</h6>
                <p>Design Landing Page</p>

                <h6 class="fw-semibold">Description:</h6>
                <p>Create wireframe and implement responsive layout</p>

                <h6 class="fw-semibold">Status:</h6>
                <span class="badge bg-success">In Progress</span>

                <h6 class="fw-semibold mt-3">Due Date:</h6>
                <p>27 Oct 2025</p>
            </div>
        </div>
    </div>
</div>

<?php
$pageContent = ob_get_clean();

include_once LAYOUTS_DIR . 'dashboard.php';
