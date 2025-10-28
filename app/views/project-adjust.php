<?php
ob_start();
?>

<!-- Page Header -->
<div class="d-flex align-items-center justify-content-between mb-4">
    <h1><?= $title ?></h1>
    <div>
        <a class="btn btn-outline-secondary me-2" href="/app/project/detail">
            <i class="ri-arrow-go-back-line me-1"></i>Back
        </a>
        <button class="btn btn-primary">
            <i class="ri-save-3-line me-1"></i>Save Changes
        </button>
    </div>
</div>

<!-- Main Layout -->
<form>
    <div class="row g-4">

        <!-- LEFT COLUMN -->
        <div class="col-lg-8">

            <!-- Project Information -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white fw-semibold">
                    <i class="ri-information-line me-1"></i> Project Information
                </div>
                <div class="card-body">

                    <!-- Title -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Project Title</label>
                        <input type="text" class="form-control" placeholder="Enter project title" value="E-Commerce Website Redesign">
                    </div>

                    <!-- Client -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Client</label>
                        <input type="text" class="form-control" placeholder="Enter client name" value="Acme Corporation">
                    </div>

                    <!-- Summary -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Summary</label>
                        <textarea class="form-control" rows="2" placeholder="Short project summary">Redesign and rebuild the client's e-commerce platform using Next.js and Headless WordPress.</textarea>
                    </div>

                    <!-- Description -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Description</label>
                        <textarea class="form-control" rows="5" placeholder="Detailed description of the project...">The project focuses on improving UX, SEO, and speed by implementing Next.js with a headless WordPress backend.</textarea>
                    </div>

                    <!-- Tags -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Tags / Technologies</label>
                        <input type="text" class="form-control" placeholder="e.g. React, Next.js, WooCommerce, WPGraphQL" value="Next.js, WordPress, WooCommerce, TailwindCSS">
                        <div class="form-text">Separate tags by commas.</div>
                    </div>

                    <!-- Category -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Category</label>
                        <select class="form-select">
                            <option selected>E-commerce</option>
                            <option>Corporate Website</option>
                            <option>Portfolio</option>
                            <option>Landing Page</option>
                            <option>Internal Tool</option>
                        </select>
                    </div>

                    <!-- Status -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Status</label>
                        <select class="form-select">
                            <option>Planning</option>
                            <option selected>In Progress</option>
                            <option>Review</option>
                            <option>Completed</option>
                            <option>Archived</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- RIGHT COLUMN -->
        <div class="col-lg-4">

            <!-- Project Reference -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white fw-semibold">
                    <i class="ri-links-line me-1"></i> Project Reference Links
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Design (Figma, XD, ...)</label>
                        <input type="url" class="form-control" placeholder="https://figma.com/...">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Documentation</label>
                        <input type="url" class="form-control" placeholder="https://docs.google.com/...">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Development Link</label>
                        <input type="url" class="form-control" placeholder="https://dev.project.com">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Staging Link</label>
                        <input type="url" class="form-control" placeholder="https://staging.project.com">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Production Link</label>
                        <input type="url" class="form-control" placeholder="https://www.project.com">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Reference / Inspiration Website</label>
                        <input type="url" class="form-control" placeholder="https://example.com">
                    </div>
                </div>
            </div>

            <!-- Quick Notes -->
            <div class="card shadow-sm">
                <div class="card-header bg-white fw-semibold">
                    <i class="ri-sticky-note-line me-1"></i> Quick Notes
                </div>
                <div class="card-body">
                    <textarea class="form-control" rows="4" placeholder="Add any quick notes or comments..."></textarea>
                </div>
            </div>
        </div>

    </div>
</form>

<?php
$pageContent = ob_get_clean();

include_once LAYOUTS_DIR . 'dashboard.php';
