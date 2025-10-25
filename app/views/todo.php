<?php
ob_start();
?>
<!-- Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><?= $title ?></h1>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addTodoModal">
        <i class="ri-add-line me-1"></i>Add New
    </button>
</div>

<!-- Summary Box -->
<div class="row mb-4 g-3">
    <div class="col-md-3">
        <div class="card summary-card shadow-sm text-center p-3">
            <h6 class="text-muted">Total Items</h6>
            <h4 class="fw-bold" id="totalCount">0</h4>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card summary-card shadow-sm text-center p-3">
            <h6 class="text-muted">Completed</h6>
            <h4 class="fw-bold text-success" id="completedCount">0</h4>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card summary-card shadow-sm text-center p-3">
            <h6 class="text-muted">In Progress</h6>
            <h4 class="fw-bold text-warning" id="inProgressCount">0</h4>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card summary-card shadow-sm text-center p-3">
            <h6 class="text-muted">Archived</h6>
            <h4 class="fw-bold text-secondary" id="archivedCount">0</h4>
        </div>
    </div>
</div>

<!-- Todo List -->
<div class="list-group" id="todoList">
    <!-- Example Item -->
    <div class="list-group-item todo-item d-flex justify-content-between align-items-start" data-status="in-progress">
        <div>
            <h6 class="mb-1">Finish project documentation
                <span class="badge bg-warning text-dark badge-status">In-progress</span>
                <span class="badge bg-danger badge-priority">High</span>
            </h6>
            <p class="mb-1">Complete the documentation for the AI project before release.</p>
            <small class="text-muted">Due: 2025-10-30</small>
        </div>
        <div class="btn-group">
            <button class="btn btn-sm btn-outline-success complete-btn" title="Toggle Complete">
                <i class="ri-checkbox-line"></i>
            </button>
            <button class="btn btn-sm btn-outline-warning edit-btn" title="Edit">
                <i class="ri-edit-2-line"></i>
            </button>
            <button class="btn btn-sm btn-outline-danger delete-btn" title="Delete">
                <i class="ri-delete-bin-line"></i>
            </button>
        </div>
    </div>
</div>

<!-- Modal: Add New Todo -->
<div class="modal fade" id="addTodoModal" tabindex="-1" aria-labelledby="addTodoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header">
                <h5 class="modal-title" id="addTodoModalLabel">
                    <i class="ri-add-line me-2"></i>Add New Todo
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="addTodoForm">
                    <div class="mb-3">
                        <label for="todoTitle" class="form-label">Title</label>
                        <input type="text" class="form-control" id="todoTitle" placeholder="Enter todo title" required>
                    </div>
                    <div class="mb-3">
                        <label for="todoDescription" class="form-label">Description</label>
                        <textarea class="form-control" id="todoDescription" rows="2" placeholder="Enter description"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="todoPriority" class="form-label">Priority</label>
                        <select class="form-select" id="todoPriority" required>
                            <option value="Low">Low</option>
                            <option value="Medium" selected>Medium</option>
                            <option value="High">High</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="todoDueDate" class="form-label">Due Date</label>
                        <input type="date" class="form-control" id="todoDueDate" required>
                    </div>
                    <div class="mb-3">
                        <label for="todoStatus" class="form-label">Status</label>
                        <select class="form-select" id="todoStatus" required>
                            <option value="in-progress" selected>In-progress</option>
                            <option value="completed">Completed</option>
                            <option value="archived">Archived</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="ri-save-3-line me-1"></i>Save Todo
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php
$pageContent = ob_get_clean();

include_once LAYOUTS_DIR . 'dashboard.php';
