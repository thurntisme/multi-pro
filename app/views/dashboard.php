<?php
$user_fullName = 'admin';
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
                                <a class="btn btn-light btn-sm"
                                    href="<?= App\Helpers\NetworkHelper::home_url("settings") ?>">
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
                            <p class="fs-16 lh-base">Upgrade your plan from a <span class="fw-semibold">Free
                                    trial</span>, to ‘Premium Plan’ <i class="mdi mdi-arrow-right"></i></p>
                            <div class="mt-3">
                                <a href="<?= App\Helpers\NetworkHelper::home_url('app/pricing') ?>"
                                    class="btn btn-info btn-label right ms-auto"><i
                                        class="ri-arrow-right-line label-icon align-bottom fs-16 ms-2"></i>
                                    Upgrade my account</a>
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
                <h4 class="card-title mb-0 flex-grow-1">Quick Apps</h4>
            </div><!-- end card header -->
            <div class="card-body p-1 d-flex justify-content-center align-items-center">
                <div class="row g-0 flex-wrap">
                    <?php foreach (DEFAULT_QUICK_APPS as $app): ?>
                        <div class="col-4">
                            <a class="dropdown-icon-item"
                                href="<?= App\Helpers\NetworkHelper::home_url('app/' . $app['slug']) ?>">
                                <img src="<?= App\Helpers\NetworkHelper::home_url("assets/images/app/" . $app['slug'] . '.png') ?>"
                                    alt="<?= $app['title'] ?> Icon">
                                <span><?= $app['title'] ?></span>
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div><!-- end card body -->
        </div>
    </div>
