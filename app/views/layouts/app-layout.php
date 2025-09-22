<?php include 'layouts/main.php'; ?>

<head>

    <?php includeFileWithVariables('layouts/title-meta.php', array('title' => $pageTitle ?? "")); ?>

    <?php include 'layouts/head-css.php'; ?>

</head>

<body>

    <!-- Begin page -->
    <div id="layout-wrapper">

        <?php include 'layouts/menu.php'; ?>

        <!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->
        <div class="main-content">

            <div class="page-content">
                <div class="container-fluid">

                    <?php includeFileWithVariables('layouts/page-title.php', array('title' => $pageTitle ?? "")); ?>
                    <?php echo $pageContent ?? '<p>Welcome to the main content area.</p>'; ?>

                </div>
                <!-- container-fluid -->
            </div>
            <!-- End Page-content -->

            <?php include 'layouts/footer.php'; ?>
        </div>
        <!-- end main content-->

    </div>
    <!-- END layout-wrapper -->

    <?php //include 'layouts/customizer.php';
    ?>

    <?php include 'layouts/vendor-scripts.php'; ?>

    <!-- App js -->
    <script src="<?= App\Helpers\NetworkHelper::home_url("assets/js/app.js") ?>"></script>
    <script src="<?= App\Helpers\NetworkHelper::home_url("assets/js/custom.js") ?>"></script>
</body>

</html>