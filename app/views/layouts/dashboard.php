<?php include_once LAYOUTS_DIR . 'partials/main.php'; ?>

<head>

    <?php include_once LAYOUTS_DIR . 'partials/title-meta.php' ?>
    <?php include_once LAYOUTS_DIR . 'partials/head-css.php'; ?>

</head>

<body>

    <!-- Begin page -->
    <div id="layout-wrapper">

        <?php include_once LAYOUTS_DIR . 'partials/top-bar.php'; ?>
        <?php include_once LAYOUTS_DIR . 'partials/sidebar.php'; ?>

        <!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->
        <div class="main-content">

            <div class="page-content">
                <div class="container-fluid pb-4">

                    <?php include_once LAYOUTS_DIR . 'partials/breadcrumb.php'; ?>
                    <?php include_once LAYOUTS_DIR . 'partials/flash.php' ?>
                    <?php echo $pageContent ?? ''; ?>

                </div>
                <!-- container-fluid -->
            </div>
            <!-- End Page-content -->

            <?php include_once LAYOUTS_DIR . 'partials/footer.php'; ?>
        </div>
        <!-- end main content-->

    </div>
    <!-- END layout-wrapper -->

    <?php //include_once LAYOUTS_DIR . 'partials/customizer.php';
    ?>

    <?php include_once LAYOUTS_DIR . 'partials/vendor-scripts.php'; ?>
</body>

</html>