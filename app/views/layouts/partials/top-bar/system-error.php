<div class="ms-1 header-item d-none d-sm-flex">
    <a href="<?= App\Helpers\Network::home_url('app/system-error') ?>"
        class="btn btn-icon btn-topbar btn-ghost-secondary rounded-circle light-dark-mode">
        <i class='bx bx-bug fs-22'></i>
        <?php if ($error_count > 0) { ?>
            <span
                class="position-absolute topbar-badge fs-10 translate-middle badge rounded-pill bg-danger"><?= $error_count ?><span
                    class="visually-hidden">unread messages</span></span>
        <?php } ?>
    </a>
</div>