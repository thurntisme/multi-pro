<?php
// require_once 'controllers/NotificationController.php';
// require_once 'controllers/SystemNotificationController.php';

// $user = new UserController();
// $user_fullName = $user->getUserFullName($user_id);
// $user_point = $user->getUserPoint($user_id);
// $fullName = !empty(trim($user_fullName)) ? $user_fullName : 'User';

// $file = 'error.log';
// $content = file_get_contents($file);
// $error_count = substr_count($content, 'PHP Warning');

// $notificationController = new NotificationController();
// $systemNotificationController = new SystemNotificationController();
// $notifications = $notificationController->newestNotifications($user_id);
// $systemNotiCount = $systemNotificationController->getSystemNotificationsUnreadCount();

$fullName = 'Admin';
$error_count = 0;
$notifications = [
    'newest' => [],
    'count' => 0
];
$user_point = 0;
$systemNotiCount = 0;

?>

<header id="page-topbar">

    <div class="layout-width">
        <div class="navbar-header">
            <div class="d-flex">
                <!-- LOGO -->
                <div class="navbar-brand-box horizontal-logo">
                    <a href="<?= App\Helpers\Network::home_url('') ?>" class="logo logo-dark">
                        <span class="fw-bold text-primary fs-22"><?= DEFAULT_SITE_NAME ?></span>
                    </a>
                </div>

                <button type="button" class="btn btn-sm px-3 fs-16 header-item vertical-menu-btn topnav-hamburger"
                    id="topnav-hamburger-icon">
                    <span class="hamburger-icon">
                        <span></span>
                        <span></span>
                        <span></span>
                    </span>
                </button>

                <?php include_once LAYOUTS_PATH . 'partials/top-bar/app-search.php'; ?>
            </div>

            <div class="d-flex align-items-center">

                <?php include_once LAYOUTS_PATH . 'partials/top-bar/app-search-mb.php'; ?>
                <?php include_once LAYOUTS_PATH . 'partials/top-bar/language-switcher.php'; ?>
                <?php include_once LAYOUTS_PATH . 'partials/top-bar/quick-apps.php'; ?>
                <?php include_once LAYOUTS_PATH . 'partials/top-bar/action-buttons.php'; ?>
                <?php include_once LAYOUTS_PATH . 'partials/top-bar/system-notification.php'; ?>
                <?php include_once LAYOUTS_PATH . 'partials/top-bar/system-error.php'; ?>
                <?php include_once LAYOUTS_PATH . 'partials/top-bar/user-notification.php'; ?>
                <?php include_once LAYOUTS_PATH . 'partials/top-bar/user-dropdown.php'; ?>

            </div>
        </div>
    </div>
</header>