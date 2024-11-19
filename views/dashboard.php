<?php
$pageTitle = "Dashboard";

ob_start();
?>
    <div class="row">
        <div class="col-xl-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-shrink-0 me-3">
                            <img src="assets/images/users/avatar-1.jpg" alt=""
                                 class="avatar-sm rounded-circle img-thumbnail">
                        </div>
                        <div class="flex-grow-1">
                            <div class="d-flex">
                                <div class="flex-grow-1">
                                    <h5 class="mb-1 card-title">Anna Adame</h5>
                                    <p class="mb-0 text-muted">Founder</p>
                                </div>

                                <div class="flex-shrink-0 dropdown ms-2">
                                    <a class="btn btn-light btn-sm" href="<?= home_url("profile") ?>">
                                        <i class="bx bxs-cog align-middle me-1"></i> Setting
                                    </a>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-4">
                                    <div class="border p-2 rounded border-dashed">
                                        <p class="text-muted text-truncate mb-2">Total Post</p>
                                        <h5 class="mb-0">26</h5>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="border p-2 rounded border-dashed">
                                        <p class="text-muted text-truncate mb-2">Subscribes</p>
                                        <h5 class="mb-0">17k</h5>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="border p-2 rounded border-dashed">
                                        <p class="text-muted text-truncate mb-2">Viewers</p>
                                        <h5 class="mb-0">487k</h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-body p-0">
                    <div class="alert alert-warning border-0 rounded-top rounded-0 m-0 d-flex align-items-center"
                         role="alert">
                        <i data-feather="alert-triangle" class="text-warning me-2 icon-sm"></i>
                        <div class="flex-grow-1 text-truncate">
                            Your free trial expired in <b>17</b> days.
                        </div>
                        <div class="flex-shrink-0">
                            <a href="pages-pricing.html" class="text-reset text-decoration-underline"><b>Upgrade</b></a>
                        </div>
                    </div>

                    <div class="row align-items-end">
                        <div class="col-sm-8">
                            <div class="p-3">
                                <p class="fs-16 lh-base">Upgrade your plan from a <span
                                            class="fw-semibold">Free trial</span>, to ‘Premium Plan’ <i
                                            class="mdi mdi-arrow-right"></i></p>
                                <div class="mt-3">
                                    <a href="pages-pricing.html" class="btn btn-success">Upgrade Account!</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="px-3">
                                <img src="assets/images/user-illustarator-2.png" class="img-fluid" alt="">
                            </div>
                        </div>
                    </div>
                </div> <!-- end card-body-->
            </div>
        </div>
        <div class="col-xl-4">
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h5 class="card-title mb-0 flex-grow-1">Top Social Media Shares</h5>
                    <div class="flex-shrink-0">
                        <div class="dropdown card-header-dropdown">
                            <a class="text-reset dropdown-btn" href="#" data-bs-toggle="dropdown" aria-haspopup="true"
                               aria-expanded="false">
                                <span class="text-muted fs-16"><i class="mdi mdi-dots-vertical align-middle"></i></span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end">
                                <a class="dropdown-item" href="#">Today</a>
                                <a class="dropdown-item" href="#">Last Week</a>
                                <a class="dropdown-item" href="#">Last Month</a>
                                <a class="dropdown-item" href="#">Current Year</a>
                            </div>
                        </div>
                    </div>
                </div><!-- end card header -->
                <div class="card-body">
                    <div class="d-flex gap-2 align-items-center mb-2">
                        <div class="avatar-xs flex-shrink-0">
                            <div class="avatar-title bg-primary-subtle text-primary rounded-2 fs-17">
                                <i class="ri-facebook-box-fill"></i>
                            </div>
                        </div>
                        <h6 class="mb-0 fs-14 flex-grow-1">Facebook</h6>
                        <h6 class="flex-shrink-0 mb-0"><a href="#!" class="btn btn-link btn-sm">Visit Website <i
                                        class="ri-arrow-right-line align-bottom"></i></a></h6>
                    </div>
                    <div class="d-flex gap-2 align-items-center mb-2">
                        <div class="avatar-xs flex-shrink-0">
                            <div class="avatar-title bg-danger-subtle text-danger rounded-2 fs-17">
                                <i class="ri-google-line"></i>
                            </div>
                        </div>
                        <h6 class="mb-0 fs-14 flex-grow-1">Google</h6>
                        <h6 class="flex-shrink-0 mb-0">13k</h6>
                    </div>
                    <div class="d-flex gap-2 align-items-center mb-2">
                        <div class="avatar-xs flex-shrink-0">
                            <div class="avatar-title bg-success-subtle text-success rounded-2 fs-17">
                                <i class="ri-whatsapp-line"></i>
                            </div>
                        </div>
                        <h6 class="mb-0 fs-14 flex-grow-1">WhatsApp</h6>
                        <h6 class="flex-shrink-0 mb-0">11k</h6>
                    </div>
                    <div class="d-flex gap-2 align-items-center mb-2">
                        <div class="avatar-xs flex-shrink-0">
                            <div class="avatar-title bg-dark-subtle text-dark rounded-2 fs-17">
                                <i class="ri-invision-line"></i>
                            </div>
                        </div>
                        <h6 class="mb-0 fs-14 flex-grow-1">Invision</h6>
                        <h6 class="flex-shrink-0 mb-0">19k</h6>
                    </div>
                    <div class="d-flex gap-2 align-items-center mb-2">
                        <div class="avatar-xs flex-shrink-0">
                            <div class="avatar-title bg-danger-subtle text-danger rounded-2 fs-17">
                                <i class="ri-instagram-line"></i>
                            </div>
                        </div>
                        <h6 class="mb-0 fs-14 flex-grow-1">Instagram</h6>
                        <h6 class="flex-shrink-0 mb-0">18k</h6>
                    </div>
                    <div class="d-flex gap-2 align-items-center mb-2">
                        <div class="avatar-xs flex-shrink-0">
                            <div class="avatar-title bg-info-subtle text-info rounded-2 fs-17">
                                <i class="ri-telegram-2-line"></i>
                            </div>
                        </div>
                        <h6 class="mb-0 fs-14 flex-grow-1">Telegram</h6>
                        <h6 class="flex-shrink-0 mb-0">26k</h6>
                    </div>
                    <div class="d-flex gap-2 align-items-center">
                        <div class="avatar-xs flex-shrink-0">
                            <div class="avatar-title bg-secondary-subtle text-secondary rounded-2 fs-17">
                                <i class="ri-youtube-line"></i>
                            </div>
                        </div>
                        <h6 class="mb-0 fs-14 flex-grow-1">YouTube</h6>
                        <h6 class="flex-shrink-0 mb-0">9k</h6>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4">
            <div class="card">
                <div class="card-body p-0 pb-2">
                    <div class="w-100">
                        <div id="customer_impression_charts"
                             data-colors='["--vz-primary", "--vz-success", "--vz-danger"]' class="apex-charts"
                             dir="ltr"></div>
                    </div>
                </div><!-- end card body -->
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-6">
            <div class="card card-height-100">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">My Checklist</h4>
                </div><!-- end card header -->

                <div class="card-body p-0">

                    <div data-simplebar style="max-height: 328px;">
                        <ul class="list-group list-group-flush border-dashed px-3">
                            <li class="list-group-item ps-0">
                                <div class="d-flex align-items-start">
                                    <div class="form-check ps-0 flex-sharink-0">
                                        <input type="checkbox" class="form-check-input ms-0" id="task_one">
                                    </div>
                                    <div class="flex-grow-1">
                                        <label class="form-check-label mb-0 ps-2" for="task_one">Review and make sure
                                            nothing slips through cracks</label>
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
                                        <label class="form-check-label mb-0 ps-2" for="task_two">Send meeting invites
                                            for sales upcampaign</label>
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
                                        <label class="form-check-label mb-0 ps-2" for="task_three">Weekly closed sales
                                            won checking with sales team</label>
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
                                        <label class="form-check-label mb-0 ps-2" for="task_four">Add notes that can be
                                            viewed from the individual view</label>
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
                                        <label class="form-check-label mb-0 ps-2" for="task_five">Move stuff to another
                                            page</label>
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
                                        <label class="form-check-label mb-0 ps-2" for="task_six">Styling wireframe
                                            design and documentation for velzon admin</label>
                                    </div>
                                    <div class="flex-shrink-0 ms-2">
                                        <p class="text-muted fs-12 mb-0">27 Sep, 2021</p>
                                    </div>
                                </div>
                            </li>
                        </ul><!-- end ul -->
                    </div>
                </div><!-- end card body -->
            </div><!-- end card -->
        </div>
        <div class="col-xl-6">
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">Upcoming Release</h4>
                </div><!-- end card header -->
                <div class="card-body pt-0">
                    <ul class="list-group list-group-flush border-dashed">
                        <li class="list-group-item ps-0">
                            <div class="row align-items-center g-3">
                                <div class="col-auto">
                                    <div class="avatar-sm p-1 py-2 h-auto bg-light rounded-3">
                                        <div class="text-center">
                                            <h5 class="mb-0">25</h5>
                                            <div class="text-muted">Tue</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col">
                                    <h5 class="text-muted mt-0 mb-1 fs-13">12:00am - 03:30pm</h5>
                                    <a href="#" class="text-reset fs-14 mb-0">Meeting for campaign with sales team</a>
                                </div>
                                <div class="col-sm-auto">
                                    <div class="avatar-group">
                                        <div class="avatar-group-item">
                                            <a href="javascript: void(0);" class="d-inline-block"
                                               data-bs-toggle="tooltip" data-bs-placement="top" title=""
                                               data-bs-original-title="Stine Nielsen">
                                                <img src="assets/images/users/avatar-1.jpg" alt=""
                                                     class="rounded-circle avatar-xxs">
                                            </a>
                                        </div>
                                        <div class="avatar-group-item">
                                            <a href="javascript: void(0);" class="d-inline-block"
                                               data-bs-toggle="tooltip" data-bs-placement="top" title=""
                                               data-bs-original-title="Jansh Brown">
                                                <img src="assets/images/users/avatar-2.jpg" alt=""
                                                     class="rounded-circle avatar-xxs">
                                            </a>
                                        </div>
                                        <div class="avatar-group-item">
                                            <a href="javascript: void(0);" class="d-inline-block"
                                               data-bs-toggle="tooltip" data-bs-placement="top" title=""
                                               data-bs-original-title="Dan Gibson">
                                                <img src="assets/images/users/avatar-3.jpg" alt=""
                                                     class="rounded-circle avatar-xxs">
                                            </a>
                                        </div>
                                        <div class="avatar-group-item">
                                            <a href="javascript: void(0);">
                                                <div class="avatar-xxs">
                                                                    <span class="avatar-title rounded-circle bg-info text-white">
                                                                        5
                                                                    </span>
                                                </div>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- end row -->
                        </li><!-- end -->
                        <li class="list-group-item ps-0">
                            <div class="row align-items-center g-3">
                                <div class="col-auto">
                                    <div class="avatar-sm p-1 py-2 h-auto bg-light rounded-3">
                                        <div class="text-center">
                                            <h5 class="mb-0">20</h5>
                                            <div class="text-muted">Wed</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col">
                                    <h5 class="text-muted mt-0 mb-1 fs-13">02:00pm - 03:45pm</h5>
                                    <a href="#" class="text-reset fs-14 mb-0">Adding a new event with attachments</a>
                                </div>
                                <div class="col-sm-auto">
                                    <div class="avatar-group">
                                        <div class="avatar-group-item">
                                            <a href="javascript: void(0);" class="d-inline-block"
                                               data-bs-toggle="tooltip" data-bs-placement="top" title=""
                                               data-bs-original-title="Frida Bang">
                                                <img src="assets/images/users/avatar-4.jpg" alt=""
                                                     class="rounded-circle avatar-xxs">
                                            </a>
                                        </div>
                                        <div class="avatar-group-item">
                                            <a href="javascript: void(0);" class="d-inline-block"
                                               data-bs-toggle="tooltip" data-bs-placement="top" title=""
                                               data-bs-original-title="Malou Silva">
                                                <img src="assets/images/users/avatar-5.jpg" alt=""
                                                     class="rounded-circle avatar-xxs">
                                            </a>
                                        </div>
                                        <div class="avatar-group-item">
                                            <a href="javascript: void(0);" class="d-inline-block"
                                               data-bs-toggle="tooltip" data-bs-placement="top" title=""
                                               data-bs-original-title="Simon Schmidt">
                                                <img src="assets/images/users/avatar-6.jpg" alt=""
                                                     class="rounded-circle avatar-xxs">
                                            </a>
                                        </div>
                                        <div class="avatar-group-item">
                                            <a href="javascript: void(0);" class="d-inline-block"
                                               data-bs-toggle="tooltip" data-bs-placement="top" title=""
                                               data-bs-original-title="Tosh Jessen">
                                                <img src="assets/images/users/avatar-7.jpg" alt=""
                                                     class="rounded-circle avatar-xxs">
                                            </a>
                                        </div>
                                        <div class="avatar-group-item">
                                            <a href="javascript: void(0);">
                                                <div class="avatar-xxs">
                                                                    <span class="avatar-title rounded-circle bg-success text-white">
                                                                        3
                                                                    </span>
                                                </div>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- end row -->
                        </li><!-- end -->
                        <li class="list-group-item ps-0">
                            <div class="row align-items-center g-3">
                                <div class="col-auto">
                                    <div class="avatar-sm p-1 py-2 h-auto bg-light rounded-3">
                                        <div class="text-center">
                                            <h5 class="mb-0">17</h5>
                                            <div class="text-muted">Wed</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col">
                                    <h5 class="text-muted mt-0 mb-1 fs-13">04:30pm - 07:15pm</h5>
                                    <a href="#" class="text-reset fs-14 mb-0">Create new project Bundling Product</a>
                                </div>
                                <div class="col-sm-auto">
                                    <div class="avatar-group">
                                        <div class="avatar-group-item">
                                            <a href="javascript: void(0);" class="d-inline-block"
                                               data-bs-toggle="tooltip" data-bs-placement="top" title=""
                                               data-bs-original-title="Nina Schmidt">
                                                <img src="assets/images/users/avatar-8.jpg" alt=""
                                                     class="rounded-circle avatar-xxs">
                                            </a>
                                        </div>
                                        <div class="avatar-group-item">
                                            <a href="javascript: void(0);" class="d-inline-block"
                                               data-bs-toggle="tooltip" data-bs-placement="top" title=""
                                               data-bs-original-title="Stine Nielsen">
                                                <img src="assets/images/users/avatar-1.jpg" alt=""
                                                     class="rounded-circle avatar-xxs">
                                            </a>
                                        </div>
                                        <div class="avatar-group-item">
                                            <a href="javascript: void(0);" class="d-inline-block"
                                               data-bs-toggle="tooltip" data-bs-placement="top" title=""
                                               data-bs-original-title="Jansh Brown">
                                                <img src="assets/images/users/avatar-2.jpg" alt=""
                                                     class="rounded-circle avatar-xxs">
                                            </a>
                                        </div>
                                        <div class="avatar-group-item">
                                            <a href="javascript: void(0);">
                                                <div class="avatar-xxs">
                                                                    <span class="avatar-title rounded-circle bg-primary text-white">
                                                                        4
                                                                    </span>
                                                </div>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- end row -->
                        </li><!-- end -->
                        <li class="list-group-item ps-0">
                            <div class="row align-items-center g-3">
                                <div class="col-auto">
                                    <div class="avatar-sm p-1 py-2 h-auto bg-light rounded-3">
                                        <div class="text-center">
                                            <h5 class="mb-0">12</h5>
                                            <div class="text-muted">Tue</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col">
                                    <h5 class="text-muted mt-0 mb-1 fs-13">10:30am - 01:15pm</h5>
                                    <a href="#" class="text-reset fs-14 mb-0">Weekly closed sales won checking with
                                        sales team</a>
                                </div>
                                <div class="col-sm-auto">
                                    <div class="avatar-group">
                                        <div class="avatar-group-item">
                                            <a href="javascript: void(0);" class="d-inline-block"
                                               data-bs-toggle="tooltip" data-bs-placement="top" title=""
                                               data-bs-original-title="Stine Nielsen">
                                                <img src="assets/images/users/avatar-1.jpg" alt=""
                                                     class="rounded-circle avatar-xxs">
                                            </a>
                                        </div>
                                        <div class="avatar-group-item">
                                            <a href="javascript: void(0);" class="d-inline-block"
                                               data-bs-toggle="tooltip" data-bs-placement="top" title=""
                                               data-bs-original-title="Jansh Brown">
                                                <img src="assets/images/users/avatar-5.jpg" alt=""
                                                     class="rounded-circle avatar-xxs">
                                            </a>
                                        </div>
                                        <div class="avatar-group-item">
                                            <a href="javascript: void(0);" class="d-inline-block"
                                               data-bs-toggle="tooltip" data-bs-placement="top" title=""
                                               data-bs-original-title="Dan Gibson">
                                                <img src="assets/images/users/avatar-2.jpg" alt=""
                                                     class="rounded-circle avatar-xxs">
                                            </a>
                                        </div>
                                        <div class="avatar-group-item">
                                            <a href="javascript: void(0);">
                                                <div class="avatar-xxs">
                                                                    <span class="avatar-title rounded-circle bg-warning text-white">
                                                                        9
                                                                    </span>
                                                </div>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- end row -->
                        </li><!-- end -->
                    </ul><!-- end -->
                </div><!-- end card body -->
            </div>
        </div>
    </div>

