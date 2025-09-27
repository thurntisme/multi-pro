<!doctype html>
<html lang="en" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg"
    data-sidebar-image="none" data-preloader="disable">

<head>
    <?php include_once LAYOUTS_PATH . 'partials/title-meta.php' ?>
    <!--Swiper slider css-->
    <link href="<?= App\Helpers\Network::home_url('assets/libs/swiper/swiper-bundle.min.css') ?>" rel="stylesheet"
        type="text/css" />
    <!-- Layout config Js -->
    <script src="<?= App\Helpers\Network::home_url('assets/js/layout.js') ?>"></script>
    <!-- Bootstrap Css -->
    <link href="<?= App\Helpers\Network::home_url('assets/css/bootstrap.min.css') ?>" rel="stylesheet"
        type="text/css" />
    <!-- Icons Css -->
    <link href="<?= App\Helpers\Network::home_url('assets/css/icons.min.css') ?>" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="<?= App\Helpers\Network::home_url('assets/css/app.min.css') ?>" rel="stylesheet" type="text/css" />
    <!-- custom Css-->
    <link href="<?= App\Helpers\Network::home_url('assets/css/custom.min.css') ?>" rel="stylesheet" type="text/css" />
</head>

<body data-bs-spy="scroll" data-bs-target="#navbar-example">

    <!-- Begin page -->
    <div class="layout-wrapper landing">
        <?php include_once LAYOUTS_PATH . 'partials/landing-header.php' ?>

        <?php echo $pageContent ?? '<p>Welcome to the main content area.</p>'; ?>

        <?php include_once LAYOUTS_PATH . 'partials/landing-footer.php' ?>

        <!--start back-to-top-->
        <button onclick="topFunction()" class="btn btn-danger btn-icon landing-back-top" id="back-to-top">
            <i class="ri-arrow-up-line"></i>
        </button>
        <!--end back-to-top-->
    </div>
    <!-- end layout wrapper -->

    <!-- JAVASCRIPT -->
    <script src="<?= App\Helpers\Network::home_url('assets/libs/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
    <script src="<?= App\Helpers\Network::home_url('assets/libs/simplebar/simplebar.min.js') ?>"></script>
    <script src="<?= App\Helpers\Network::home_url('assets/libs/node-waves/waves.min.js') ?>"></script>
    <script src="<?= App\Helpers\Network::home_url('assets/libs/feather-icons/feather.min.js') ?>"></script>
    <script src="<?= App\Helpers\Network::home_url('assets/js/pages/plugins/lord-icon-2.1.0.js') ?>"></script>

    <script type="text/javascript">
        (function () {
            const toastList = document.querySelector("[toast-list]");
            const dataChoices = document.querySelector("[data-choices]");
            const dataProvider = document.querySelector("[data-provider]");

            if (toastList || dataChoices || dataProvider) {
                const scripts = [
                    "https://cdn.jsdelivr.net/npm/toastify-js",
                    "<?= App\Helpers\Network::home_url('assets/libs/choices.js/public/assets/scripts/choices.min.js') ?>",
                    "<?= App\Helpers\Network::home_url('assets/libs/flatpickr.min.js') ?>"
                ];

                scripts.forEach(src => {
                    const script = document.createElement("script");
                    script.type = "text/javascript";
                    script.src = src;
                    document.body.appendChild(script);
                });
            }
        })();
    </script>

    <!--Swiper slider js-->
    <script src="<?= App\Helpers\Network::home_url('assets/libs/swiper/swiper-bundle.min.js') ?>"></script>

    <!-- landing init -->
    <script src="<?= App\Helpers\Network::home_url('assets/js/pages/landing.init.js') ?>"></script>
</body>

</html>