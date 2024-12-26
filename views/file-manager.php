<?php
$pageTitle = "File Manager";

ob_start(); ?>
<!-- dropzone css -->
<link href="<?= home_url("assets/libs/dropzone/dropzone.css") ?>" rel="stylesheet" />
<?php
$additionCss = ob_get_clean();

ob_start();
?>

<div class="auth-page-content overflow-hidden p-0 pb-4">
    <div class="d-lg-flex gap-1">
        <div class="file-manager-sidebar">
            <div class="p-3 d-flex flex-column h-100">
                <div class="mb-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-semibold">My Drive</h5>
                    <button class="btn btn-success btn-sm createFile-modal" data-bs-toggle="modal" data-bs-target="#createFileModal"><i class="ri-add-line align-bottom me-1"></i> Upload File</button>
                </div>
                <div class="search-box">
                    <input type="text" class="form-control bg-light border-light" placeholder="Search here...">
                    <i class="ri-search-2-line search-icon"></i>
                </div>
                <div class="mt-3 mx-n4 px-4 file-menu-sidebar-scroll" data-simplebar>
                    <ul class="list-unstyled file-manager-menu">
                        <li>
                            <a data-bs-toggle="collapse" href="#collapseExample" role="button" aria-expanded="true" aria-controls="collapseExample">
                                <i class="ri-folder-2-line align-bottom me-2"></i> <span class="file-list-link">My Drive</span>
                            </a>
                            <div class="collapse show" id="collapseExample">
                                <ul class="sub-menu list-unstyled">
                                    <li>
                                        <a href="#!">Assets</a>
                                    </li>
                                    <li>
                                        <a href="#!">Marketing</a>
                                    </li>
                                    <li>
                                        <a href="#!">Personal</a>
                                    </li>
                                    <li>
                                        <a href="#!">Projects</a>
                                    </li>
                                    <li>
                                        <a href="#!">Templates</a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <li>
                            <a href="#!"><i class="ri-file-list-2-line align-bottom me-2"></i> <span class="file-list-link">Documents</span></a>
                        </li>
                        <li>
                            <a href="#!"><i class="ri-image-2-line align-bottom me-2"></i> <span class="file-list-link">Media</span></a>
                        <li>
                            <a href="#!"><i class="ri-history-line align-bottom me-2"></i> <span class="file-list-link">Recent</span></a>
                        </li>
                        <li>
                            <a href="#!"><i class="ri-star-line align-bottom me-2"></i> <span class="file-list-link">Important</span></a>
                        </li>
                        </li>
                        <li>
                            <a href="#!"><i class="ri-delete-bin-line align-bottom me-2"></i> <span class="file-list-link">Deleted</span></a>
                        </li>
                    </ul>
                </div>


                <div class="mt-auto">
                    <h6 class="fs-11 text-muted text-uppercase mb-3">Storage Status</h6>
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="ri-database-2-line fs-17"></i>
                        </div>
                        <div class="flex-grow-1 ms-3 overflow-hidden">
                            <div class="progress mb-2 progress-sm">
                                <div class="progress-bar bg-success" role="progressbar" style="width: 25%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <span class="text-muted fs-12 d-block text-truncate"><b>47.52</b>GB used of <b>119</b>GB</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="file-manager-content w-100 p-3 py-0">
            <div class="mx-n3 pt-4 px-4 file-manager-content-scroll" data-simplebar>
                <div id="folder-list" class="mb-2">
                    <div class="row justify-content-beetwen g-2 mb-3">

                        <div class="col">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0 me-2 d-block d-lg-none">
                                    <button type="button" class="btn btn-soft-success btn-icon btn-sm fs-16 file-menu-btn">
                                        <i class="ri-menu-2-fill align-bottom"></i>
                                    </button>
                                </div>
                                <div class="flex-grow-1">
                                    <h5 class="fs-16 mb-0">Folders</h5>
                                </div>
                            </div>
                        </div>
                        <!--end col-->
                        <div class="col-auto">
                            <div class="d-flex gap-2 align-items-start">
                                <select class="form-control" data-choices data-choices-search-false name="choices-single-default" id="file-type">
                                    <option value="">File Type</option>
                                    <option value="All" selected>All</option>
                                    <option value="Video">Video</option>
                                    <option value="Images">Images</option>
                                    <option value="Music">Music</option>
                                    <option value="Documents">Documents</option>
                                </select>

                                <button class="btn btn-success w-sm create-folder-modal flex-shrink-0" data-bs-toggle="modal" data-bs-target="#createFolderModal"><i class="ri-add-line align-bottom me-1"></i> Create Folders</button>
                            </div>
                        </div>
                        <!--end col-->
                    </div>
                    <!--end row-->
                    <div class="row" id="folderlist-data">
                        <div class="col-xxl-3 col-6 folder-card">
                            <div class="card bg-light shadow-none" id="folder-1">
                                <div class="card-body">
                                    <div class="d-flex mb-1">
                                        <div class="form-check form-check-danger mb-3 fs-15 flex-grow-1">
                                            <input class="form-check-input" type="checkbox" value="" id="folderlistCheckbox_1" checked>
                                            <label class="form-check-label" for="folderlistCheckbox_1"></label>
                                        </div>
                                        <div class="dropdown">
                                            <button class="btn btn-ghost-primary btn-icon btn-sm dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="ri-more-2-fill fs-16 align-bottom"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li><a class="dropdown-item view-item-btn" href="javascript:void(0);">Open</a></li>
                                                <li><a class="dropdown-item edit-folder-list" href="#createFolderModal" data-bs-toggle="modal" role="button">Rename</a></li>
                                                <li><a class="dropdown-item" href="#removeFolderModal" data-bs-toggle="modal" role="button">Delete</a></li>
                                            </ul>
                                        </div>
                                    </div>

                                    <div class="text-center">
                                        <div class="mb-2">
                                            <i class="ri-folder-2-fill align-bottom text-warning display-5"></i>
                                        </div>
                                        <h6 class="fs-15 folder-name">Projects</h6>
                                    </div>
                                    <div class="hstack mt-4 text-muted">
                                        <span class="me-auto"><b>349</b> Files</span>
                                        <span><b>4.10</b>GB</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--end col-->
                        <div class="col-xxl-3 col-6 folder-card">
                            <div class="card bg-light shadow-none" id="folder-2">
                                <div class="card-body">
                                    <div class="d-flex mb-1">
                                        <div class="form-check form-check-danger mb-3 fs-15 flex-grow-1">
                                            <input class="form-check-input" type="checkbox" value="" id="folderlistCheckbox_2">
                                            <label class="form-check-label" for="folderlistCheckbox_2"></label>
                                        </div>
                                        <div class="dropdown">
                                            <button class="btn btn-ghost-primary btn-icon btn-sm dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="ri-more-2-fill fs-16 align-bottom"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li><a class="dropdown-item view-item-btn" href="javascript:void(0);">Open</a></li>
                                                <li><a class="dropdown-item edit-folder-list" href="#createFolderModal" data-bs-toggle="modal" role="button">Rename</a></li>
                                                <li><a class="dropdown-item" href="#removeFolderModal" data-bs-toggle="modal" role="button">Delete</a></li>
                                            </ul>
                                        </div>
                                    </div>

                                    <div class="text-center">
                                        <div class="mb-2">
                                            <i class="ri-folder-2-fill align-bottom text-warning display-5"></i>
                                        </div>
                                        <h6 class="fs-15 folder-name">Documents</h6>
                                    </div>
                                    <div class="hstack mt-4 text-muted">
                                        <span class="me-auto"><b>2348</b> Files</span>
                                        <span><b>27.01</b>GB</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--end col-->
                        <div class="col-xxl-3 col-6 folder-card">
                            <div class="card bg-light shadow-none" id="folder-3">
                                <div class="card-body">
                                    <div class="d-flex mb-1">
                                        <div class="form-check form-check-danger mb-3 fs-15 flex-grow-1">
                                            <input class="form-check-input" type="checkbox" value="" id="folderlistCheckbox_3">
                                            <label class="form-check-label" for="folderlistCheckbox_3"></label>
                                        </div>
                                        <div class="dropdown">
                                            <button class="btn btn-ghost-primary btn-icon btn-sm dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="ri-more-2-fill fs-16 align-bottom"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li><a class="dropdown-item view-item-btn" href="javascript:void(0);">Open</a></li>
                                                <li><a class="dropdown-item edit-folder-list" href="#createFolderModal" data-bs-toggle="modal" role="button">Rename</a></li>
                                                <li><a class="dropdown-item" href="#removeFolderModal" data-bs-toggle="modal" role="button">Delete</a></li>
                                            </ul>
                                        </div>
                                    </div>

                                    <div class="text-center">
                                        <div class="mb-2">
                                            <i class="ri-folder-2-fill align-bottom text-warning display-5"></i>
                                        </div>
                                        <h6 class="fs-15 folder-name">Media</h6>
                                    </div>
                                    <div class="hstack mt-4 text-muted">
                                        <span class="me-auto"><b>12480</b> Files</span>
                                        <span><b>20.87</b>GB</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--end col-->
                        <div class="col-xxl-3 col-6 folder-card">
                            <div class="card bg-light shadow-none" id="folder-4">
                                <div class="card-body">
                                    <div class="d-flex mb-1">
                                        <div class="form-check form-check-danger mb-3 fs-15 flex-grow-1">
                                            <input class="form-check-input" type="checkbox" value="" id="folderlistCheckbox_4" checked>
                                            <label class="form-check-label" for="folderlistCheckbox_4"></label>
                                        </div>
                                        <div class="dropdown">
                                            <button class="btn btn-ghost-primary btn-icon btn-sm dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="ri-more-2-fill fs-16 align-bottom"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li><a class="dropdown-item view-item-btn" href="javascript:void(0);">Open</a></li>
                                                <li><a class="dropdown-item edit-folder-list" href="#createFolderModal" data-bs-toggle="modal" role="button">Rename</a></li>
                                                <li><a class="dropdown-item" href="#removeFolderModal" data-bs-toggle="modal" role="button">Delete</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="text-center">
                                        <div class="mb-2">
                                            <i class="ri-folder-2-fill align-bottom text-warning display-5"></i>
                                        </div>
                                        <h6 class="fs-15 folder-name">Velzon v1.7.0</h6>
                                    </div>
                                    <div class="hstack mt-4 text-muted">
                                        <span class="me-auto"><b>180</b> Files</span>
                                        <span><b>478.65</b>MB</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--end col-->
                    </div>
                    <!--end row-->
                </div>
                <div>
                    <div class="d-flex align-items-center mb-3">
                        <h5 class="flex-grow-1 fs-16 mb-0" id="filetype-title">Recent File</h5>
                    </div>
                    <div class="table-responsive">
                        <table class="table align-middle table-nowrap mb-0">
                            <thead class="table-active">
                                <tr>
                                    <th scope="col">Name</th>
                                    <th scope="col">File Item</th>
                                    <th scope="col">File Size</th>
                                    <th scope="col">Recent Date</th>
                                    <th scope="col" class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="file-list"></tbody>
                        </table>
                    </div>
                    <ul id="pagination" class="pagination pagination-lg"></ul>
                    <div class="align-items-center mt-2 row g-3 text-center text-sm-start">
                        <div class="col-sm">
                            <div class="text-muted">Showing<span class="fw-semibold">4</span> of <span class="fw-semibold">125</span> Results
                            </div>
                        </div>
                        <div class="col-sm-auto">
                            <ul class="pagination pagination-separated pagination-sm justify-content-center justify-content-sm-start mb-0">
                                <li class="page-item disabled">
                                    <a href="#" class="page-link">←</a>
                                </li>
                                <li class="page-item">
                                    <a href="#" class="page-link">1</a>
                                </li>
                                <li class="page-item active">
                                    <a href="#" class="page-link">2</a>
                                </li>
                                <li class="page-item">
                                    <a href="#" class="page-link">3</a>
                                </li>
                                <li class="page-item">
                                    <a href="#" class="page-link">→</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- START CREATE FILE MODAL -->
