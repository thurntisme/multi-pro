<?php include_once LAYOUTS_PATH . 'partials/main.php'; ?>

<head>

    <?php include_once LAYOUTS_PATH . 'partials/title-meta.php' ?>
    <?php include_once LAYOUTS_PATH . 'partials/head-css.php'; ?>

</head>

<body>

    <!-- Begin page -->
    <div id="layout-wrapper">

        <?php include_once LAYOUTS_PATH . 'partials/topbar.php'; ?>
        <?php include_once LAYOUTS_PATH . 'partials/sidebar.php'; ?>

        <!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->
        <div class="main-content">

            <div class="page-content">
                <div class="container-fluid">

                    <?php include_once LAYOUTS_PATH . 'partials/breadcrumb.php'; ?>
                    <?php echo $pageContent ?? ''; ?>

                </div>
                <!-- container-fluid -->
            </div>
            <!-- End Page-content -->

            <?php include_once LAYOUTS_PATH . 'partials/footer.php'; ?>
        </div>
        <!-- end main content-->

    </div>
    <!-- END layout-wrapper -->

    <?php //include_once LAYOUTS_PATH . 'partials/customizer.php';
    ?>

    <?php include_once LAYOUTS_PATH . 'partials/vendor-scripts.php'; ?>

    <!-- App js -->
    <script src="<?= App\Helpers\NetworkHelper::home_url("assets/js/app.js") ?>"></script>
    <script src="<?= App\Helpers\NetworkHelper::home_url("assets/js/custom.js") ?>"></script>
</body>

</html>