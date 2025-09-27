<div class="dropdown ms-1 topbar-head-dropdown header-item">
    <button type="button" class="btn btn-icon btn-topbar btn-ghost-secondary rounded-circle" data-bs-toggle="dropdown"
        aria-haspopup="true" aria-expanded="false">
        <img src="<?= App\Helpers\Network::home_url("assets/images/flags/" . $cur_lang . ".svg") ?>"
            alt="Header Language" height="20" class="rounded">
    </button>
    <div class="dropdown-menu dropdown-menu-end">
        <?php foreach (App\Constants\Settings::SUPPORT_LANGUAGES as $key => $value) { ?>
            <a href="javascript:void(0);" class="dropdown-item notify-item language py-2" data-lang="<?= $key ?>"
                title="<?= $value ?>">
                <img src="<?= App\Helpers\Network::home_url("assets/images/flags/" . $key . ".svg") ?>"
                    alt="<?= $value ?>-flag" class="me-2 rounded" height="18">
                <span class="align-middle"><?= $value ?></span>
            </a>
        <?php } ?>
    </div>
</div>