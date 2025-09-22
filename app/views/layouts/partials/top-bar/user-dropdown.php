<div class="dropdown ms-sm-3 header-item topbar-user">
    <button type="button" class="btn" id="page-header-user-dropdown" data-bs-toggle="dropdown" aria-haspopup="true"
        aria-expanded="false">
        <span class="d-flex align-items-center">
            <img class="rounded-circle header-profile-user"
                src="<?= App\Helpers\NetworkHelper::home_url("assets/images/users/user-dummy-img.jpg") ?>"
                alt="Header Avatar">
            <span class="text-start ms-xl-2">
                <span class="d-none d-xl-inline-block ms-1 fw-medium user-name-text"><?= $fullName ?></span>
                <span class="d-none d-xl-block ms-1 fs-12 user-name-sub-text">Founder</span>
            </span>
        </span>
    </button>
    <div class="dropdown-menu dropdown-menu-end">
        <!-- item-->
        <h6 class="dropdown-header">Welcome <b><?= $fullName ?></b>!</h6>
        <a class="dropdown-item" href="<?= App\Helpers\NetworkHelper::home_url('app/my-profile') ?>"><i
                class="mdi mdi-account-circle text-muted fs-16 align-middle me-1"></i> <span
                class="align-middle">Profile</span></a>
        <a class="dropdown-item" href="apps-chat"><i
                class="mdi mdi-message-text-outline text-muted fs-16 align-middle me-1"></i> <span
                class="align-middle">Messages</span></a>
        <a class="dropdown-item" href="apps-tasks-kanban"><i
                class="mdi mdi-calendar-check-outline text-muted fs-16 align-middle me-1"></i> <span
                class="align-middle">Taskboard</span></a>
        <a class="dropdown-item" href="pages-faqs"><i class="mdi mdi-lifebuoy text-muted fs-16 align-middle me-1"></i>
            <span class="align-middle">Help</span></a>
        <div class="dropdown-divider"></div>
        <a class="dropdown-item" href="<?= App\Helpers\NetworkHelper::home_url('app/wallet') ?>"><i
                class="mdi mdi-wallet text-muted fs-16 align-middle me-1"></i> <span
                class="align-middle"><b><?= $user_point ?></b></span></a>
        <a class="dropdown-item" href="<?= App\Helpers\NetworkHelper::home_url('app/my-settings') ?>"><i
                class="mdi mdi-cog-outline text-muted fs-16 align-middle me-1"></i> <span class="align-middle">My
                Settings</span></a>
        <a class="dropdown-item" href="auth-lockscreen-basic"><i
                class="mdi mdi-lock text-muted fs-16 align-middle me-1"></i> <span class="align-middle">Lock
                screen</span></a>
        <a class="dropdown-item" href="<?= App\Helpers\NetworkHelper::home_url('app/logout') ?>"><i
                class="mdi mdi-logout text-muted fs-16 align-middle me-1"></i> <span class="align-middle"
                data-key="t-logout">Logout</span></a>
    </div>
</div>