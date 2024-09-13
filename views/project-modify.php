<?php
$pageTitle = "Project";
$modify_type = getLastSegmentFromUrl();

require_once 'controllers/ProjectController.php';
$projectController = new ProjectController();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($modify_type === "create") {
        $projectController->createProject();
    }
    if ($modify_type === "edit") {
        $projectController->updateProject();
    }
};
$projectData = '';
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['post_id'])) {
        $post_id = $_GET['post_id'];
        $projectData = $projectController->viewProject($post_id);
    }
}

ob_start();

echo '<link href="' . home_url('assets/libs/dropzone/dropzone.css') . '" rel="stylesheet" type="text/css" />';
$additionCss = ob_get_clean();

ob_start();

if (isset($_SESSION['message'])) {
    $messageType = $_SESSION['message_type'] ?? 'info';
    echo '<div class="alert alert-' . $_SESSION['message_type'] . ' alert-dismissible fade show" role="alert">
                <i class="ri-' . ($_SESSION['message_type'] === "success" ? "check-double" : "error-warning") . '-line me-3 align-middle fs-16 text-' . $_SESSION['message_type'] . '"></i><strong>' . $_SESSION['message'] . '</strong>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>';

    unset($_SESSION['message']);
    unset($_SESSION['message_type']);
}
$action = $modify_type === "create" ? $modify_type : ($modify_type . "?post_id=" . $_GET['post_id']);
echo '<form method="post" action="' . home_url("projects/" . $action) . '">
        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label" for="project-title-input">Project Title</label>
                            <input type="text" class="form-control" name="title" id="project-title-input" placeholder="Enter project title" value="' . ($projectData['title'] ?? "") . '">
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="project-thumbnail-img">Thumbnail Image</label>
                            <input class="form-control" id="project-thumbnail-img" type="file" accept="image/png, image/gif, image/jpeg">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Project Description</label>
                            <div id="ckeditor-classic" name="description">
                                ' . ($projectData['description'] ?? "") . '
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-3">';
$typeOptions = '';
foreach (DEFAULT_PROJECT_TYPES as $type) {
    $selected = 'owner' === $type[0] ? 'selected' : '';
    if (isset($projectData['type'])) {
        $selected = $projectData['type'] === $type[0] ? 'selected' : '';
    }
    $typeOptions .= '<option value="' . htmlspecialchars($type[0]) . '" ' . $selected . '>' .
        htmlspecialchars($type[1]) .
        '</option>';
}
echo '<div class="mb-3 mb-lg-0">
                                        <label for="choices-types-input" class="form-label">Type</label>
                                        <select class="form-select" name="type" data-choices data-choices-search-false id="choices-types-input">
                                            ' . $typeOptions . '
                                        </select>
                                    </div>';
