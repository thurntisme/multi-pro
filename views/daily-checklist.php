<?php
$pageTitle = "Today Checklist";

ob_start();
?>

<div class="card card-height-100">

    <div class="card-body p-0">

        <div class="align-items-center p-3 justify-content-between d-flex">
            <div class="flex-shrink-0">
                <div class="text-muted"><span class="fw-semibold">4</span> of <span class="fw-semibold">10</span> remaining</div>
            </div>
            <button type="button" class="btn btn-sm btn-success"><i class="ri-add-line align-middle me-1"></i> Add Task</button>
        </div><!-- end card header -->

        <div data-simplebar style="max-height: 219px;">
            <ul class="list-group list-group-flush border-dashed px-3">
                <li class="list-group-item ps-0">
                    <div class="d-flex align-items-start">
                        <div class="form-check ps-0 flex-sharink-0">
                            <input type="checkbox" class="form-check-input ms-0" id="task_one">
                        </div>
                        <div class="flex-grow-1">
                            <label class="form-check-label mb-0 ps-2" for="task_one">Review and make sure nothing slips through cracks</label>
                        </div>
                        <div class="flex-shrink-0 ms-2">
                            <p class="text-muted fs-12 mb-0">15 Sep, 2021</p>
                        </div>
                    </div>
                </li>
                <li class="list-group-item ps-0">
                    <div class="d-flex align-items-start">
                        <div class="form-check ps-0 flex-sharink-0">
                            <input type="checkbox" class="form-check-input ms-0" id="task_two">
                        </div>
                        <div class="flex-grow-1">
                            <label class="form-check-label mb-0 ps-2" for="task_two">Send meeting invites for sales upcampaign</label>
                        </div>
                        <div class="flex-shrink-0 ms-2">
                            <p class="text-muted fs-12 mb-0">20 Sep, 2021</p>
                        </div>
                    </div>
                </li>
                <li class="list-group-item ps-0">
                    <div class="d-flex align-items-start">
                        <div class="form-check flex-sharink-0 ps-0">
                            <input type="checkbox" class="form-check-input ms-0" id="task_three">
                        </div>
                        <div class="flex-grow-1">
                            <label class="form-check-label mb-0 ps-2" for="task_three">Weekly closed sales won checking with sales team</label>
                        </div>
                        <div class="flex-shrink-0 ms-2">
                            <p class="text-muted fs-12 mb-0">24 Sep, 2021</p>
                        </div>
                    </div>
                </li>
                <li class="list-group-item ps-0">
                    <div class="d-flex align-items-start">
                        <div class="form-check ps-0 flex-sharink-0">
                            <input type="checkbox" class="form-check-input ms-0" id="task_four">
                        </div>
                        <div class="flex-grow-1">
                            <label class="form-check-label mb-0 ps-2" for="task_four">Add notes that can be viewed from the individual view</label>
                        </div>
                        <div class="flex-shrink-0 ms-2">
                            <p class="text-muted fs-12 mb-0">27 Sep, 2021</p>
                        </div>
                    </div>
                </li>
                <li class="list-group-item ps-0">
                    <div class="d-flex align-items-start">
                        <div class="form-check ps-0 flex-sharink-0">
                            <input type="checkbox" class="form-check-input ms-0" id="task_five">
                        </div>
                        <div class="flex-grow-1">
                            <label class="form-check-label mb-0 ps-2" for="task_five">Move stuff to another page</label>
                        </div>
                        <div class="flex-shrink-0 ms-2">
                            <p class="text-muted fs-12 mb-0">27 Sep, 2021</p>
                        </div>
                    </div>
                </li>
                <li class="list-group-item ps-0">
                    <div class="d-flex align-items-start">
                        <div class="form-check ps-0 flex-sharink-0">
                            <input type="checkbox" class="form-check-input ms-0" id="task_six">
                        </div>
                        <div class="flex-grow-1">
                            <label class="form-check-label mb-0 ps-2" for="task_six">Styling wireframe design and documentation for velzon admin</label>
                        </div>
                        <div class="flex-shrink-0 ms-2">
                            <p class="text-muted fs-12 mb-0">27 Sep, 2021</p>
                        </div>
                    </div>
                </li>
            </ul><!-- end ul -->
        </div>
        <div class="p-3 pt-2">
            <a href="javascript:void(0);" class="text-muted text-decoration-underline">Show more...</a>
        </div>
    </div><!-- end card body -->
</div>

<?php
$pageContent = ob_get_clean();