<?php
$pageContent = ob_get_clean();


ob_start();
echo '
    <!-- apexcharts -->
    <script src="' . home_url("/assets/libs/apexcharts/apexcharts.min.js") . '"></script>

<script type="text/javascript">
      
var linechartcustomerColors = ["#405189", "#0ab39c", "#f06548"];
if (linechartcustomerColors) {
    var options = {
        series: [{
            name: "Orders",
            type: "area",
            data: [34, 65, 46, 68, 49, 61, 42, 44],
        },
        {
            name: "Earnings",
            type: "bar",
            data: [
                89.25, 98.58, 68.74, 108.87, 77.54, 84.03, 51.24,
            ],
        },
        {
            name: "Refunds",
            type: "line",
            data: [8, 12, 7, 17, 21, 11, 5],
        },
        ],
        chart: {
            height: 354,
            type: "line",
            toolbar: {
                show: false,
            },
        },
        stroke: {
            curve: "straight",
            dashArray: [0, 0, 8],
            width: [2, 0, 2.2],
        },
        fill: {
            opacity: [0.1, 0.9, 1],
        },
        markers: {
            size: [0, 0, 0],
            strokeWidth: 2,
            hover: {
                size: 4,
            },
        },
        xaxis: {
            categories: [
                "Jan",
                "Feb",
                "Mar",
                "Apr",
                "May",
                "Jun",
                "Jul",
            ],
            axisTicks: {
                show: false,
            },
            axisBorder: {
                show: false,
            },
        },
        grid: {
            show: true,
            xaxis: {
                lines: {
                    show: true,
                },
            },
            yaxis: {
                lines: {
                    show: false,
                },
            },
            padding: {
                top: 0,
                right: -2,
                bottom: 15,
                left: 10,
            },
        },
        legend: {
            show: true,
            horizontalAlign: "center",
            offsetX: 0,
            offsetY: -5,
            markers: {
                width: 9,
                height: 9,
                radius: 6,
            },
            itemMargin: {
                horizontal: 10,
                vertical: 0,
            },
        },
        plotOptions: {
            bar: {
                columnWidth: "30%",
                barHeight: "70%",
            },
        },
        colors: linechartcustomerColors,
        tooltip: {
            shared: true,
            y: [{
                formatter: function (y) {
                    if (typeof y !== "undefined") {
                        return y.toFixed(0);
                    }
                    return y;
                },
            },
            {
                formatter: function (y) {
                    if (typeof y !== "undefined") {
                        return "$" + y.toFixed(2) + "k";
                    }
                    return y;
                },
            },
            {
                formatter: function (y) {
                    if (typeof y !== "undefined") {
                        return y.toFixed(0) + " Sales";
                    }
                    return y;
                },
            },
            ],
        },
    };
    var chart = new ApexCharts(
        document.querySelector("#customer_impression_charts"),
        options
    );
    chart.render();
}

  </script>

    <!-- Dashboard init -->
  <script src="' . home_url("/assets/js/pages/finance.js") . '" type="text/javascript"></script>
  ';
$additionJs = ob_get_clean();

include 'layout.php';
