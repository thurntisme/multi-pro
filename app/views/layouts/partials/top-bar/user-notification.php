<div class="dropdown topbar-head-dropdown ms-1 header-item" id="notificationDropdown">
    <button type="button" class="btn btn-icon btn-topbar btn-ghost-secondary rounded-circle"
        id="page-header-notifications-dropdown" data-bs-toggle="dropdown" data-bs-auto-close="outside"
        aria-haspopup="true" aria-expanded="false">
        <i class='bx bx-bell fs-22'></i>
        <?php if ($notifications['count'] > 0) { ?>
            <span
                class="position-absolute topbar-badge fs-10 translate-middle badge rounded-pill bg-danger"><?= $notifications['count'] ?><span
                    class="visually-hidden">unread messages</span></span>
        <?php } ?>
    </button>
    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0"
        aria-labelledby="page-header-notifications-dropdown">

        <div class="dropdown-head bg-primary bg-pattern rounded-top">
            <div class="p-3">
                <div class="row align-items-center">
                    <div class="col">
                        <h6 class="m-0 fs-16 fw-semibold text-white"> Notifications </h6>
                    </div>
                    <div class="col-auto dropdown-tabs">
                        <?php if ($notifications['count'] > 0) { ?>
                            <span class="badge bg-light-subtle text-body fs-13">
                                <?= $notifications['count'] ?> New</span>
                        <?php } ?>
                    </div>
                </div>
            </div>

            <div class="px-2 pt-2">
                <ul class="nav nav-tabs dropdown-tabs nav-tabs-custom" data-dropdown-tabs="true"
                    id="notificationItemsTab" role="tablist">
                    <li class="nav-item waves-effect waves-light">
                        <a class="nav-link active" data-bs-toggle="tab" href="#all-noti-tab" role="tab"
                            aria-selected="true">
                            All
                        </a>
                    </li>
                    <li class="nav-item waves-effect waves-light">
                        <a class="nav-link" data-bs-toggle="tab" href="#messages-tab" role="tab" aria-selected="false">
                            Messages
                        </a>
                    </li>
                    <li class="nav-item waves-effect waves-light">
                        <a class="nav-link" data-bs-toggle="tab" href="#alerts-tab" role="tab" aria-selected="false">
                            Alerts
                        </a>
                    </li>
                </ul>
            </div>

        </div>

        <div class="tab-content position-relative" id="notificationItemsTabContent">
            <div class="tab-pane fade show active py-2 ps-2" id="all-noti-tab" role="tabpanel">
                <div data-simplebar style="max-height: 300px;" class="pe-2">
                    <?php foreach ($notifications['newest'] as $noti) { ?>
                        <div class="text-reset notification-item d-block dropdown-item position-relative">
                            <div class="d-flex">
                                <img src="<?= App\Helpers\NetworkHelper::home_url("assets/images/users/avatar-8.jpg") ?>"
                                    class="me-3 rounded-circle avatar-xs flex-shrink-0" alt="user-pic">
                                <div class="flex-grow-1">
                                    <a href="#!" class="stretched-link">
                                        <h6 class="mt-0 mb-1 fs-13 fw-semibold"><?= $noti['title'] ?></h6>
                                    </a>
                                    <div class="fs-13 text-muted">
                                        <p class="mb-1"><?= $noti['message'] ?></p>
                                    </div>
                                    <p class="mb-0 fs-11 fw-medium text-uppercase text-muted">
                                        <span><i class="mdi mdi-clock-outline"></i>
                                            <?= $systemController->convertDateTime($noti['created_at']) ?></span>
                                    </p>
                                </div>
                                <div class="px-2 fs-15">
                                    <?php if ($noti['is_read'] === 0) { ?>
                                        <div class="form-check notification-check">
                                            <input class="form-check-input notification-check" type="checkbox" value=""
                                                data-idx="<?= $noti['id'] ?>" id="all-notification-check<?= $noti['id'] ?>">
                                            <label class="form-check-label"
                                                for="all-notification-check<?= $noti['id'] ?>"></label>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    <?php } ?>

                    <div class="my-3 text-center view-all">
                        <?php if ($systemNotiCount > 0) { ?>
                            <button type="button" class="btn btn-soft-success waves-effect waves-light">
                                View
                                All Notifications <i class="ri-arrow-right-line align-middle"></i>
                            </button>
                        <?php } else { ?>
                            <p class="text-bold">There is not any notifications.</p>
                        <?php } ?>
                    </div>
                </div>

            </div>

            <div class="tab-pane fade py-2 ps-2" id="messages-tab" role="tabpanel" aria-labelledby="messages-tab">
                <div data-simplebar style="max-height: 300px;" class="pe-2">
                    <div class="text-reset notification-item d-block dropdown-item">
                        <div class="d-flex">
                            <img src="<?= App\Helpers\NetworkHelper::home_url("assets/images/users/avatar-3.jpg") ?>"
                                class="me-3 rounded-circle avatar-xs" alt="user-pic">
                            <div class="flex-grow-1">
                                <a href="#!" class="stretched-link">
                                    <h6 class="mt-0 mb-1 fs-13 fw-semibold">James Lemire</h6>
                                </a>
                                <div class="fs-13 text-muted">
                                    <p class="mb-1">We talked about a project on linkedin.</p>
                                </div>
                                <p class="mb-0 fs-11 fw-medium text-uppercase text-muted">
                                    <span><i class="mdi mdi-clock-outline"></i> 30 min ago</span>
                                </p>
                            </div>
                            <div class="px-2 fs-15">
                                <div class="form-check notification-check">
                                    <input class="form-check-input" type="checkbox" value=""
                                        id="messages-notification-check01">
                                    <label class="form-check-label" for="messages-notification-check01"></label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="text-reset notification-item d-block dropdown-item">
                        <div class="d-flex">
                            <img src="<?= App\Helpers\NetworkHelper::home_url("assets/images/users/avatar-2.jpg") ?>"
                                class="me-3 rounded-circle avatar-xs" alt="user-pic">
                            <div class="flex-grow-1">
                                <a href="#!" class="stretched-link">
                                    <h6 class="mt-0 mb-1 fs-13 fw-semibold">Angela Bernier</h6>
                                </a>
                                <div class="fs-13 text-muted">
                                    <p class="mb-1">Answered to your comment on the cash flow forecast's
                                        graph 🔔.</p>
                                </div>
                                <p class="mb-0 fs-11 fw-medium text-uppercase text-muted">
                                    <span><i class="mdi mdi-clock-outline"></i> 2 hrs ago</span>
                                </p>
                            </div>
                            <div class="px-2 fs-15">
                                <div class="form-check notification-check">
                                    <input class="form-check-input" type="checkbox" value=""
                                        id="messages-notification-check02">
                                    <label class="form-check-label" for="messages-notification-check02"></label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="text-reset notification-item d-block dropdown-item">
                        <div class="d-flex">
                            <img src="<?= App\Helpers\NetworkHelper::home_url("assets/images/users/avatar-6.jpg") ?>"
                                class="me-3 rounded-circle avatar-xs" alt="user-pic">
                            <div class="flex-grow-1">
                                <a href="#!" class="stretched-link">
                                    <h6 class="mt-0 mb-1 fs-13 fw-semibold">Kenneth Brown</h6>
                                </a>
                                <div class="fs-13 text-muted">
                                    <p class="mb-1">Mentionned you in his comment on 📃 invoice #12501.
                                    </p>
                                </div>
                                <p class="mb-0 fs-11 fw-medium text-uppercase text-muted">
                                    <span><i class="mdi mdi-clock-outline"></i> 10 hrs ago</span>
                                </p>
                            </div>
                            <div class="px-2 fs-15">
                                <div class="form-check notification-check">
                                    <input class="form-check-input" type="checkbox" value=""
                                        id="messages-notification-check03">
                                    <label class="form-check-label" for="messages-notification-check03"></label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="text-reset notification-item d-block dropdown-item">
                        <div class="d-flex">
                            <img src="<?= App\Helpers\NetworkHelper::home_url("assets/images/users/avatar-8.jpg") ?>"
                                class="me-3 rounded-circle avatar-xs" alt="user-pic">
                            <div class="flex-grow-1">
                                <a href="#!" class="stretched-link">
                                    <h6 class="mt-0 mb-1 fs-13 fw-semibold">Maureen Gibson</h6>
                                </a>
                                <div class="fs-13 text-muted">
                                    <p class="mb-1">We talked about a project on linkedin.</p>
                                </div>
                                <p class="mb-0 fs-11 fw-medium text-uppercase text-muted">
                                    <span><i class="mdi mdi-clock-outline"></i> 3 days ago</span>
                                </p>
                            </div>
                            <div class="px-2 fs-15">
                                <div class="form-check notification-check">
                                    <input class="form-check-input" type="checkbox" value=""
                                        id="messages-notification-check04">
                                    <label class="form-check-label" for="messages-notification-check04"></label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="my-3 text-center view-all">
                        <button type="button" class="btn btn-soft-success waves-effect waves-light">View
                            All Messages <i class="ri-arrow-right-line align-middle"></i></button>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade p-4" id="alerts-tab" role="tabpanel" aria-labelledby="alerts-tab">
            </div>

            <div class="notification-actions" id="notification-actions">
                <div class="d-flex text-muted justify-content-center">
                    Select
                    <div id="select-content" class="text-body fw-semibold px-1">0</div>
                    Result
                    <button type="button" class="btn btn-link link-danger p-0 ms-3" data-bs-toggle="modal"
                        data-bs-target="#removeNotificationModal">Remove
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>