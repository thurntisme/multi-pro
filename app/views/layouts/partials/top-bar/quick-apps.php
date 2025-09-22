<div class="dropdown topbar-head-dropdown ms-1 header-item">
    <button type="button" class="btn btn-icon btn-topbar btn-ghost-secondary rounded-circle" data-bs-toggle="dropdown"
        aria-haspopup="true" aria-expanded="false">
        <i class='bx bx-category-alt fs-22'></i>
    </button>
    <div class="dropdown-menu dropdown-menu-lg p-0 dropdown-menu-end">
        <div class="p-3 border-top-0 border-start-0 border-end-0 border-dashed border">
            <div class="row align-items-center">
                <div class="col">
                    <h6 class="m-0 fw-semibold fs-15"> Web Apps </h6>
                </div>
            </div>
        </div>

        <div class="p-2">
            <div class="row g-0 flex-wrap">
                <?php foreach (App\Constants\Dashboard::QUICK_APPS as $slug => $title): ?>
                    <div class="col-4">
                        <a class="dropdown-icon-item" href="<?= App\Helpers\NetworkHelper::home_url('app/' . $slug) ?>">
                            <img src="<?= App\Helpers\NetworkHelper::home_url("assets/images/app/" . $slug . '.png') ?>"
                                alt="<?= $title ?> Icon">
                            <span><?= $title ?></span>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>