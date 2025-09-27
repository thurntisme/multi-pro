<?php
$pageTitle = "Lottery Simulator";

ob_start();
?>

<div class="auth-page-content overflow-hidden p-0" id="lottery-simulator">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-7 col-lg-8">
                <div class="text-center py-5">
                    <h1 class="mb-3">🎟️ Lottery Simulator</h1>
                    <p class="mb-3">Pick 6 lucky numbers for a chance to win!</p>
                    <div class="lottery-container d-flex gap-3 align-items-center justify-content-center mb-3">
                        <?php for ($i = 0; $i < 6; $i++) { ?>
                            <div class="lottery-box">
                                <div class="lottery-result">0</div>
                            </div>
                        <?php } ?>
                    </div>
                    <div class="mb-4 d-flex justify-content-center">
                        <input type="text" class="form-control" placeholder="Enter your number" name="your_number">
                    </div>
                    <button class="btn btn-outline-primary spin-btn btn-load" id="btn-play">
                        <span class="d-flex align-items-center">
                            <span class="flex-grow-1">
                                Spin
                            </span>
                            <span class="spinner-border flex-shrink-0 d-none ms-2" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </span>
                        </span>
                    </button>
                </div>
            </div><!-- end col -->
        </div>
        <!-- end row -->
    </div>
    <!-- end container -->
</div>

<?php
$pageContent = ob_get_clean();

ob_start(); ?>
<script src="<?= App\Helpers\Network::home_url("assets/js/pages/lottery-simulator.js") ?>"></script>
<?php
$additionJs = ob_get_clean();
