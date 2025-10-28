<?php
ob_start();
?>

<!-- Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><?= $title ?></h1>
    <button
        class="btn btn-primary"
        data-bs-toggle="modal"
        data-bs-target="#addLeadModal">
        <i class="ri-user-add-line me-1"></i> Add New
    </button>
</div>

<!-- Lead List -->
<div class="list-group shadow-sm">
    <!-- Lead Item -->
    <div
        class="list-group-item d-flex justify-content-between align-items-center lead-item">
        <div class="d-flex align-items-center">
            <img
                src="https://via.placeholder.com/50"
                class="rounded-circle me-3 lead-avatar"
                alt="Lead Avatar" />
            <div>
                <h6 class="mb-1 fw-bold">Leonardo DiCaprio</h6>
                <small class="text-muted">Actor • United States</small>
            </div>
        </div>
        <div>
            <button
                class="btn btn-sm btn-outline-primary me-2"
                data-bs-toggle="modal"
                data-bs-target="#viewLeadModal">
                <i class="ri-eye-line"></i>
            </button>
            <button class="btn btn-sm btn-outline-success me-2">
                <i class="ri-edit-line"></i>
            </button>
            <button class="btn btn-sm btn-outline-danger">
                <i class="ri-delete-bin-line"></i>
            </button>
        </div>
    </div>

    <!-- Another Lead -->
    <div
        class="list-group-item d-flex justify-content-between align-items-center lead-item">
        <div class="d-flex align-items-center">
            <img
                src="https://via.placeholder.com/50"
                class="rounded-circle me-3 lead-avatar"
                alt="Lead Avatar" />
            <div>
                <h6 class="mb-1 fw-bold">Taylor Swift</h6>
                <small class="text-muted">Singer • United States</small>
            </div>
        </div>
        <div>
            <button
                class="btn btn-sm btn-outline-primary me-2"
                data-bs-toggle="modal"
                data-bs-target="#viewLeadModal">
                <i class="ri-eye-line"></i>
            </button>
            <button class="btn btn-sm btn-outline-success me-2">
                <i class="ri-edit-line"></i>
            </button>
            <button class="btn btn-sm btn-outline-danger">
                <i class="ri-delete-bin-line"></i>
            </button>
        </div>
    </div>
</div>

<!-- Modal: Add New Lead -->
<div
    class="modal fade"
    id="addLeadModal"
    tabindex="-1"
    aria-labelledby="addLeadModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="addLeadModalLabel">
                    Add New Lead
                </h5>
                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="leadForm">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Full Name</label>
                            <input
                                type="text"
                                class="form-control"
                                placeholder="Enter full name"
                                required />
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Profession</label>
                            <input
                                type="text"
                                class="form-control"
                                placeholder="Enter profession" />
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Country</label>
                            <input
                                type="text"
                                class="form-control"
                                placeholder="Enter country" />
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Avatar URL</label>
                            <input
                                type="url"
                                class="form-control"
                                placeholder="https://example.com/avatar.jpg" />
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Website</label>
                            <input
                                type="url"
                                class="form-control"
                                placeholder="https://officialwebsite.com" />
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Facebook</label>
                            <input
                                type="url"
                                class="form-control"
                                placeholder="https://facebook.com/username" />
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Twitter</label>
                            <input
                                type="url"
                                class="form-control"
                                placeholder="https://twitter.com/username" />
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Instagram</label>
                            <input
                                type="url"
                                class="form-control"
                                placeholder="https://instagram.com/username" />
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">YouTube</label>
                            <input
                                type="url"
                                class="form-control"
                                placeholder="https://youtube.com/@channel" />
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button
                    type="button"
                    class="btn btn-secondary"
                    data-bs-dismiss="modal">
                    Cancel
                </button>
                <button type="submit" form="leadForm" class="btn btn-primary">
                    <i class="ri-save-line me-1"></i> Save Lead
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal: View Lead Detail -->
<div
    class="modal fade"
    id="viewLeadModal"
    tabindex="-1"
    aria-labelledby="viewLeadModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="viewLeadModalLabel">
                    Lead Detail
                </h5>
                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="d-flex align-items-center mb-4">
                    <img
                        src="https://via.placeholder.com/80"
                        alt="Lead Avatar"
                        class="rounded-circle me-3"
                        width="80"
                        height="80" />
                    <div>
                        <h5 class="fw-bold mb-0">Leonardo DiCaprio</h5>
                        <small class="text-muted">Actor • United States</small>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <strong>Website:</strong>
                        <a href="#" class="text-decoration-none d-block">https://leonardodicaprio.com</a>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Facebook:</strong>
                        <a href="#" class="text-decoration-none d-block">facebook.com/leo</a>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Twitter:</strong>
                        <a href="#" class="text-decoration-none d-block">twitter.com/leo</a>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Instagram:</strong>
                        <a href="#" class="text-decoration-none d-block">instagram.com/leo</a>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>YouTube:</strong>
                        <a href="#" class="text-decoration-none d-block">youtube.com/@leonardo</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$pageContent = ob_get_clean();

include_once LAYOUTS_DIR . 'dashboard.php';
