<?php
$pageTitle = "403 Access Denied";

ob_start();

echo '<div class="auth-page-content overflow-hidden p-0">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-7 col-lg-8">
                    <div class="text-center pt-4">
                        <img src="' . home_url("assets/images/error500.png") . '" alt="error img" class="img-fluid">
                        <div class="mt-3">
                            <h3 class="text-uppercase">403 - Access Denied</h3>
                            <p class="text-muted mb-4">Sorry, you do not have permission to access this page. Please upgrade your plan.</p>
                            <div class="hstack justify-content-center">
                            <a href="' . home_url("app") . '" class="btn btn-ghost-primary"><i class="mdi mdi-home me-1"></i>Back to Dashboard</a>
                            <a href="' . home_url("app/pricing") . '" class="btn btn-success ms-2"><i class="mdi mdi-account-arrow-up me-1"></i>Upgrade Account!</a>
                            </div>
                        </div>
                    </div>
                </div><!-- end col -->
            </div>
            <!-- end row -->
        </div>
        <!-- end container -->
    </div>';

$pageContent = ob_get_clean();

include 'layout.php';
