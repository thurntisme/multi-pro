<?php
ob_start(); ?>
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-lite.min.css" rel="stylesheet">
<?php
$additionCss = ob_get_clean();
ob_start();
?>

<!-- Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><?= $title ?> Page</h1>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addNoteModal">
        <i class="ri-add-line me-1"></i> Add New
    </button>
</div>

<div class="row h-100">
    <!-- Sidebar -->
    <div class="col-lg-3 d-flex flex-column">
        <div class="bg-white">
            <div class="p-3">
                <input type="text" class="form-control form-control-sm" placeholder="Search note...">
            </div>

            <div id="noteList" class="list-group list-group-flush">
                <!-- Sample Notes -->
                <div class="list-group-item note-item cursor-pointer active" data-id="1">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-truncate">Meeting Notes Meeting Notes Meeting Notes Meeting Notes</span>
                    </div>
                </div>
                <div class="list-group-item note-item cursor-pointer" data-id="2">
                    <div class="d-flex justify-content-between align-items-center">
                        <span>Shopping List</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Content -->
    <div class="col-lg-9 ps-4">
        <div class="p-4 card border-0 shadow-sm">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <div>
                    <h3 class="fw-bold mb-1">Morning Reflection</h3>
                    <small class="text-muted">Oct 13, 2025</small>
                </div>
                <div class="btn-group">
                    <button class="btn btn-light btn-sm"><i class="ri-edit-2-line"></i></button>
                    <button class="btn btn-light btn-sm text-danger"><i class="ri-delete-bin-line"></i></button>
                </div>
            </div>

            <div class="text-primary">
                <p>Today I woke up early, had some coffee, and read a chapter of <em>Atomic Habits</em>.</p>
                <p>Feeling positive and ready to take on new challenges.</p>
            </div>
        </div>
    </div>
</div>

<!-- Add Note Modal -->
<div class="modal fade" id="addNoteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Add New Note</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="noteForm">
                    <div class="mb-3">
                        <label class="form-label">Title</label>
                        <input type="text" class="form-control" placeholder="Enter note title">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Content</label>
                        <textarea id="summernote" name="content"></textarea>
                    </div>
                    <div class="text-end">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save Note</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php
$pageContent = ob_get_clean();
ob_start(); ?>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-lite.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        $('#summernote').summernote({
            placeholder: 'Write your note here...',
            tabsize: 2,
            height: 200,
            toolbar: [
                ['style', ['bold', 'italic', 'underline']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['insert', ['link', 'picture']],
                ['view', ['codeview']]
            ]
        });
    });
</script>
<?php
$additionJs = ob_get_clean();
include_once LAYOUTS_DIR . 'dashboard.php';
