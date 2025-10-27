<?php
ob_start();
?>

<!-- Header + Filter -->
<div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
  <h1><?= $title ?></h1>

  <div class="d-flex align-items-center gap-2">
    <label for="categoryFilter" class="form-label mb-0 fw-semibold">Filter by Category:</label>
    <select id="categoryFilter" class="form-select form-select-sm" style="width: 200px;">
      <option value="all">All</option>
      <option value="landing">Landing Pages</option>
      <option value="portfolio">Portfolio</option>
      <option value="blog">Blog</option>
      <option value="ecommerce">E-Commerce</option>
    </select>
  </div>
</div>

<!-- Template Grid -->
<div class="row g-4" id="templateGrid">

  <!-- Template Item -->
  <div class="col-lg-4 col-md-6 template-item" data-category="portfolio">
    <div class="card template-card h-100">
      <img src="https://via.placeholder.com/600x350" class="card-img-top" alt="Template Thumbnail">
      <div class="card-body">
        <h5 class="card-title mb-2">Modern Portfolio Template</h5>
        <span class="badge category-badge mb-3">Portfolio</span>
        <p class="card-text text-muted small mb-2">Clean and minimal design for personal portfolios or freelancers.</p>

        <!-- Tags -->
        <div class="mb-2">
          <span class="tag">Responsive</span>
          <span class="tag">Bootstrap 5</span>
          <span class="tag">Dark Mode</span>
        </div>

        <!-- Plugins -->
        <div class="mb-3">
          <span class="plugin">Elementor</span>
          <span class="plugin">Contact Form 7</span>
        </div>

        <div class="d-flex justify-content-between">
          <a href="#" class="btn btn-sm btn-outline-primary">
            <i class="ri-eye-line me-1"></i>Preview
          </a>
          <a href="/app/web-template/detail" class="btn btn-sm btn-outline-secondary">
            <i class="ri-information-line me-1"></i>Detail
          </a>
        </div>
      </div>
    </div>
  </div>

  <!-- Template Item -->
  <div class="col-lg-4 col-md-6 template-item" data-category="landing">
    <div class="card template-card h-100">
      <img src="https://via.placeholder.com/600x350" class="card-img-top" alt="Template Thumbnail">
      <div class="card-body">
        <h5 class="card-title mb-2">Startup Landing Page</h5>
        <span class="badge category-badge mb-3">Landing</span>
        <p class="card-text text-muted small mb-2">Perfect for SaaS, startups, and digital product launches.</p>

        <div class="mb-2">
          <span class="tag">Landing</span>
          <span class="tag">Next.js</span>
          <span class="tag">Animation</span>
        </div>

        <div class="mb-3">
          <span class="plugin">WPBakery</span>
          <span class="plugin">Slider Revolution</span>
        </div>

        <div class="d-flex justify-content-between">
          <a href="#" class="btn btn-sm btn-outline-primary">
            <i class="ri-eye-line me-1"></i>Preview
          </a>
          <a href="#" class="btn btn-sm btn-outline-secondary">
            <i class="ri-information-line me-1"></i>Detail
          </a>
        </div>
      </div>
    </div>
  </div>

  <!-- Template Item -->
  <div class="col-lg-4 col-md-6 template-item" data-category="blog">
    <div class="card template-card h-100">
      <img src="https://via.placeholder.com/600x350" class="card-img-top" alt="Template Thumbnail">
      <div class="card-body">
        <h5 class="card-title mb-2">Tech Blog Template</h5>
        <span class="badge category-badge mb-3">Blog</span>
        <p class="card-text text-muted small mb-2">Responsive blog layout for content creators and writers.</p>

        <div class="mb-2">
          <span class="tag">Blog</span>
          <span class="tag">SEO Optimized</span>
          <span class="tag">Clean UI</span>
        </div>

        <div class="mb-3">
          <span class="plugin">Yoast SEO</span>
          <span class="plugin">Jetpack</span>
        </div>

        <div class="d-flex justify-content-between">
          <a href="#" class="btn btn-sm btn-outline-primary">
            <i class="ri-eye-line me-1"></i>Preview
          </a>
          <a href="#" class="btn btn-sm btn-outline-secondary">
            <i class="ri-information-line me-1"></i>Detail
          </a>
        </div>
      </div>
    </div>
  </div>

</div>

<?php
$pageContent = ob_get_clean();

include_once LAYOUTS_DIR . 'dashboard.php';
