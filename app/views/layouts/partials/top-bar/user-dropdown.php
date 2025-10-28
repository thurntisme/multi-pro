<div class="dropdown ms-sm-3 header-item topbar-user">
    <button type="button" class="btn" id="page-header-user-dropdown" data-bs-toggle="dropdown" aria-haspopup="true"
        aria-expanded="false">
        <span class="d-flex align-items-center">
            <img class="rounded-circle header-profile-user"
                src="<?= App\Helpers\Network::home_url("assets/images/users/user-dummy-img.jpg") ?>"
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
        <a class="dropdown-item" href="<?= App\Helpers\Network::home_url('app/profile') ?>"><i
                class="mdi mdi-account-circle text-muted fs-16 align-middle me-1"></i> <span
                class="align-middle">Profile</span></a>
        <a class="dropdown-item" href="<?= App\Helpers\Network::home_url('app/settings') ?>"><i
                class="mdi mdi-cog-outline text-muted fs-16 align-middle me-1"></i> <span class="align-middle">
                Settings</span></a>
        <div class="dropdown-divider"></div>
        <a class="dropdown-item" href="<?= App\Helpers\Network::home_url('logout') ?>"><i
                class="mdi mdi-logout text-muted fs-16 align-middle me-1"></i> <span class="align-middle"
                data-key="t-logout">Logout</span></a>
    </div>
</div>