echo '</div>';
if ($modify_type == 'edit') {
    echo '<div class="col-lg-3">';
    echo '<input type="hidden" name="project_id" value="' . $_GET['post_id'] . '" />';

    $statusOptions = '';
    foreach (DEFAULT_PROJECT_STATUS as $status) {
        $selected = 'not_started' === $status[0] ? 'selected' : '';
        if (isset($projectData['status'])) {
            $selected = $projectData['status'] === $status[0] ? 'selected' : '';
        }
        $statusOptions .= '<option value="' . htmlspecialchars($status[0]) . '" ' . $selected . '>' .
            htmlspecialchars($status[1]) .
            '</option>';
    }
    echo '<div class="mb-3 mb-lg-0">
                                        <label for="choices-status-input" class="form-label">Status</label>
                                        <select class="form-select" name="status" data-choices data-choices-search-false id="choices-status-input">
                                            ' . $statusOptions . '
                                        </select>
                                    </div>';
    echo '</div>';
}
echo '<div class="col-lg-3">
                                <div>
                                    <label for="datepicker-start-date-input" class="form-label">Start Date</label>
                                    <input type="text" name="start_date" class="form-control" id="datepicker-start-date-input" placeholder="Enter Start Date" data-provider="flatpickr" value="' . ($projectData['start_date'] ?? "") . '">
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div>
                                    <label for="datepicker-end-date-input" class="form-label">End Date</label>
                                    <input type="text" name="end_date" class="form-control" id="datepicker-end-date-input" placeholder="Enter End Date" data-provider="flatpickr" value="' . ($projectData['end_date'] ?? "") . '">
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- end card body -->
                </div>
                <!-- end card -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Links</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label" for="project-title-input">Dev Url</label>
                            <input type="text" class="form-control" name="dev_url" placeholder="Enter development url" value="' . ($projectData['dev_url'] ?? "") . '">
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="project-title-input">Staging Url</label>
                            <input type="text" class="form-control" name="staging_url" placeholder="Enter staging url" value="' . ($projectData['staging_url'] ?? "") . '">
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="project-title-input">Production Url</label>
                            <input type="text" class="form-control" name="production_url" placeholder="Enter production url" value="' . ($projectData['production_url'] ?? "") . '">
                        </div>
                        <hr/>
                        <div class="mb-3">
                            <label class="form-label" for="project-title-input">Source Url</label>
                            <input type="text" class="form-control" name="source_url" placeholder="Enter source url" value="' . ($projectData['source_url'] ?? "") . '">
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Attached files</h5>
                    </div>
                    <div class="card-body">
                        <div>
                            <p class="text-muted">Add Attached files here.</p>

                            <div class="dropzone">
                                <div class="fallback">
                                    <input name="file" type="file" multiple="multiple">
                                </div>
                                <div class="dz-message needsclick">
                                    <div class="mb-3">
                                        <i class="display-4 text-muted ri-upload-cloud-2-fill"></i>
                                    </div>

                                    <h5>Drop files here or click to upload.</h5>
                                </div>
                            </div>

                            <ul class="list-unstyled mb-0" id="dropzone-preview">
                                <li class="mt-2" id="dropzone-preview-list">
                                    <!-- This is used as the file preview template -->
                                    <div class="border rounded">
                                        <div class="d-flex p-2">
                                            <div class="flex-shrink-0 me-3">
                                                <div class="avatar-sm bg-light rounded">
                                                    <img src="#" alt="Project-Image" data-dz-thumbnail class="img-fluid rounded d-block" />
                                                </div>
                                            </div>
                                            <div class="flex-grow-1">
                                                <div class="pt-1">
                                                    <h5 class="fs-14 mb-1" data-dz-name>&nbsp;</h5>
                                                    <p class="fs-13 text-muted mb-0" data-dz-size></p>
                                                    <strong class="error text-danger" data-dz-errormessage></strong>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                            <!-- end dropzon-preview -->
                        </div>
                    </div>
                </div>
                <!-- end card -->
                <div class="text-center mb-4">
                    <a href="' . home_url("projects/list") . '" class="btn btn-light w-sm">Back</a>
                    <!--<button type="submit" class="btn btn-secondary w-sm">Draft</button>-->
                    <button type="submit" class="btn btn-success w-sm">' . ($modify_type === "create" ? "Create" : "Save") . '</button>
                </div>
            </div>
            <!-- end col -->
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Privacy</h5>
                    </div>
                    <div class="card-body">
                        <div>
                            <label for="choices-privacy-status-input" class="form-label">Status</label>
                            <select class="form-select" data-choices data-choices-search-false id="choices-privacy-status-input">
                                <option value="Private" selected>Private</option>
                                <option value="Team">Team</option>
                                <option value="Public">Public</option>
                            </select>
                        </div>
                    </div>
                    <!-- end card body -->
                </div>
                <!-- end card -->

                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Tags</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="choices-categories-input" class="form-label">Categories</label>
                            <select class="form-select" data-choices data-choices-search-false id="choices-categories-input">
                                <option value="Designing" selected>Designing</option>
                                <option value="Development">Development</option>
                            </select>
                        </div>

                        <div>
                            <label for="choices-text-input" class="form-label">Skills</label>
                            <input class="form-control" id="choices-text-input" data-choices data-choices-limit="Required Limit" placeholder="Enter Skills" type="text" value="UI/UX, Figma, HTML, CSS, Javascript, C#, Nodejs" />
                        </div>
                    </div>
                    <!-- end card body -->
                </div>
                <!-- end card -->

                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Members</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="choices-lead-input" class="form-label">Team Lead</label>
                            <select class="form-select" data-choices data-choices-search-false id="choices-lead-input">
                                <option value="Brent Gonzalez" selected>Brent Gonzalez</option>
                                <option value="Darline Williams">Darline Williams</option>
                                <option value="Sylvia Wright">Sylvia Wright</option>
                                <option value="Ellen Smith">Ellen Smith</option>
                                <option value="Jeffrey Salazar">Jeffrey Salazar</option>
                                <option value="Mark Williams">Mark Williams</option>
                            </select>
                        </div>

                        <div>
                            <label class="form-label">Team Members</label>
                            <div class="avatar-group">
                                <a href="javascript: void(0);" class="avatar-group-item" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Brent Gonzalez">
                                    <div class="avatar-xs">
                                        <img src="assets/images/users/avatar-3.jpg" alt="" class="rounded-circle img-fluid">
                                    </div>
                                </a>
                                <a href="javascript: void(0);" class="avatar-group-item" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Sylvia Wright">
                                    <div class="avatar-xs">
                                        <div class="avatar-title rounded-circle bg-secondary">
                                            S
                                        </div>
                                    </div>
                                </a>
                                <a href="javascript: void(0);" class="avatar-group-item" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Ellen Smith">
                                    <div class="avatar-xs">
                                        <img src="assets/images/users/avatar-4.jpg" alt="" class="rounded-circle img-fluid">
                                    </div>
                                </a>
                                <a href="javascript: void(0);" class="avatar-group-item" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Add Members">
                                    <div class="avatar-xs" data-bs-toggle="modal" data-bs-target="#inviteMembersModal">
                                        <div class="avatar-title fs-16 rounded-circle bg-light border-dashed border text-primary">
                                            +
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                    <!-- end card body -->
                </div>
                <!-- end card -->
            </div>
            <!-- end col -->
        </div>
    </form>';

