<!-- ========== App Menu ========== -->
<div class="app-menu navbar-menu">
    <!-- LOGO -->
    <div class="navbar-brand-box">
        <!-- Dark Logo-->
        <a href="index.php" class="logo logo-dark">
            <span class="logo-sm">
                <img src="<?= home_url("assets/images/logo-sm.png") ?>" alt="" height="22">
            </span>
            <span class="logo-lg">
                <img src="<?= home_url("assets/images/logo-dark.png") ?>" alt="" height="17">
            </span>
        </a>
        <!-- Light Logo-->
        <a href="index.php" class="logo logo-light">
            <span class="logo-sm">
                <img src="<?= home_url("assets/images/logo-sm.png") ?>" alt="" height="22">
            </span>
            <span class="logo-lg">
                <img src="<?= home_url("assets/images/logo-light.png") ?>" alt="" height="17">
            </span>
        </a>
        <button type="button" class="btn btn-sm p-0 fs-20 header-item float-end btn-vertical-sm-hover" id="vertical-hover">
            <i class="ri-record-circle-line"></i>
        </button>
    </div>

    <div id="scrollbar">
        <div class="container-fluid">

            <div id="two-column-menu">
            </div>
            <ul class="navbar-nav" id="navbar-nav">
                <li class="menu-title"><span data-key="t-menu">Menu</span></li>
                <li class="nav-item">
                    <a class="nav-link menu-link <?= activeClassName("") ?>" href="<?= home_url("") ?>">
                        <i class="ri-dashboard-2-line"></i> <span data-key="t-dashboard">Dashboard</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link menu-link <?= activeClassName("todo") ?>" href="<?= home_url("todo") ?>">
                        <i class="ri-task-line"></i> <span data-key="t-todo">To Do</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link menu-link <?= activeClassName("daily-checklist") ?>" href="<?= home_url("daily-checklist") ?>">
                        <i class="ri-calendar-check-line"></i> <span data-key="t-daily-checklist">Daily Checklist</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link menu-link <?= activeClassName("projects") ?>" href="#sidebarProjects" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarProjects">
                        <i class="ri-folder-5-line"></i> <span data-key="t-projects">Projects</span>
                    </a>
                    <div class="collapse menu-dropdown <?= activeClassName("projects", "show", true) ?>" id="sidebarProjects">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="<?= home_url("projects/overview") ?>" class="nav-link <?= activeClassName("projects/overview") ?>" data-key="t-overview"> Overview </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?= home_url("projects/list") ?>" class="nav-link <?= activeClassName("projects/list") ?>" data-key="t-list"> List </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?= home_url("projects/create") ?>" class="nav-link <?= activeClassName("projects/create") ?>" data-key="t-create-project"> Create Project </a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link menu-link <?= activeClassName("finance") ?>" href="#sidebarFinance" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarFinance">
                        <i class="ri-wallet-3-line"></i> <span data-key="t-finance">Finance</span>
                    </a>
                    <div class="collapse menu-dropdown <?= activeClassName("finance", "show", true) ?>" id="sidebarFinance">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="<?= home_url("finance/overview") ?>" class="nav-link <?= activeClassName("finance") ?>" data-key="t-overview"> Overview </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?= home_url("finance/budget") ?>" class="nav-link <?= activeClassName("finance/budget") ?>" data-key="t-budget"> Budget </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?= home_url("finance/income") ?>" class="nav-link <?= activeClassName("finance/income") ?>" data-key="t-income"> Income </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?= home_url("finance/expenses") ?>" class="nav-link <?= activeClassName("finance/expenses") ?>" data-key="t-expense"> Expense </a>
                            </li>
                        </ul>
                    </div>
                </li>

            </ul>
        </div>
        <!-- Sidebar -->
    </div>

    <div class="sidebar-background"></div>
</div>
<!-- Left Sidebar End -->
<!-- Vertical Overlay-->
<div class="vertical-overlay"></div>