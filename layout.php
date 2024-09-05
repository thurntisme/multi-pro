<?php
include 'template-parts/header.php';
include 'template-parts/sidebar.php';
?>

<!-- Content Wrapper -->
<div id="content-wrapper" class="d-flex flex-column">

    <!-- Main Content -->
    <div id="content">

        <?php include 'template-parts/topbar.php'; ?>

        <!-- Begin Page Content -->
        <div class="container-fluid">

            <!-- Page Heading -->
            <div class="d-sm-flex align-items-center mb-4">
                <h1 class="h3 mb-0 text-gray-800"><?= generateBreadcrumbs($pageTitle ?? '') ?></h1>
                <?= $btnAction ?? "" ?>
            </div>

            <div class="content-wrapper">
                <?php echo $pageContent ?? '<p>Welcome to the main content area.</p>'; ?>
            </div>

        </div>
        <!-- /.container-fluid -->

    </div>
    <!-- End of Main Content -->

    <!-- Footer -->
    <footer class="sticky-footer bg-white">
        <div class="container my-auto">
            <div class="copyright text-center my-auto">
                <span>Copyright &copy; <?= $commonController->getSiteName() ?> <?= date('Y') ?></span>
            </div>
        </div>
    </footer>
    <!-- End of Footer -->

</div>
<!-- End of Content Wrapper -->


<?php include 'template-parts/footer.php'; ?>