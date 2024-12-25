<?php include 'layouts/main.php'; ?>

<head>

    <?php includeFileWithVariables('layouts/title-meta.php', array('title' => $pageTitle ?? "")); ?>

    <?php include 'layouts/head-css.php'; ?>

</head>

<body>

    <div class="auth-page-wrapper pt-5">
        <!-- auth page bg -->
        <div class="auth-one-bg-position auth-one-bg" id="auth-particles">
            <div class="bg-overlay"></div>

            <div class="shape">
                <svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 1440 120">
                    <path d="M 0,36 C 144,53.6 432,123.2 720,124 C 1008,124.8 1296,56.8 1440,40L1440 140L0 140z"></path>
                </svg>
            </div>
        </div>

        <!-- auth page content -->
        <div class="auth-page-content">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="text-center mt-sm-5 mb-4 text-white-50">
                            <div>
                                <a href="<?= home_url('') ?>" class="d-inline-block auth-logo">
                                    <img src="assets/images/logo-light.png" alt="" height="20">
                                </a>
                            </div>
                            <p class="mt-3 fs-15 fw-medium">Premium Admin & Dashboard Template</p>
                        </div>
                    </div>
                </div>
                <!-- end row -->

                <?php echo $pageContent ?? '<p>Welcome to the main content area.</p>'; ?>

            </div>
            <!-- end container -->
        </div>
        <!-- end auth page content -->

        <!-- footer -->
        <footer class="footer">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="text-center">
                            <p class="mb-0 text-muted">&copy;
                                <script>
                                    document.write(new Date().getFullYear())
                                </script> <?= $commonController->getSiteName() ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
        <!-- end Footer -->
    </div>
    <!-- end auth-page-wrapper -->

    <?php include 'layouts/vendor-scripts.php'; ?>

    <!-- particles js -->
    <script src="<?= home_url("assets/libs/particles.js/particles.js") ?>"></script>
    <!-- particles app js -->
    <script src="<?= home_url("assets/js/pages/particles.app.js") ?>"></script>
    <!-- validation init -->
    <script src="<?= home_url("assets/js/pages/form-validation.init.js") ?>"></script>

    <?php
    if (!empty($additionJs)) {
        echo $additionJs;
    }
    ?>
</body>

</html>