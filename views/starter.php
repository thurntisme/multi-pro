<?php
$pageTitle = "Starter";

ob_start();
?>

<div class="auth-page-content overflow-hidden p-0">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-7 col-lg-8">
                <div class="text-center">
                    <div class="mt-3">
                        <h3 class="text-uppercase"><?= $pageTitle ?></h3>
                    </div>
                </div>
            </div><!-- end col -->
        </div>
        <!-- end row -->
    </div>
    <!-- end container -->
</div>

<?php
$pageContent = ob_get_clean();

include 'layout.php';
