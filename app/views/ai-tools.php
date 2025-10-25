<?php
ob_start();
?>

<!-- Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><?= $title ?></h1>
    <button class="btn btn-primary d-flex align-items-center" data-bs-toggle="modal" data-bs-target="#addToolModal">
        <i class="ri-add-line me-2"></i>Add New
    </button>
</div>

<!-- Tools List -->
<div class="card shadow-sm mb-3 p-3">
    <div class="row align-items-center">
        <div class="col-md-2 text-center">
            <img src="https://via.placeholder.com/60" alt="Tool Logo" class="rounded">
        </div>
        <div class="col-md-6">
            <h5 class="mb-1">ChatGPT</h5>
            <p class="text-muted mb-1">An AI assistant developed by OpenAI for conversation and productivity.</p>
            <a href="https://chat.openai.com" target="_blank" class="text-decoration-none">https://chat.openai.com</a>
        </div>
        <div class="col-md-4 text-end">
            <button class="btn btn-outline-primary btn-sm me-1" data-bs-toggle="modal" data-bs-target="#viewToolModal"><i class="ri-eye-line"></i> View</button>
            <button class="btn btn-outline-secondary btn-sm me-1"><i class="ri-edit-line"></i> Edit</button>
            <button class="btn btn-outline-danger btn-sm"><i class="ri-delete-bin-line"></i> Delete</button>
        </div>
    </div>
</div>

<div class="card shadow-sm mb-3 p-3">
    <div class="row align-items-center">
        <div class="col-md-2 text-center">
            <img src="https://via.placeholder.com/60" alt="Tool Logo" class="rounded">
        </div>
        <div class="col-md-6">
            <h5 class="mb-1">Midjourney</h5>
            <p class="text-muted mb-1">AI image generation tool for creating stunning visuals from text prompts.</p>
            <a href="https://www.midjourney.com" target="_blank" class="text-decoration-none">https://www.midjourney.com</a>
        </div>
        <div class="col-md-4 text-end">
            <button class="btn btn-outline-primary btn-sm me-1" data-bs-toggle="modal" data-bs-target="#viewToolModal"><i class="ri-eye-line"></i> View</button>
            <button class="btn btn-outline-secondary btn-sm me-1"><i class="ri-edit-line"></i> Edit</button>
            <button class="btn btn-outline-danger btn-sm"><i class="ri-delete-bin-line"></i> Delete</button>
        </div>
    </div>
</div>

<!-- Add New Modal -->
<div class="modal fade" id="addToolModal" tabindex="-1" aria-labelledby="addToolModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="addToolModalLabel">Add New AI Tool</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="mb-3">
                        <label class="form-label">Tool Name</label>
                        <input type="text" class="form-control" placeholder="Enter tool name">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea class="form-control" rows="2" placeholder="Short description"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Website</label>
                        <input type="url" class="form-control" placeholder="https://example.com">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Category</label>
                        <select class="form-select">
                            <option>Chatbot</option>
                            <option>Image Generation</option>
                            <option>Code Assistant</option>
                            <option>Writing Tool</option>
                            <option>Other</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tags</label>
                        <input type="text" class="form-control" placeholder="e.g. AI, Productivity">
                    </div>
                    <div class="text-end">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Add Tool</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- View Detail Modal -->
<div class="modal fade" id="viewToolModal" tabindex="-1" aria-labelledby="viewToolModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="viewToolModalLabel">AI Tool Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-3">
                    <img src="https://via.placeholder.com/100" alt="Tool Logo" class="rounded mb-2">
                    <h5 class="fw-bold mb-0">ChatGPT</h5>
                    <small class="text-muted">Chatbot / Assistant</small>
                </div>
                <p><strong>Description:</strong> An advanced conversational AI by OpenAI for writing, coding, and productivity.</p>
                <p><strong>Website:</strong> <a href="https://chat.openai.com" target="_blank">https://chat.openai.com</a></p>
                <p><strong>Tags:</strong> AI, Chatbot, Productivity</p>
            </div>
        </div>
    </div>
</div>

<?php
$pageContent = ob_get_clean();

include_once LAYOUTS_DIR . 'dashboard.php';
