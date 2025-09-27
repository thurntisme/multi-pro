<?php
ob_start();
?>

<div class="row g-4 mb-3">
    <div class="col-sm-auto">
        <div>
            <a href="<?= App\Helpers\Network::home_url('project/new') ?>" class="btn btn-success
                   "><i class="ri-add-line align-bottom me-1"></i>
                Add New</a>
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
    <div class="col-xxl-3 col-sm-6 project-card">
        <div class="card">
            <div class="card-body">
                <div class="p-3 mt-n3 mx-n3 bg-danger-subtle rounded-top">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h5 class="mb-0 fs-14"><a href="apps-projects-overview.html" class="text-body">Multipurpose
                                    landing template</a></h5>
                        </div>
                        <div class="flex-shrink-0">
                            <div class="d-flex gap-1 align-items-center my-n2">
                                <button type="button" class="btn avatar-xs p-0 favourite-btn active">
                                    <span class="avatar-title bg-transparent fs-15">
                                        <i class="ri-star-fill"></i>
                                    </span>
                                </button>
                                <div class="dropdown">
                                    <button class="btn btn-link text-muted p-1 mt-n1 py-0 text-decoration-none fs-15"
                                        data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                        <i data-feather="more-horizontal" class="icon-sm"></i>
                                    </button>

                                    <div class="dropdown-menu dropdown-menu-end">
                                        <a class="dropdown-item" href="apps-projects-overview.html"><i
                                                class="ri-eye-fill align-bottom me-2 text-muted"></i> View</a>
                                        <a class="dropdown-item" href="apps-projects-create.html"><i
                                                class="ri-pencil-fill align-bottom me-2 text-muted"></i> Edit</a>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                            data-bs-target="#removeProjectModal"><i
                                                class="ri-delete-bin-fill align-bottom me-2 text-muted"></i> Remove</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="py-3">
                    <div class="row gy-3">
                        <div class="col-6">
                            <div>
                                <p class="text-muted mb-1">Status</p>
                                <div class="badge bg-warning-subtle text-warning fs-12">Inprogress</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div>
                                <p class="text-muted mb-1">Deadline</p>
                                <h5 class="fs-14">18 Sep, 2021</h5>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-auto">
                    <div class="d-flex mb-2">
                        <div class="flex-grow-1">
                            <div>Tasks</div>
                        </div>
                        <div class="flex-shrink-0">
                            <div><i class="ri-list-check align-bottom me-1 text-muted"></i> 20/34</div>
                        </div>
                    </div>
                    <div class="progress progress-sm animated-progress">
                        <div class="progress-bar bg-success" role="progressbar" aria-valuenow="78" aria-valuemin="0"
                            aria-valuemax="100" style="width: 78%;"></div>
                    </div>
                </div>

            </div>
            <!-- end card body -->
            <div class="card-footer bg-transparent border-top-dashed py-2">
                <div class="d-flex align-items-center">
                    <p class="text-muted mb-0 me-2">Client :</p>
                    <div class="flex-grow-1">
                        <div class="avatar-group">
                            <a href="javascript: void(0);" class="avatar-group-item" data-bs-toggle="tooltip"
                                data-bs-trigger="hover" data-bs-placement="top" title="Kristin Turpin">
                                <div class="avatar-xxs">
                                    <div class="avatar-title rounded-circle bg-info">
                                        K
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                    <div class="flex-shrink-0">
                        <div class="text-muted">
                            <i class="ri-calendar-event-fill me-1 align-bottom"></i> Updated 3hrs ago
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>
</div>

<!-- removeProjectModal -->
<div id="removeProjectModal" class="modal fade zoomIn" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                    id="close-modal"></button>
            </div>
            <div class="modal-body">
                <div class="mt-2 text-center">
                    <lord-icon src="https://cdn.lordicon.com/gsqxdxog.json" trigger="loop"
                        colors="primary:#f7b84b,secondary:#f06548" style="width:100px;height:100px"></lord-icon>
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
</div><!-- /.modal -->

<?php
$pageContent = ob_get_clean();

include_once LAYOUTS_PATH . 'dashboard.php';