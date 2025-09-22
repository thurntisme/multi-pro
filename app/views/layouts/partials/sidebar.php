<!-- ========== App Menu ========== -->
<div class="app-menu navbar-menu">
    <!-- LOGO -->
    <div class="navbar-brand-box">
        <!-- Light Logo-->
        <a href="<?= App\Helpers\NetworkHelper::home_url('') ?>" class="logo logo-light">
            <span class="fw-bold text-white fs-22"><?= DEFAULT_SITE_NAME ?></span>
        </a>
        <!-- Dark Logo-->
        <a href="<?= App\Helpers\NetworkHelper::home_url('') ?>" class="logo logo-dark">
            <span class="fw-bold text-white fs-22"><?= DEFAULT_SITE_NAME ?></span>
        </a>
        <button type="button" class="btn btn-sm p-0 fs-20 header-item float-end btn-vertical-sm-hover"
            id="vertical-hover">
            <i class="ri-record-circle-line"></i>
        </button>
    </div>

    <div id="scrollbar">
        <div class="container-fluid">

            <div id="two-column-menu">
            </div>
            <ul class="navbar-nav" id="navbar-nav">
                <?php
                foreach (SIDEBAR_MENU_ITEMS as $category) {
                    echo '<li class="menu-title"><span>' . $category['title'] . '</span></li>';

                    foreach ($category['sub_items'] as $item) {
                        // $pageData = getPageData($item['slug'], $user_role);
                        $isLock = false;
                        // Generate active class based on the slug
                        $active_class = App\Helpers\NetworkHelper::activeClassName($item['slug']);

                        // Generate the URL dynamically based on the slug
                        $url = App\Helpers\NetworkHelper::home_url("app/{$item['slug']}");

                        echo '<li class="nav-item">
                                    <a class="nav-link menu-link ' . $active_class . '" href="' . $url . '">
                                        <i class="' . $item['icon'] . '"></i> <span>' . $item['name'] . '</span>
                                        ' . ($isLock ? '<i class="mdi mdi-lock ms-auto"></i>' : '') . '
                                    </a>
                                  </li>';
                    }
                }
                ?>
            </ul>
        </div>
        <!-- Sidebar -->
    </div>

    <div class="sidebar-background"></div>
</div>
<!-- Left Sidebar End -->
<!-- Vertical Overlay-->
<div class="vertical-overlay"></div>