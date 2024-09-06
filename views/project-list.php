<?php
$pageTitle = "Project List";

require_once 'controllers/ProjectController.php';
$projectController = new ProjectController();
$projectLists = $projectController->listProjects();

ob_start();

$projectItems = '';

foreach ($projectLists as $project) {
    $projectItems .= '
    <div class="col-xxl-3 col-sm-6 project-card">
        <div class="card card-height-100">
            <div class="card-body">
                <div class="d-flex flex-column h-100">
                    <div class="d-flex">
                        <div class="flex-grow-1">
                            <p class="text-muted mb-1">Updated ' . timeAgo($project['updated_at']) . '</p>
                        </div>
                        <div class="flex-shrink-0">
                            <div class="d-flex gap-1 align-items-center">
                                <!--<button type="button" class="btn avatar-xs mt-n1 p-0 favourite-btn">
                                    <span class="avatar-title bg-transparent fs-15">
                                        <i class="ri-star-fill"></i>
                                    </span>
                                </button>-->
                                <div class="dropdown">
                                    <button class="btn btn-link text-muted p-1 mt-n2 py-0 text-decoration-none fs-15" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                        <i data-feather="more-horizontal" class="icon-sm"></i>
                                    </button>

                                    <div class="dropdown-menu dropdown-menu-end">
                                        <a class="dropdown-item" href="' . home_url("projects/detail?post_id") . $project['id'] . '"><i class="ri-eye-fill align-bottom me-2 text-muted"></i> View</a>
                                        <a class="dropdown-item" href="' . home_url("projects/edit?post_id=") . $project['id'] . '"><i class="ri-pencil-fill align-bottom me-2 text-muted"></i> Edit</a>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#removeProjectModal"><i class="ri-delete-bin-fill align-bottom me-2 text-muted"></i> Remove</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-3">
                        <h5 class="mb-3 fs-14"><a href="' . home_url("projects/detail?post_id=") . $project['id'] . '" class="text-body">' . $project['title'] . '</a></h5>
                        <div class="row gy-3">
                            <div class="col-6">
                                <div>
                                    <p class="text-muted mb-1">Status</p>
                                    ' . renderStatusBadge($project['status']) . '
                                </div>
                            </div>
                            <div class="col-6">
                                <div>
                                    <p class="text-muted mb-1">Deadline</p>
                                    <h5 class="fs-14">' . (!empty($project['end_date']) ? $commonController->convertDate($project['end_date']) : "Unset") . '</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex align-items-center mt-3">
                        <!--<p class="text-muted mb-0 me-2">Team :</p>
                        <div class="avatar-group">
                            <a href="javascript: void(0);" class="avatar-group-item" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Brent Gonzalez">
                                <div class="avatar-xxs">
                                    <img src="assets/images/users/avatar-3.jpg" alt="" class="rounded-circle img-fluid">
                                </div>
                            </a>
                            <a href="javascript: void(0);" class="avatar-group-item" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Sylvia Wright">
                                <div class="avatar-xxs">
                                    <div class="avatar-title rounded-circle bg-secondary">
                                        S
                                    </div>
                                </div>
                            </a>
                            <a href="javascript: void(0);" class="avatar-group-item" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Ellen Smith">
                                <div class="avatar-xxs">
                                    <img src="assets/images/users/avatar-4.jpg" alt="" class="rounded-circle img-fluid">
                                </div>
                            </a>
                            <a href="javascript: void(0);" class="avatar-group-item" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Add Members">
                                <div class="avatar-xxs">
                                    <div class="avatar-title fs-16 rounded-circle bg-light border-dashed border text-primary">
                                        +
                                    </div>
                                </div>
                            </a>
                        </div>-->
                    </div>
                </div>

            </div>
            <!-- end card body -->
            <div class="card-footer bg-transparent border-top-dashed py-3">
                <div class="d-flex mb-2">
                    <div class="flex-grow-1">
                        <div>Tasks</div>
                    </div>
                    <div class="flex-shrink-0">
                        <div><i class="ri-list-check align-bottom me-1 text-muted"></i> 18/42</div>
                    </div>
                </div>
                <div class="progress progress-sm animated-progress">
                    <div class="progress-bar bg-success" role="progressbar" aria-valuenow="34" aria-valuemin="0" aria-valuemax="100" style="width: 34%;"></div><!-- /.progress-bar -->
                </div><!-- /.progress -->
            </div>
            <!-- end card footer -->
        </div>
        <!-- end card -->
    </div>
    <!-- end col -->';
}