</div>
<div class="row">
    <div class="col-xl-4">
        <div class="card">
            <div class="card-header align-items-center d-flex">
                <h4 class="card-title mb-0 flex-grow-1">Today Activities</h4>
            </div><!-- end card header -->
            <div class="card-body pt-0">
                <ul class="list-group list-group-flush border-dashed">
                    <?php foreach ($events as $item): ?>
                        <li class="list-group-item ps-0">
                            <div class="row align-items-center g-3">
                                <div class="col-auto">
                                    <div class="avatar-sm p-1 py-2 h-auto bg-light rounded-3">
                                        <div class="text-center">
                                            <h5 class="mb-0"><?= date('d'); ?></h5>
                                            <div class="text-muted"><?= date('M'); ?></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col">
                                    <h5 class="text-muted mt-0 mb-1 fs-13">
                                        <?= $item['start_time'] . ' - ' . $item['end_time'] ?>
                                    </h5>
                                    <p class="text-reset fs-14 mb-0"><?= $item['content'] ?></p>
                                </div>
                                <div class="col-sm-auto">
                                    <?php if ($item['status'] === 'Processing') { ?>
                                        <button class="btn btn-outline-primary btn-load border-0">
                                            <span class="d-flex align-items-center">
                                                <span class="spinner-border flex-shrink-0" role="status">
                                                    <span class="visually-hidden">Processing...</span>
                                                </span>
                                                <span class="flex-grow-1 ms-2">Processing...</span>
                                            </span>
                                        </button>
                                    <?php } ?>
                                    <?php if ($item['status'] === 'Passed') { ?>
                                        <button type="button"
                                            class="btn btn-success btn-label waves-effect waves-light rounded-pill">
                                            <i class="ri-check-double-line label-icon align-middle rounded-pill fs-16 me-2"></i>
                                            Passed
                                        </button>
                                    <?php } ?>
                                </div>
                            </div>
                            <!-- end row -->
                        </li><!-- end -->
                    <?php endforeach; ?>
                </ul><!-- end -->
            </div><!-- end card body -->
        </div>
        <div class="card">
            <div class="card-header align-items-center d-flex">
                <h4 class="card-title mb-0 flex-grow-1">My Activities</h4>
            </div><!-- end card header -->
            <div class="card-body p-0">
                <?php if ($isUserCheckIn) { ?>
                    <div class="p-3">
                        <p class="fs-16 lh-base">🎉 Check in now to earn points and unlock rewards! 🏆</p>
                        <form action="<?= $_SERVER['REQUEST_URI'] ?>" method="POST">
                            <input type="hidden" name="action_name" value="check_in_out">
                            <button type="submit" class="btn btn-success btn-label right ms-auto"><i
                                    class="ri-arrow-right-line label-icon align-bottom fs-16 ms-2"></i>
                                Check In
                            </button>
                        </form>
                    </div>
                <?php } else { ?>
                    <div data-simplebar style="max-height: 328px;">
                        <ul class="list-group list-group-flush border-dashed px-3">
                            <?php foreach (array_reverse($user_logs) as $idx => $item): ?>
                                <li class="list-group-item ps-0">
                                    <div class="d-flex align-items-start">
                                        <div class="d-flex flex-grow-1 align-items-center">
                                            <?= get_log_badge($item['level']) . $item['message'] ?>
                                        </div>
                                        <div class="flex-shrink-0 ms-2">
                                            <p class="text-muted fs-12 mb-0">
                                                <?= $commonController->convertTime($item['timestamp']); ?>
                                            </p>
                                        </div>
                                    </div>
                                </li>
                            <?php endforeach; ?>
                        </ul><!-- end ul -->
                    </div>
                <?php } ?>
            </div><!-- end card body -->
        </div>
    </div>
    <div class="col-xl-8">
        <div class="card">
            <div class="card-header border-0">
                <h4 class="card-title mb-0">Upcoming Schedules</h4>
            </div><!-- end cardheader -->
            <div class="card-body pt-0">
                <div class="upcoming-scheduled">
                    <input type="text" class="form-control" data-provider="flatpickr" data-date-format="d M, Y"
                        data-deafult-date="today" data-inline-date="true">
                </div>
                <div class="event-list">
                    <h6 class="text-uppercase fw-semibold mt-4 mb-3 text-muted">Events:</h6>
                    <div class="mini-stats-wid d-flex align-items-center mt-3">
                        <div class="flex-shrink-0 avatar-sm">
                            <span
                                class="mini-stat-icon avatar-title rounded-circle text-success bg-success-subtle fs-4">
                                09
                            </span>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1">Development planning</h6>
                            <p class="text-muted mb-0">iTest Factory </p>
                        </div>
                        <div class="flex-shrink-0">
                            <p class="text-muted mb-0">9:20 <span class="text-uppercase">am</span></p>
                        </div>
                    </div><!-- end -->
                    <div class="mini-stats-wid d-flex align-items-center mt-3">
                        <div class="flex-shrink-0 avatar-sm">
                            <span
                                class="mini-stat-icon avatar-title rounded-circle text-success bg-success-subtle fs-4">
                                12
                            </span>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1">Design new UI and check sales</h6>
                            <p class="text-muted mb-0">Meta4Systems</p>
                        </div>
                        <div class="flex-shrink-0">
                            <p class="text-muted mb-0">11:30 <span class="text-uppercase">am</span></p>
                        </div>
                    </div><!-- end -->
                    <div class="mini-stats-wid d-flex align-items-center mt-3">
                        <div class="flex-shrink-0 avatar-sm">
                            <span
                                class="mini-stat-icon avatar-title rounded-circle text-success bg-success-subtle fs-4">
                                25
                            </span>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1">Weekly catch-up </h6>
                            <p class="text-muted mb-0">Nesta Technologies</p>
                        </div>
                        <div class="flex-shrink-0">
                            <p class="text-muted mb-0">02:00 <span class="text-uppercase">pm</span></p>
                        </div>
                    </div><!-- end -->
                    <div class="mini-stats-wid d-flex align-items-center mt-3">
                        <div class="flex-shrink-0 avatar-sm">
                            <span
                                class="mini-stat-icon avatar-title rounded-circle text-success bg-success-subtle fs-4">
                                27
                            </span>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1">James Bangs (Client) Meeting</h6>
                            <p class="text-muted mb-0">Nesta Technologies</p>
                        </div>
                        <div class="flex-shrink-0">
                            <p class="text-muted mb-0">03:45 <span class="text-uppercase">pm</span></p>
                        </div>
                    </div><!-- end -->

                    <div class="mt-3 text-center">
                        <a href="<?= App\Helpers\NetworkHelper::home_url('app/calendar') ?>>"
                            class="text-muted text-decoration-underline">View
                            all
                            Events</a>
                    </div>
                </div>
            </div><!-- end cardbody -->
        </div>
    </div>
</div>

<?php
$pageContent = ob_get_clean();

require LAYOUTS_PATH . 'dashboard.php';