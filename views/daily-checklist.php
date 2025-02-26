<?php
$pageTitle = "Today Checklist";

ob_start();

include_once DIR . "/components/alert.php";
?>
<div class="row mt-4">
    <div class="col-4">
        <form method="POST" action="<?= $_SERVER['REQUEST_URI'] ?>">
            <?php csrfInput() ?>
            <input type="hidden" name="action_name" value="create_daily_checklist">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-lg-3 d-flex">
                            <label for="title-input" class="form-label mb-0">Title</label>
                        </div>
                        <div class="col-lg-9">
                            <input type="text" class="form-control" id="title-input" name="title"
                                placeholder="Enter title" value="">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-lg-3 d-flex">
                            <label for="content" class="form-label mb-0">Content</label>
                        </div>
                        <div class="col-lg-9">
                            <textarea name="content" class="form-control" id="content" rows="3" placeholder="Enter content"></textarea>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-3">
                        </div>
                        <div class="col-lg-9">
                            <div class="text-center">
                                <button type="submit" class="btn btn-success w-sm">Add to checklist</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        <div class="card mt-3">
            <div class="card-body p-0">
                <div class="align-items-center p-3 justify-content-between d-flex">
                    <div class="flex-shrink-0">
                        <div class="text-muted">Recommended Checklist</div>
                    </div>
                </div><!-- end card header -->
                <div data-simplebar style="max-height: 226px;">
                    <ul class="list-group list-group-flush border-dashed px-3">
                        <li class="list-group-item ps-0">
                            <div class="d-flex align-items-start">
                                <div class="flex-grow-1">
                                    <label class="form-check-label mb-0 ps-2" for="task_one">Review and make sure nothing slips through cracks</label>
                                </div>
                                <div class="flex-shrink-0 ms-2">
                                    <button class="btn btn-sm btn-info">Add Item</button>
                                </div>
                            </div>
                        </li>
                    </ul><!-- end ul -->
                </div>
            </div><!-- end card body -->
        </div>
    </div>
    <div class="col-8">
        <div class="card card-height-100">
            <div class="card-header align-items-center d-flex">
                <h4 class="card-title mb-0 flex-grow-1">Today Checklist</h4>
                <a class="btn btn-soft-primary btn-label waves-effect waves-light right ml-auto" href="<?= home_url('app/daily-checklist/all') ?>"><i class="ri-arrow-right-line label-icon align-middle fs-16 ms-2"></i> See All</a>
            </div>
            <div class="card-body">
                <div class="table-responsive table-card">
                    <table class="table table-hover table-nowrap align-middle mb-0">
                        <thead class="table-light">
                            <tr class="text-muted">
                                <th scope="col">No</th>
                                <th scope="col">Title</th>
                                <th scope="col">Content</th>
                                <th scope="col" class="text-center">Status</th>
                                <th scope="col">Update At</th>
                                <th scope="col" class="text-center"></th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr>
                                <td>01</td>
                                <td>23</td>
                                <td>157</td>
                                <td class="text-center"><span class="badge bg-success-subtle text-success text-uppercase">Completed</span></td>
                                <td>20 Sep, 2024</td>
                                <td class="text-center"><button class="btn btn-sm btn-secondary">Mark Incomplete</button></td>
                            </tr>
                            <tr>
                                <td>02</td>
                                <td>23</td>
                                <td>157</td>
                                <td class="text-center"><span class="badge bg-secondary-subtle text-secondary text-uppercase">Todo</span></td>
                                <td>20 Sep, 2024</td>
                                <td class="text-center"><button class="btn btn-sm btn-success">Mark Complete</button></td>
                            </tr>
                        </tbody><!-- end tbody -->
                    </table><!-- end table -->
                </div>
                <div class="align-items-center mt-3 row g-3 text-center text-sm-start">
                    <div class="col-sm">
                        <div class="text-muted"><span class="fw-semibold">4</span> of <span class="fw-semibold">10</span> remaining</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$pageContent = ob_get_clean();
