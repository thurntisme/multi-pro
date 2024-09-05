<?php

ob_start();

echo '<div class="container-fluid">
        <div class="jumbotron bg-light">
            <div class="row">
                <div class="col-10 mx-auto">
                    <!-- 404 Error Text -->
                    <div class="text-center">
                        <div class="error mx-auto" data-text="404">404</div>
                        <p class="lead text-gray-800 mb-5">Page Not Found</p>
                        <p class="text-gray-500 mb-0">It looks like you found a glitch in the matrix...</p>
                        <a href="' . home_url("/") . '">&larr; Back to Dashboard</a>
                    </div>
                </div>
            </div>
        </div>
    </div>';

$pageContent = ob_get_clean();

include 'layout.php';
