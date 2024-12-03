<?php
$pageTitle = "Dashboard";

require_once DIR . '/functions/system.php';
// log_message('INFO', 'User logged in.', ['user_id' => 123]);

$checklist = DEFAULT_DASHBOARD_OPTIONS['checklist'];
$event = DEFAULT_DASHBOARD_OPTIONS['event'];
$links = DEFAULT_DASHBOARD_OPTIONS['links'];

$user = new UserController();
$user_fullName = $user->getUserFullName($user_id);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($_POST['action_name'])) {
        if ($_POST['action_name'] === 'check_in_out') {
            if (isUserCheckIn()) {
                checkOut();
            } else {
                checkIn();
            }
        }
    }
}

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
                                <h5 class="mb-1 card-title"><?= $user_fullName ?></h5>
                                <p class="mb-0 text-muted">Founder</p>
                            </div>

                            <div class="flex-shrink-0 dropdown ms-2">
                                <a class="btn btn-light btn-sm" href="<?= home_url("settings") ?>">
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
                                <form action="<?= $_SERVER['REQUEST_URI'] ?>" method="POST">
                                    <input type="hidden" name="action_name" value="check_in_out">
                                    <button type="submit"
                                        class="btn btn-success btn-label right ms-auto"><i
                                            class="ri-arrow-right-line label-icon align-bottom fs-16 ms-2"></i>
                                        Check <?= isUserCheckIn() ? 'Out' : 'In' ?></button>
                                </form>
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
        <div class="card card-height-100">
            <div class="card-header align-items-center d-flex">
                <h5 class="card-title mb-0 flex-grow-1">Quick Links</h5>
            </div><!-- end card header -->
            <div class="card-body">
                <?php foreach ($links as $link): ?>
                    <div class="d-flex gap-2 align-items-center mb-2">
                        <div class="flex-shrink-0">
                            <img src="<?= home_url($link['logo']) ?>" alt="<?= $link['name'] ?>"
                                class="rounded avatar-xxs object-fit-contain">
                        </div>
                        <h6 class="mb-0 fs-14 flex-grow-1"><?= $link['name'] ?></h6>
                        <h6 class="flex-shrink-0 mb-0">
                            <a href="<?= $link['url'] ?>" target="_blank" rel="noreferrer"
                                class="btn btn-link btn-sm">Visit Website <i
                                    class="ri-arrow-right-line align-bottom"></i>
                            </a>
                        </h6>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <div class="col-xl-4">
        <div class="card card-height-100">
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
                        <?php foreach ($checklist as $idx => $item):
                            $deadline = date('d M, Y', strtotime($item['deadline']));
                            $isToday = date('d M, Y') === $deadline;
                        ?>
                            <li class="list-group-item ps-0">
                                <div class="d-flex align-items-start">
                                    <div class="form-check ps-0 flex-sharink-0">
                                        <input type="checkbox" class="form-check-input ms-0"
                                            id="task_<?= $idx ?>" <?= $item['isDone'] ? 'checked' : '' ?>>
                                    </div>
                                    <div class="flex-grow-1">
                                        <label class="form-check-label mb-0 ps-2"
                                            for="task_<?= $idx ?>"><?= $item['title'] ?></label>
                                        <?php if ($isToday) { ?>
                                            <span class="badge rounded-pill bg-info-subtle text-info">Focus</span>
                                        <?php } ?>
                                    </div>
                                    <div class="flex-shrink-0 ms-2">
                                        <p class="text-muted fs-12 mb-0"><?= !$isToday ? $deadline : 'Today'; ?></p>
                                    </div>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ul><!-- end ul -->
                </div>
            </div><!-- end card body -->
        </div><!-- end card -->
    </div>
    <div class="col-xl-6">
        <div class="card card-height-100">
            <div class="card-header align-items-center d-flex">
                <h4 class="card-title mb-0 flex-grow-1">Upcoming Release</h4>
            </div><!-- end card header -->
            <div class="card-body pt-0">
                <ul class="list-group list-group-flush border-dashed">
                    <?php foreach ($event as $idx => $item): ?>
                        <li class="list-group-item ps-0">
                            <div class="row align-items-center g-3">
                                <div class="col-auto">
                                    <div class="avatar-sm p-1 py-2 h-auto bg-light rounded-3">
                                        <div class="text-center">
                                            <h5 class="mb-0"><?= date('d', strtotime($item['date']));
                                                                ?></h5>
                                            <div class="text-muted"><?= date('M', strtotime($item['date']));
                                                                    ?></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col">
                                    <h4 class="text-reset fs-14 mb-0"><?= $item['title'] ?></h4>
                                    <h5 class="text-muted mt-0 mt-1 fs-13"><?= $item['description'] ?? '' ?></h5>
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
                                    </div>
                                </div>
                            </div>
                            <!-- end row -->
                        </li><!-- end -->
                    <?php endforeach; ?>
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
