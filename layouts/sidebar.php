<!-- ========== App Menu ========== -->
<div class="app-menu navbar-menu">
    <!-- LOGO -->
    <div class="navbar-brand-box">
        <!-- Dark Logo-->
        <a href="<?= home_url('app/') ?>" class="logo logo-dark">
            <span class="logo-sm">
                <img src="<?= home_url("assets/images/logo-sm.png") ?>" alt="" height="22">
            </span>
            <span class="logo-lg">
                <img src="<?= home_url("assets/images/logo-dark.png") ?>" alt="" height="17">
            </span>
        </a>
        <!-- Light Logo-->
        <a href="<?= home_url('app/') ?>" class="logo logo-light">
            <span class="logo-sm">
                <img src="<?= home_url("assets/images/logo-sm.png") ?>" alt="" height="22">
            </span>
            <span class="logo-lg">
                <img src="<?= home_url("assets/images/logo-light.png") ?>" alt="" height="17">
            </span>
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
                <li class="menu-title"><span data-key="t-personal">Personal</span></li>
                <li class="nav-item">
                    <a class="nav-link menu-link <?= activeClassName("") ?>" href="<?= home_url("app/") ?>">
                        <i class="ri-dashboard-2-line"></i> <span data-key="t-dashboard">Dashboard</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link menu-link <?= activeClassName("todo") ?>" href="<?= home_url("app/todo") ?>">
                        <i class="ri-task-line"></i> <span data-key="t-todo">To Do</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link menu-link <?= activeClassName("note") ?>" href="<?= home_url("app/note") ?>">
                        <i class="ri-sticky-note-line"></i> <span data-key="t-note">Note</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link menu-link <?= activeClassName("daily-checklist") ?>"
                       href="<?= home_url("app/daily-checklist") ?>">
                        <i class="ri-calendar-check-line"></i> <span data-key="t-daily-checklist">Daily Checklist</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link menu-link <?= activeClassName("finance") ?>"
                       href="<?= home_url("app/finance") ?>">
                        <i class="ri-wallet-3-line"></i> <span data-key="t-finance">Finance</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link menu-link <?= activeClassName("subscription") ?>"
                       href="<?= home_url("app/subscription") ?>">
                        <i class="ri-calendar-check-line"></i> <span data-key="t-subscription">Subscription</span>
                    </a>
                </li>
                <li class="menu-title"><span data-key="t-tech-stack">Tech Stack</span></li>
                <li class="nav-item">
                    <a class="nav-link menu-link <?= activeClassName("wordpress") ?>"
                       href="<?= home_url("app/wordpress") ?>">
                        <i class="mdi mdi-wordpress"></i> <span data-key="t-wordpress">Wordpress</span>
                    </a>
                    <a class="nav-link menu-link <?= activeClassName("reactjs") ?>"
                       href="<?= home_url("app/reactjs") ?>">
                        <i class="mdi mdi-react"></i> <span>ReactJS</span>
                    </a>
                    <a class="nav-link menu-link <?= activeClassName("salesforce") ?>"
                       href="<?= home_url("app/salesforce") ?>">
                        <i class="mdi mdi-salesforce"></i> <span>Salesforce</span>
                    </a>
                    <a class="nav-link menu-link <?= activeClassName("php") ?>"
                       href="<?= home_url("app/php") ?>">
                        <i class="mdi mdi-language-php"></i> <span>PHP</span>
                    </a>
                    <a class="nav-link menu-link <?= activeClassName("mysql") ?>"
                       href="<?= home_url("app/mysql") ?>">
                        <i class="mdi mdi-database-outline"></i> <span>MySql</span>
                    </a>
                    <a class="nav-link menu-link <?= activeClassName("english") ?>"
                       href="<?= home_url("app/english") ?>">
                        <i class="mdi mdi-google-translate"></i> <span>English</span>
                    </a>
                </li>
                <li class="menu-title"><span data-key="t-app">App</span></li>
                <li class="nav-item">
                    <a class="nav-link menu-link <?= activeClassName("bookmark") ?>"
                       href="<?= home_url("app/bookmark") ?>">
                        <i class="ri-bookmark-line"></i> <span data-key="t-bookmark">Bookmark</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link menu-link <?= activeClassName("short-link") ?>"
                       href="<?= home_url("app/short-link") ?>">
                        <i class="ri-link-m"></i> <span data-key="t-short-link">Short Link</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link menu-link <?= activeClassName("generate-qr") ?>"
                       href="<?= home_url("app/generate-qr") ?>">
                        <i class="ri-qr-code-line"></i> <span data-key="t-generate-qr">Generate QR</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link menu-link <?= activeClassName("ai-chat") ?>"
                       href="<?= home_url("app/ai-chat") ?>">
                        <i class="ri-chat-3-line"></i> <span data-key="t-ai-chat">AI Chat</span>
                    </a>
                </li>
                <li class="menu-title"><span data-key="t-work">Work</span></li>
                <li class="nav-item">
                    <a class="nav-link menu-link <?= activeClassName("projects") ?>"
                       href="<?= home_url("app/projects") ?>">
                        <i class="ri-folder-5-line"></i> <span data-key="t-projects">Projects</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link menu-link <?= activeClassName("report-working") ?>"
                       href="<?= home_url("app/report-working") ?>">
                        <i class="ri-clipboard-line"></i> <span data-key="t-report-working">Report Working</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link menu-link <?= activeClassName("maintain-web") ?>"
                       href="<?= home_url("app/maintain-web") ?>">
                        <i class="ri-global-line"></i> <span data-key="t-maintain-web">Maintain Web</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link menu-link <?= activeClassName("seo-checklist") ?>"
                       href="<?= home_url("app/seo-checklist") ?>">
                        <i class="ri-search-line"></i> <span data-key="t-seo-checklist">SEO Checklist</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link menu-link <?= activeClassName("web-dev-checklist") ?>"
                       href="<?= home_url("app/web-dev-checklist") ?>">
                        <i class="ri-tools-line"></i> <span data-key="t-web-dev-checklist">Web Dev Checklist</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link menu-link <?= activeClassName("web-secure-checklist") ?>"
                       href="<?= home_url("app/web-secure-checklist") ?>">
                        <i class="ri-shield-line"></i> <span data-key="t-web-secure-checklist">Web Secure
                            Checklist</span>
                    </a>
                </li>
                <li class="menu-title"><span data-key="t-development">Development</span></li>
                <li class="nav-item">
                    <a class="nav-link menu-link <?= activeClassName("code") ?>" href="<?= home_url("app/code") ?>">
                        <i class="ri-code-line"></i> <span data-key="t-code">Code</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link menu-link <?= activeClassName("tip") ?>" href="<?= home_url("app/tip") ?>">
                        <i class="ri-lightbulb-line"></i> <span data-key="t-tip">Tip</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link menu-link <?= activeClassName("book") ?>" href="<?= home_url("app/book") ?>">
                        <i class="ri-book-open-line"></i> <span data-key="t-book">Book</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link menu-link <?= activeClassName("git") ?>" href="<?= home_url("app/git") ?>">
                        <i class="ri-git-merge-line"></i> <span data-key="t-git">Git</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link menu-link <?= activeClassName("course") ?>" href="<?= home_url("app/course") ?>">
                        <i class="ri-graduation-cap-line"></i> <span data-key="t-course">Course</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link menu-link <?= activeClassName("blog") ?>" href="<?= home_url("app/blog") ?>">
                        <i class="ri-article-line"></i> <span data-key="t-blog">Blog</span>
                    </a>
                </li>
                <li class="menu-title"><span data-key="t-network">Network</span></li>
                <li class="nav-item">
                    <a class="nav-link menu-link <?= activeClassName("lead") ?>" href="<?= home_url("app/lead") ?>">
                        <i class="ri-file-user-line"></i> <span data-key="t-lead">Lead</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link menu-link <?= activeClassName("website") ?>"
                       href="<?= home_url("app/website") ?>">
                        <i class="ri-links-line"></i> <span data-key="t-website">Website</span>
                    </a>
                </li>
                <li class="menu-title"><span data-key="t-network">Game</span></li>
                <li class="nav-item">
                    <a class="nav-link menu-link <?= activeClassName("football-manager") ?>"
                       href="<?= home_url("app/football-manager") ?>">
                        <i class="ri-football-line"></i> <span data-key="t-football-manager">Football Manager</span>
                    </a>
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