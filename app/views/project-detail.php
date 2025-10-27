<?php
ob_start();
?>

<!-- Page Header -->
<div class="d-flex align-items-center justify-content-between mb-4">
  <h1><?= $title ?></h1>
  <div>
    <a href="/app/project/estimate" class="btn btn-info me-2"><i class="ri-timer-line me-1"></i>Estimate</a>
    <a href="/app/project/edit" class="btn btn-primary me-2"><i class="ri-pencil-line me-1"></i>Edit</a>
    <button class="btn btn-danger"><i class="ri-delete-bin-line me-1"></i>Delete</button>
  </div>
</div>

<div class="row g-4">

  <!-- Left column -->
  <div class="col-lg-8">
    <div class="card shadow-sm mb-4">
      <div class="card-body">
        <h3 class="fw-semibold mb-2">E-Commerce Website Redesign</h3>
        <p class="text-muted mb-3"><i class="ri-user-3-line me-1"></i><strong>Client:</strong> Acme Corporation</p>
        <div class="mb-3">
          <span class="badge bg-primary me-1">ReactJS</span>
          <span class="badge bg-success me-1">Next.js</span>
          <span class="badge bg-warning text-dark me-1">WooCommerce</span>
          <span class="badge bg-info text-dark">Headless WP</span>
        </div>
        <p class="lead">
          Redesign and rebuild the client's e-commerce platform using Next.js and Headless WordPress architecture.
          The main goals are to improve SEO performance, reduce load times, and provide a modern UI for product browsing and checkout.
        </p>
      </div>
    </div>

    <!-- Project Gallery -->
    <div class="card shadow-sm mb-4">
      <div class="card-header bg-white fw-semibold">
        <i class="ri-image-2-line me-1"></i> Project Gallery
      </div>
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

    <!-- Description / Notes -->
    <div class="card shadow-sm">
      <div class="card-header bg-white fw-semibold">
        <i class="ri-file-text-line me-1"></i> Description
      </div>
      <div class="card-body">
        <p>
          The project included migrating old data from WooCommerce to a new headless setup using WPGraphQL.
          Frontend is powered by Next.js, with ISR enabled for performance optimization.
        </p>
        <p>
          The new system also integrates with Stripe for payments and uses Vercel for deployment.
          The CMS allows the client to manage products, blog posts, and SEO fields easily through the WordPress backend.
        </p>
      </div>
    </div>
  </div>

  <!-- Right column (Sidebar) -->
  <div class="col-lg-4">
    <div class="card shadow-sm mb-4">
      <div class="card-header bg-white fw-semibold">
        <i class="ri-information-line me-1"></i> Project Info
      </div>
      <div class="card-body">
        <ul class="list-group list-group-flush small">
          <li class="list-group-item d-flex justify-content-between">
            <span><strong>Status:</strong></span>
            <span class="badge bg-success">In Progress</span>
          </li>
          <li class="list-group-item d-flex justify-content-between">
            <span><strong>Category:</strong></span>
            <span>E-commerce</span>
          </li>
          <li class="list-group-item d-flex justify-content-between">
            <span><strong>Start Date:</strong></span>
            <span>Aug 10, 2025</span>
          </li>
          <li class="list-group-item d-flex justify-content-between">
            <span><strong>Due Date:</strong></span>
            <span>Dec 15, 2025</span>
          </li>
          <li class="list-group-item d-flex justify-content-between">
            <span><strong>Budget:</strong></span>
            <span>$6,000</span>
          </li>
        </ul>
      </div>
    </div>

    <!-- Quick Actions -->
    <div class="card shadow-sm">
      <div class="card-header bg-white fw-semibold">
        <i class="ri-tools-line me-1"></i> Quick Actions
      </div>
      <div class="card-body d-grid gap-2">
        <button class="btn btn-outline-success"><i class="ri-check-double-line me-1"></i>Mark as Complete</button>
        <button class="btn btn-outline-primary"><i class="ri-share-line me-1"></i>Share with Client</button>
        <button class="btn btn-outline-secondary"><i class="ri-attachment-line me-1"></i>View Files</button>
      </div>
    </div>
  </div>

</div>

<?php
$pageContent = ob_get_clean();

include_once LAYOUTS_DIR . 'dashboard.php';
