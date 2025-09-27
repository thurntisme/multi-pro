<?php
$pageTitle = "404 not found";

ob_start();

echo '<div class="auth-page-content overflow-hidden p-0">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-7 col-lg-8">
                    <div class="text-center">
                        <img src="' . App\Helpers\Network::home_url("assets/images/error400-cover.png") . '" alt="error img" class="img-fluid">
                        <div class="mt-3">
                            <h3 class="text-uppercase">Sorry, Page not Found 😭</h3>
                            <p class="text-muted mb-4">The page you are looking for not available!</p>
                            <a href="' . App\Helpers\Network::home_url("") . '" class="btn btn-success"><i class="mdi mdi-home me-1"></i>Back to Dashboard</a>
                        </div>
                    </div>
                </div><!-- end col -->
            </div>
            <!-- end row -->
        </div>
        <!-- end container -->
    </div>';

$pageContent = ob_get_clean();

include_once LAYOUTS_PATH . 'auth-layout.php';