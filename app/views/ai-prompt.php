<?php
ob_start();
?>

<!-- Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><?= $title ?></h1>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addPromptModal">
        <i class="ri-add-line me-1"></i>Add New
    </button>
</div>

<!-- Prompt List -->
<div class="list-group">
    <!-- Item -->
    <div class="list-group-item prompt-item">
        <div class="d-flex justify-content-between align-items-start">
            <div>
                <h6 class="mb-1">Blog Post Idea Generator</h6>
                <small class="text-muted">Generates creative blog post ideas based on a topic.</small>
            </div>
            <div class="btn-group">
                <button class="btn btn-sm btn-outline-primary toggle-btn">
                    <i class="ri-arrow-down-s-line"></i>
                </button>
                <button class="btn btn-sm btn-outline-success">
                    <i class="ri-eye-line"></i>
                </button>
                <button class="btn btn-sm btn-outline-warning">
                    <i class="ri-edit-2-line"></i>
                </button>
                <button class="btn btn-sm btn-outline-danger">
                    <i class="ri-delete-bin-line"></i>
                </button>
            </div>
        </div>
        <div class="prompt-content mt-2">
            <p>Write 10 creative blog post ideas about the topic: "Artificial Intelligence in daily life".</p>
            <button class="btn btn-sm btn-outline-secondary copy-btn">
                <i class="ri-file-copy-line me-1"></i>Copy Prompt
            </button>
        </div>
    </div>

    <!-- Another Item -->
    <div class="list-group-item prompt-item">
        <div class="d-flex justify-content-between align-items-start">
            <div>
                <h6 class="mb-1">Marketing Email Writer</h6>
                <small class="text-muted">Creates professional email drafts for marketing campaigns.</small>
            </div>
            <div class="btn-group">
                <button class="btn btn-sm btn-outline-primary toggle-btn">
                    <i class="ri-arrow-down-s-line"></i>
                </button>
                <button class="btn btn-sm btn-outline-success">
                    <i class="ri-eye-line"></i>
                </button>
                <button class="btn btn-sm btn-outline-warning">
                    <i class="ri-edit-2-line"></i>
                </button>
                <button class="btn btn-sm btn-outline-danger">
                    <i class="ri-delete-bin-line"></i>
                </button>
            </div>
        </div>
        <div class="prompt-content mt-2">
            <p>Write a persuasive marketing email for a new AI productivity tool.</p>
            <button class="btn btn-sm btn-outline-secondary copy-btn">
                <i class="ri-file-copy-line me-1"></i>Copy Prompt
            </button>
        </div>
    </div>
</div>

<!-- Modal: Add New Prompt -->
<div class="modal fade" id="addPromptModal" tabindex="-1" aria-labelledby="addPromptModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="addPromptModalLabel">
                    <i class="ri-add-line me-2"></i>Add New Prompt
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="addPromptForm">
                    <div class="mb-3">
                        <label for="promptTitle" class="form-label">Title</label>
                        <input type="text" class="form-control" id="promptTitle" placeholder="Enter prompt title" required>
                    </div>
                    <div class="mb-3">
                        <label for="promptDescription" class="form-label">Short Description</label>
                        <input type="text" class="form-control" id="promptDescription" placeholder="Enter short description">
                    </div>
                    <div class="mb-3">
                        <label for="promptContent" class="form-label">Prompt Content</label>
                        <textarea class="form-control" id="promptContent" rows="4" placeholder="Enter prompt text..." required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="ri-save-3-line me-1"></i>Save Prompt
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>


<?php
$pageContent = ob_get_clean();

include_once LAYOUTS_DIR . 'dashboard.php';
