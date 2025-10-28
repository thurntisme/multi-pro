<?php
ob_start();
?>

<!-- Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
  <h1><?= $title ?></h1>
  <div>
    <a href="/app/project/detail" class="btn btn-outline-secondary me-2"><i class="ri-arrow-go-back-line me-1"></i>Back</a>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addQAModal">
      <i class="ri-add-line me-1"></i>Ask a Question
    </button>
  </div>
</div>

<!-- QA List -->
<div class="card shadow-sm mb-3 qa-card p-3">
  <div class="d-flex justify-content-between align-items-start">
    <div>
      <div class="d-flex align-items-center gap-2 mb-1">
        <h6 class="fw-semibold mb-0">How will we handle image optimization in the new website?</h6>
        <span class="badge text-bg-success badge-status">Resolved</span>
      </div>
      <div class="qa-meta mb-1">
        <small><i class="ri-user-line me-1"></i><span class="qa-member">Thuy Nguyen</span></small> |
        <small><i class="ri-time-line me-1"></i>Asked: 2025-10-25 14:20</small>
      </div>
      <div class="qa-answer">
        <p class="mb-1"><strong>Client Response (2025-10-26 09:30):</strong></p>
        <p class="mb-0">We’ll use Next.js built-in image optimization and host all images on Cloudinary for performance.</p>
      </div>
    </div>
    <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#responseModal">
      <i class="ri-reply-line me-1"></i>Respond
    </button>
  </div>
</div>

<!-- 🟦 Add QA Modal -->
<div class="modal fade" id="addQAModal" tabindex="-1" aria-labelledby="addQAModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addQAModalLabel"><i class="ri-add-line me-1"></i>Ask a New Question</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form>
          <div class="mb-3">
            <label class="form-label">Your Question</label>
            <textarea class="form-control" rows="3" placeholder="Type your question..."></textarea>
          </div>
          <div class="row">
            <div class="col-md-6">
              <label class="form-label">Asked By</label>
              <input type="text" class="form-control" placeholder="Enter your name" value="Thuy Nguyen">
            </div>
            <div class="col-md-6">
              <label class="form-label">Date</label>
              <input type="datetime-local" class="form-control" value="2025-10-26T11:00">
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button class="btn btn-primary"><i class="ri-send-plane-line me-1"></i>Submit Question</button>
      </div>
    </div>
  </div>
</div>

<!-- 🟨 Response Modal -->
<div class="modal fade" id="responseModal" tabindex="-1" aria-labelledby="responseModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><i class="ri-reply-line me-1"></i>Respond to Question</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form>
          <div class="mb-3">
            <label class="form-label">Question</label>
            <input type="text" class="form-control" value="How will we handle image optimization in the new website?" readonly>
          </div>
          <div class="mb-3">
            <label class="form-label">Client Response</label>
            <textarea class="form-control" rows="3" placeholder="Type your response..."></textarea>
          </div>
          <div class="row">
            <div class="col-md-6">
              <label class="form-label">Response Date</label>
              <input type="datetime-local" class="form-control" value="2025-10-26T10:30">
            </div>
            <div class="col-md-6">
              <label class="form-label">Status</label>
              <select class="form-select">
                <option value="pending">Pending</option>
                <option value="resolved" selected>Resolved</option>
              </select>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button class="btn btn-success"><i class="ri-check-line me-1"></i>Submit & Resolve</button>
      </div>
    </div>
  </div>
</div>

<?php
$pageContent = ob_get_clean();

include_once LAYOUTS_DIR . 'dashboard.php';
