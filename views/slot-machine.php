<?php
$pageTitle = "Slot Machine";

ob_start();
?>

<div class="auth-page-content overflow-hidden p-0" id="slot-machine">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-7 col-lg-8">
                <div class="text-center py-5">
                    <h2>🎰 Slot Machine</h2>
                    <div class="slot-machine my-4 d-flex gap-2 justify-content-center">
                        <div class="reel border-0">❓</div>
                        <div class="reel border-0">❓</div>
                        <div class="reel border-0">❓</div>
                    </div>
                    <button class="btn btn-outline-primary spin-btn btn-load">
                        <span class="d-flex align-items-center">
                            <span class="flex-grow-1">
                                Spin
                            </span>
                            <span class="spinner-border flex-shrink-0 d-none ms-2" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </span>
                        </span>
                    </button>
                    <div class="message mt-3"></div>
                </div>
            </div><!-- end col -->
        </div>
        <!-- end row -->
    </div>
    <!-- end container -->
</div>

<?php
$pageContent = ob_get_clean();

ob_start();
echo "
<script src='" . home_url("/assets/js/pages/slot-machine.js") . "'></script>
";
$additionJs = ob_get_clean();