echo '<div class="row g-4 mb-3">
        <div class="col-sm-auto">
            <div>
                <a href="' . home_url("projects/create") . '" class="btn btn-success"><i class="ri-add-line align-bottom me-1"></i> Add New</a>
            </div>
        </div>
        <div class="col-sm">
            <div class="d-flex justify-content-sm-end gap-2">
                <div class="search-box ms-2">
                    <input type="text" class="form-control" placeholder="Search...">
                    <i class="ri-search-line search-icon"></i>
                </div>

                <select class="form-control w-md" data-choices data-choices-search-false>
                    <option value="All">All</option>
                    <option value="Today">Today</option>
                    <option value="Yesterday" selected>Yesterday</option>
                    <option value="Last 7 Days">Last 7 Days</option>
                    <option value="Last 30 Days">Last 30 Days</option>
                    <option value="This Month">This Month</option>
                    <option value="Last Year">Last Year</option>
                </select>
            </div>
        </div>
    </div>

    <div class="row">        
        ' . $projectItems . '
    </div>
    <!-- end row -->

    <div class="row g-0 text-center text-sm-start align-items-center mb-4">
        <div class="col-sm-6">
            <div>
                <p class="mb-sm-0 text-muted">Showing <span class="fw-semibold">1</span> to <span class="fw-semibold">10</span> of <span class="fw-semibold text-decoration-underline">12</span> entries</p>
            </div>
        </div>
        <!-- end col -->
        <div class="col-sm-6">
            <ul class="pagination pagination-separated justify-content-center justify-content-sm-end mb-sm-0">
                <li class="page-item disabled">
                    <a href="#" class="page-link">Previous</a>
                </li>
                <li class="page-item active">
                    <a href="#" class="page-link">1</a>
                </li>
                <li class="page-item ">
                    <a href="#" class="page-link">2</a>
                </li>
                <li class="page-item">
                    <a href="#" class="page-link">3</a>
                </li>
                <li class="page-item">
                    <a href="#" class="page-link">4</a>
                </li>
                <li class="page-item">
                    <a href="#" class="page-link">5</a>
                </li>
                <li class="page-item">
                    <a href="#" class="page-link">Next</a>
                </li>
            </ul>
        </div><!-- end col -->
</div><!-- end row -->';

echo '<!-- removeProjectModal -->
    <div id="removeProjectModal" class="modal fade zoomIn" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="close-modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mt-2 text-center">
                        <lord-icon src="https://cdn.lordicon.com/gsqxdxog.json" trigger="loop" colors="primary:#f7b84b,secondary:#f06548" style="width:100px;height:100px"></lord-icon>
                        <div class="mt-4 pt-2 fs-15 mx-4 mx-sm-5">
                            <h4>Are you Sure ?</h4>
                            <p class="text-muted mx-4 mb-0">Are you Sure You want to Remove this Project ?</p>
                        </div>
                    </div>
                    <div class="d-flex gap-2 justify-content-center mt-4 mb-2">
                        <button type="button" class="btn w-sm btn-light" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn w-sm btn-danger" id="remove-project">Yes, Delete It!</button>
                    </div>
                </div>

            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->';

$pageContent = ob_get_clean();

ob_start();
echo '<!-- project list init -->
    <script src="' . home_url('assets/js/pages/project-list.init.js') . '"></script>

    <!-- App js -->
    <script src="' . home_url('assets/js/app.js') . '"></script>';

$additionJs = ob_get_clean();

include 'layout.php';
