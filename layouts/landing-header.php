<nav class="navbar navbar-expand-lg navbar-landing fixed-top" id="navbar">
    <div class="container">
        <a class="navbar-brand" href="<?= home_url('') ?>">
            <img src="<?= home_url("assets/images/dark-logo.png") ?>" class="card-logo card-logo-dark"
                 alt="logo dark" height="29">
            <img src="<?= home_url("assets/images/light-logo.png") ?>" class="card-logo card-logo-light"
                 alt="logo light" height="29">
        </a>
        <button class="navbar-toggler py-0 fs-20 text-body" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                aria-expanded="false" aria-label="Toggle navigation">
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
                <a href="<?= home_url('resources') ?>" class="btn btn-info">Resources</a>
                <?php if (empty($token)) { ?>
                    <a href="<?= home_url('login') ?>"
                       class="btn btn-link fw-medium text-decoration-none text-body">Sign in</a>
                    <a href="<?= home_url('register') ?>" class="btn btn-primary">Sign Up</a>
                <?php } else { ?>
                    <a href="<?= home_url('app') ?>" class="btn btn-primary">Dashboard</a>
                <?php } ?>
            </div>
        </div>

    </div>
</nav>
<!-- end navbar -->
<div class="vertical-overlay" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent.show"></div>