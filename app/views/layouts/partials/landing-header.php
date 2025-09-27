<nav class="navbar navbar-expand-lg navbar-landing fixed-top" id="navbar">
    <div class="container">
        <a class="navbar-brand" href="<?= App\Helpers\Network::home_url('') ?>">
            <span class="fw-bold text-primary fs-22"><?= DEFAULT_SITE_NAME ?></span>
        </a>
        <button class="navbar-toggler py-0 fs-20 text-body" type="button" data-bs-toggle="collapse"
            data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
            aria-label="Toggle navigation">
            <i class="mdi mdi-menu"></i>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mx-auto mt-2 mt-lg-0" id="navbar-example">
                <li class="nav-item">
                    <a class="nav-link active" href="#hero">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#services">Services</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#features">Features</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#plans">Plans</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#reviews">Reviews</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#team">Team</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#contact">Contact</a>
                </li>
            </ul>

            <div class="">
                <?php if (empty($token)) { ?>
                    <a href="<?= App\Helpers\Network::home_url('login') ?>"
                        class="btn btn-link fw-medium text-decoration-none text-body">Sign in</a>
                    <a href="<?= App\Helpers\Network::home_url('register') ?>" class="btn btn-primary">Sign Up</a>
                <?php } else { ?>
                    <a href="<?= App\Helpers\Network::home_url('app') ?>" class="btn btn-primary">Dashboard</a>
                <?php } ?>
            </div>
        </div>

    </div>
</nav>
<!-- end navbar -->
<div class="vertical-overlay" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent.show"></div>