echo '<!-- Modal -->
    <div class="modal fade" id="inviteMembersModal" tabindex="-1" aria-labelledby="inviteMembersModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header p-3 ps-4 bg-success-subtle">
                    <h5 class="modal-title" id="inviteMembersModalLabel">Members</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="search-box mb-3">
                        <input type="text" class="form-control bg-light border-light" placeholder="Search here...">
                        <i class="ri-search-line search-icon"></i>
                    </div>

                    <div class="mb-4 d-flex align-items-center">
                        <div class="me-2">
                            <h5 class="mb-0 fs-13">Members :</h5>
                        </div>
                        <div class="avatar-group justify-content-center">
                            <a href="javascript: void(0);" class="avatar-group-item" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Brent Gonzalez">
                                <div class="avatar-xs">
                                    <img src="assets/images/users/avatar-3.jpg" alt="" class="rounded-circle img-fluid">
                                </div>
                            </a>
                            <a href="javascript: void(0);" class="avatar-group-item" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Sylvia Wright">
                                <div class="avatar-xs">
                                    <div class="avatar-title rounded-circle bg-secondary">
                                        S
                                    </div>
                                </div>
                            </a>
                            <a href="javascript: void(0);" class="avatar-group-item" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Ellen Smith">
                                <div class="avatar-xs">
                                    <img src="assets/images/users/avatar-4.jpg" alt="" class="rounded-circle img-fluid">
                                </div>
                            </a>
                        </div>
                    </div>
                    <div class="mx-n4 px-4" data-simplebar style="max-height: 225px;">
                        <div class="vstack gap-3">
                            <div class="d-flex align-items-center">
                                <div class="avatar-xs flex-shrink-0 me-3">
                                    <img src="assets/images/users/avatar-2.jpg" alt="" class="img-fluid rounded-circle">
                                </div>
                                <div class="flex-grow-1">
                                    <h5 class="fs-13 mb-0"><a href="#" class="text-body d-block">Nancy Martino</a></h5>
                                </div>
                                <div class="flex-shrink-0">
                                    <button type="button" class="btn btn-light btn-sm">Add</button>
                                </div>
                            </div>
                            <!-- end member item -->
                            <div class="d-flex align-items-center">
                                <div class="avatar-xs flex-shrink-0 me-3">
                                    <div class="avatar-title bg-danger-subtle text-danger rounded-circle">
                                        HB
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <h5 class="fs-13 mb-0"><a href="#" class="text-body d-block">Henry Baird</a></h5>
                                </div>
                                <div class="flex-shrink-0">
                                    <button type="button" class="btn btn-light btn-sm">Add</button>
                                </div>
                            </div>
                            <!-- end member item -->
                            <div class="d-flex align-items-center">
                                <div class="avatar-xs flex-shrink-0 me-3">
                                    <img src="assets/images/users/avatar-3.jpg" alt="" class="img-fluid rounded-circle">
                                </div>
                                <div class="flex-grow-1">
                                    <h5 class="fs-13 mb-0"><a href="#" class="text-body d-block">Frank Hook</a></h5>
                                </div>
                                <div class="flex-shrink-0">
                                    <button type="button" class="btn btn-light btn-sm">Add</button>
                                </div>
                            </div>
                            <!-- end member item -->
                            <div class="d-flex align-items-center">
                                <div class="avatar-xs flex-shrink-0 me-3">
                                    <img src="assets/images/users/avatar-4.jpg" alt="" class="img-fluid rounded-circle">
                                </div>
                                <div class="flex-grow-1">
                                    <h5 class="fs-13 mb-0"><a href="#" class="text-body d-block">Jennifer Carter</a></h5>
                                </div>
                                <div class="flex-shrink-0">
                                    <button type="button" class="btn btn-light btn-sm">Add</button>
                                </div>
                            </div>
                            <!-- end member item -->
                            <div class="d-flex align-items-center">
                                <div class="avatar-xs flex-shrink-0 me-3">
                                    <div class="avatar-title bg-success-subtle text-success rounded-circle">
                                        AC
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <h5 class="fs-13 mb-0"><a href="#" class="text-body d-block">Alexis Clarke</a></h5>
                                </div>
                                <div class="flex-shrink-0">
                                    <button type="button" class="btn btn-light btn-sm">Add</button>
                                </div>
                            </div>
                            <!-- end member item -->
                            <div class="d-flex align-items-center">
                                <div class="avatar-xs flex-shrink-0 me-3">
                                    <img src="assets/images/users/avatar-7.jpg" alt="" class="img-fluid rounded-circle">
                                </div>
                                <div class="flex-grow-1">
                                    <h5 class="fs-13 mb-0"><a href="#" class="text-body d-block">Joseph Parker</a></h5>
                                </div>
                                <div class="flex-shrink-0">
                                    <button type="button" class="btn btn-light btn-sm">Add</button>
                                </div>
                            </div>
                            <!-- end member item -->
                        </div>
                        <!-- end list -->
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light w-xs" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-success w-xs">Invite</button>
                </div>
            </div>
            <!-- end modal-content -->
        </div>
        <!-- modal-dialog -->
    </div>
    <!-- end modal -->';

$pageContent = ob_get_clean();

ob_start();
echo '<!-- ckeditor -->
    <script src="' . home_url('assets/libs/@ckeditor/ckeditor5-build-classic/build/ckeditor.js') . ' "></script>

    <!-- dropzone js -->
    <script src="' . home_url('assets/libs/dropzone/dropzone-min.js') . ' "></script>
    <!-- project-create init -->
    <script src="' . home_url('assets/js/pages/project-create.init.js') . ' "></script>';

$additionJs = ob_get_clean();

include 'layout.php';
