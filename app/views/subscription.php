<?php
ob_start();
?>

<!-- Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><?= $title ?></h1>
</div>

<!-- Summary Section -->
<div class="row g-4 mb-2">
    <div class="col-md-3">
        <div class="card border-0 shadow-sm text-center p-3">
            <div class="text-primary mb-2"><i class="ri-wallet-3-line ri-2x"></i></div>
            <h6 class="text-muted mb-1">Total Spending (Month)</h6>
            <h4 class="fw-bold">$240.00</h4>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm text-center p-3">
            <div class="text-success mb-2"><i class="ri-calendar-check-line ri-2x"></i></div>
            <h6 class="text-muted mb-1">Active Subscriptions</h6>
            <h4 class="fw-bold">8</h4>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm text-center p-3">
            <div class="text-warning mb-2"><i class="ri-timer-2-line ri-2x"></i></div>
            <h6 class="text-muted mb-1">Upcoming Payments</h6>
            <h4 class="fw-bold">3</h4>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm text-center p-3">
            <div class="text-danger mb-2"><i class="ri-refresh-line ri-2x"></i></div>
            <h6 class="text-muted mb-1">Renewals This Week</h6>
            <h4 class="fw-bold">$45.00</h4>
        </div>
    </div>
</div>

<!-- Filter -->
<div class="d-flex justify-content-between align-items-center mb-3">
    <div class="btn-group btn-group-sm" role="group">
        <button class="btn btn-outline-secondary active">All</button>
        <button class="btn btn-outline-secondary">Daily</button>
        <button class="btn btn-outline-secondary">Weekly</button>
        <button class="btn btn-outline-secondary">Monthly</button>
        <button class="btn btn-outline-secondary">Yearly</button>
    </div>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addSubscriptionModal">
        <i class="ri-add-line me-1"></i> Add New
    </button>
</div>

<!-- Subscription List -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body p-0">
        <table class="table table-hover align-middle mb-0">
            <thead class="bg-light">
                <tr>
                    <th class="ps-4">Service</th>
                    <th class="text-center">Billing Cycle</th>
                    <th>Next Payment</th>
                    <th class="text-center">Status</th>
                    <th class="text-end pe-4">Amount</th>
                </tr>
            </thead>
            <tbody>

                <!-- Netflix -->
                <tr>
                    <td class="ps-4">
                        <div class="d-flex align-items-center">
                            <div class="rounded-circle bg-danger bg-gradient d-flex align-items-center justify-content-center text-white me-3" style="width: 42px; height: 42px;">
                                <i class="ri-netflix-fill fs-5"></i>
                            </div>
                            <div>
                                <div class="fw-bold">Netflix Premium</div>
                                <div class="text-muted small">4K UHD + 4 Devices</div>
                            </div>
                        </div>
                    </td>
                    <td class="text-center"><span class="badge bg-secondary">Monthly</span></td>
                    <td><i class="ri-calendar-line me-1"></i>25 Oct 2025</td>
                    <td class="text-center"><span class="badge bg-success-subtle text-success"><i class="ri-check-line me-1"></i>Active</span></td>
                    <td class="text-end pe-4 fw-bold text-danger">$15.99</td>
                </tr>

                <!-- Spotify -->
                <tr>
                    <td class="ps-4">
                        <div class="d-flex align-items-center">
                            <div class="rounded-circle bg-success bg-gradient d-flex align-items-center justify-content-center text-white me-3" style="width: 42px; height: 42px;">
                                <i class="ri-spotify-fill fs-5"></i>
                            </div>
                            <div>
                                <div class="fw-bold">Spotify Family</div>
                                <div class="text-muted small">Up to 6 accounts</div>
                            </div>
                        </div>
                    </td>
                    <td class="text-center"><span class="badge bg-secondary">Monthly</span></td>
                    <td><i class="ri-calendar-line me-1"></i>2 Nov 2025</td>
                    <td class="text-center"><span class="badge bg-warning-subtle text-warning"><i class="ri-timer-line me-1"></i>Due soon</span></td>
                    <td class="text-end pe-4 fw-bold text-success">$12.99</td>
                </tr>

                <!-- Adobe -->
                <tr>
                    <td class="ps-4">
                        <div class="d-flex align-items-center">
                            <div class="rounded-circle bg-primary bg-gradient d-flex align-items-center justify-content-center text-white me-3" style="width: 42px; height: 42px;">
                                <i class="ri-palette-line fs-5"></i>
                            </div>
                            <div>
                                <div class="fw-bold">Adobe Creative Cloud</div>
                                <div class="text-muted small">Annual license</div>
                            </div>
                        </div>
                    </td>
                    <td class="text-center"><span class="badge bg-secondary">Yearly</span></td>
                    <td><i class="ri-calendar-line me-1"></i>10 Dec 2025</td>
                    <td class="text-center"><span class="badge bg-info-subtle text-info"><i class="ri-refresh-line me-1"></i>Renewal</span></td>
                    <td class="text-end pe-4 fw-bold text-primary">$240.00</td>
                </tr>

            </tbody>
        </table>
    </div>
</div>

<!-- Add Subscription Modal -->
<div class="modal fade" id="addSubscriptionModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header">
                <h5 class="modal-title fw-bold"><i class="ri-add-line text-primary me-1"></i>Add Subscription</h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Service Name</label>
                        <input type="text" class="form-control" placeholder="e.g., Netflix, Figma Pro">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Cycle</label>
                        <select class="form-select">
                            <option>Daily</option>
                            <option>Weekly</option>
                            <option selected>Monthly</option>
                            <option>Yearly</option>
                        </select>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Amount</label>
                            <input type="number" class="form-control" placeholder="$">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Next Payment</label>
                            <input type="date" class="form-control">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Notes</label>
                        <textarea class="form-control" rows="2" placeholder="Optional..."></textarea>
                    </div>
                    <div class="text-end">
                        <button class="btn btn-success"><i class="ri-save-3-line me-1"></i>Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php
$pageContent = ob_get_clean();

include_once LAYOUTS_DIR . 'dashboard.php';
