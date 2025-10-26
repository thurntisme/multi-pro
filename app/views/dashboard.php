<?php
ob_start();
?>

<!-- Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1></i>Daily Check-In Portal</h3>
        <button class="btn btn-outline-secondary btn-sm" onclick="window.location.reload()">
            <i class="ri-refresh-line me-1"></i>Refresh
        </button>
</div>

<!-- Summary -->
<div class="row mb-4 g-3">
    <div class="col-md-3">
        <div class="card summary-card shadow-sm text-center p-3">
            <h6 class="text-muted">Check-In Time</h6>
            <h4 id="checkInTime" class="fw-bold text-primary">--:--</h4>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card summary-card shadow-sm text-center p-3">
            <h6 class="text-muted">Check-Out Time</h6>
            <h4 id="checkOutTime" class="fw-bold text-danger">--:--</h4>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card summary-card shadow-sm text-center p-3">
            <h6 class="text-muted">Working Hours</h6>
            <h4 id="workHours" class="fw-bold text-success">0h 00m</h4>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card summary-card shadow-sm text-center p-3">
            <h6 class="text-muted">Status</h6>
            <span id="statusBadge" class="badge bg-secondary fs-6 px-3 py-2">Not Checked-in</span>
        </div>
    </div>
</div>

<!-- Action Buttons -->
<div class="d-flex justify-content-center gap-3 mb-4">
    <button id="checkInBtn" class="btn btn-success check-btn shadow-sm" data-bs-toggle="modal" data-bs-target="#confirmModal">
        <i class="ri-login-circle-line me-1"></i>Check In
    </button>
    <button id="checkOutBtn" class="btn btn-danger check-btn shadow-sm d-none">
        <i class="ri-logout-circle-line me-1"></i>Check Out
    </button>
</div>

<!-- History -->
<div class="card shadow-sm border-0">
    <div class="card-header bg-light d-flex justify-content-between align-items-center">
        <h5 class="mb-0 fw-semibold"><i class="ri-history-line me-2 text-primary"></i>Check-In / Check-Out History</h5>
    </div>
    <div class="list-group list-group-flush" id="historyList">
        <div class="list-group-item history-item d-flex justify-content-between">
            <div><strong>2025-10-25</strong> - Checked In at <span class="text-primary">08:45</span>, Checked Out at <span class="text-danger">17:30</span></div>
            <div><span class="badge bg-success">8h 45m</span></div>
        </div>
    </div>
</div>

<!-- Modal Confirm -->
<div class="modal fade" id="confirmModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header">
                <h5 class="modal-title"><i class="ri-time-line me-2"></i>Confirm Action</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center py-4">
                <i class="ri-question-line display-5 text-primary mb-3"></i>
                <p id="confirmText" class="fs-5 mb-0">Are you sure?</p>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" id="confirmBtn" class="btn btn-primary">Confirm</button>
            </div>
        </div>
    </div>
</div>

<?php
$pageContent = ob_get_clean();

include_once LAYOUTS_DIR . 'dashboard.php';
