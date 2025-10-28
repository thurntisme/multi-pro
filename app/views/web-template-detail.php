<?php
ob_start();
?>

<!-- Page Header -->
<div class="mb-4 d-flex align-items-center justify-content-between">
  <h1 class="fw-bold mb-0">
    <?= $title ?>
  </h1>
  <div>
    <button class="btn btn-success me-2"><i class="ri-download-2-line me-1"></i>Download</button>
    <button class="btn btn-outline-primary me-2"><i class="ri-file-copy-line me-1"></i>Copy</button>
    <button class="btn btn-outline-danger"><i class="ri-delete-bin-line me-1"></i>Delete</button>
  </div>
</div>

<div class="row g-4">
  <!-- Left column -->
  <div class="col-lg-8">
    <!-- Thumbnail -->
    <div class="card mb-4 shadow-sm">
      <img src="https://via.placeholder.com/800x400?text=Template+Thumbnail" class="card-img-top" alt="Template Thumbnail">
      <div class="card-body">
        <h3 class="fw-semibold">Modern Portfolio Template</h3>
        <p class="text-muted mb-2"><i class="ri-folder-3-line me-1"></i>Category: <span class="badge bg-secondary">Portfolio</span></p>
        <div class="mb-3">
          <span class="badge bg-primary me-1">React</span>
          <span class="badge bg-success me-1">Next.js</span>
          <span class="badge bg-info me-1">TailwindCSS</span>
        </div>
        <p class="lead">
          This is a modern, responsive portfolio template built with Next.js and TailwindCSS.
          It includes smooth animations, SEO optimization, and flexible section layouts for developers and designers.
        </p>
      </div>
    </div>

    <!-- Gallery -->
    <div class="card mb-4 shadow-sm">
      <div class="card-header bg-white fw-semibold"><i class="ri-image-2-line me-1"></i>Gallery</div>
      <div class="card-body">
        <div class="row g-3">
          <div class="col-6 col-md-4">
            <img src="https://via.placeholder.com/300x200?text=Screenshot+1" class="img-fluid rounded shadow-sm" alt="">
          </div>
          <div class="col-6 col-md-4">
            <img src="https://via.placeholder.com/300x200?text=Screenshot+2" class="img-fluid rounded shadow-sm" alt="">
          </div>
          <div class="col-6 col-md-4">
            <img src="https://via.placeholder.com/300x200?text=Screenshot+3" class="img-fluid rounded shadow-sm" alt="">
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Right column (Sidebar) -->
  <div class="col-lg-4">
    <div class="card shadow-sm mb-4">
      <div class="card-header bg-white fw-semibold">
        <i class="ri-settings-3-line me-1"></i> Required Plugins
      </div>
      <div class="card-body">
        <ul class="list-group list-group-flush small">
          <li class="list-group-item d-flex justify-content-between align-items-center">
            Advanced Custom Fields
            <span class="badge bg-success">Installed</span>
          </li>
          <li class="list-group-item d-flex justify-content-between align-items-center">
            WP GraphQL
            <span class="badge bg-warning text-dark">Required</span>
          </li>
          <li class="list-group-item d-flex justify-content-between align-items-center">
            Elementor
            <span class="badge bg-danger">Missing</span>
          </li>
        </ul>
      </div>
    </div>

    <div class="card shadow-sm">
      <div class="card-header bg-white fw-semibold">
        <i class="ri-information-line me-1"></i> Info
      </div>
      <div class="card-body">
        <p><strong>Created:</strong> Oct 2025</p>
        <p><strong>Updated:</strong> Oct 2025</p>
        <p><strong>Version:</strong> 1.2.0</p>
      </div>
    </div>
  </div>
</div>

<?php
$pageContent = ob_get_clean();

include_once LAYOUTS_DIR . 'dashboard.php';