<div class="modal fade zoomIn" id="createFileModal" tabindex="-1" aria-labelledby="createFileModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0">
            <div class="modal-header p-3 bg-success-subtle">
                <h5 class="modal-title" id="createFileModalLabel">Create File</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" id="addFileBtn-close" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form autocomplete="off" class="needs-validation createfile-form" id="createfile-form" novalidate>
                    <div class="mb-3">
                        <div class="dropzone">
                            <div class="fallback">
                                <input name="file" type="file" multiple="multiple">
                            </div>
                            <div class="dz-message needsclick">
                                <div class="mb-3">
                                    <i class="display-4 text-muted ri-upload-cloud-2-fill"></i>
                                </div>

                                <h4>Drop files here or click to upload.</h4>
                            </div>
                        </div>
                        <ul class="list-unstyled pt-3" id="dropzone-preview"></ul>
                    </div>
                    <div class="hstack gap-2 justify-content-end">
                        <button type="button" class="btn btn-ghost-success" data-bs-dismiss="modal"><i class="ri-close-line align-bottom"></i> Close</button>
                        <button type="button" class="btn btn-info" id="resetForm">Reset</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php
$pageContent = ob_get_clean();

ob_start(); ?>
<!-- dropzone min -->
<script src="<?= home_url("assets/libs/dropzone/dropzone-min.js") ?>"></script>
<script type="text/javascript">
    $(document).ready(function() {
        var previewTemplate, dropzone, dropzonePreviewNode;

        Dropzone.autoDiscover = false;

        const myDropzone = new Dropzone(".dropzone", {
            url: "/api/file-manager/upload",
            maxFilesize: 2,
            paramName: "file",
            previewsContainer: "#dropzone-preview",
            previewTemplate: `
        <li class="list-group-item dz-preview dz-file-preview">
            <div class="d-flex justify-content-between align-items-center">
                <span class="dz-filename"><span data-dz-name></span></span>
                <span class="dz-size" data-dz-size></span>
                <button class="btn btn-danger btn-sm dz-remove" data-dz-remove>Remove</button>
            </div>
            <div class="progress mt-2">
                <div class="progress-bar" role="progressbar" data-dz-uploadprogress></div>
            </div>
        </li>
    `,
            dictDefaultMessage: `
        <div class="mb-3">
            <i class="display-4 text-muted ri-upload-cloud-2-fill"></i>
        </div>
        <h4>Drop files here or click to upload.</h4>
    `,
            success: function(file, response) {
                console.log("Successfully uploaded", response);
                $("#dropzone-preview .progress-bar").addClass("bg-success");
            },
            error: function(file, errorMessage) {
                console.error("Upload failed", errorMessage);
                $("#dropzone-preview .progress-bar").addClass("bg-danger");
            }
        });

        $(document).on('click', '#resetForm', function(e) {
            e.preventDefault();
            myDropzone.removeAllFiles(true);
        })
    });
</script>
<?php
$additionJs = ob_get_clean();

include 'layout.php';
