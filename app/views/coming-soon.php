<?php
$pageTitle = "Coming soon";

ob_start();

echo '<div class="container-fluid">
        <div class="jumbotron bg-light">
            <div class="row">
                <div class="col-10 mx-auto">
                    <div class="text-center">
                        <div class="error mx-auto mb-3" style="width: 100%">Coming Soon</div>
                        <p class="text-gray-500 mb-2">We are working hard to launch our new website. Stay tuned!</p>
                        <a href="' . home_url("/") . '">&larr; Back to Dashboard</a>
                    </div>
                </div>
            </div>
        </div>
    </div>';

$pageContent = ob_get_clean();
