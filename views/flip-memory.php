<?php
$pageTitle = "Flip Memory";

ob_start();
?>

<div class="auth-page-content overflow-hidden p-0" id="card-flip-memory">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-7 col-lg-8">
                <div class="text-center py-5">
                    <h2 class="mb-3">🃏 Card Flip Memory</h2>
                    <p>Score: <span id="score">0</span></p>
                    <p>Time Left: <span id="timer">30</span> seconds</p>
                    <div id="game-board" class="game-board mt-4"></div>
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
<script src="<?= home_url("assets/js/pages/flip-memory.js") ?>"></script>
<?php
$additionJs = ob_get_clean();
