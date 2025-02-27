<?php
$pageTitle = "Number Guessing";

ob_start();
?>

<div class="auth-page-content overflow-hidden p-0">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-7 col-lg-8">
                <div class="text-center py-5">
                    <h2>🔢 Number Guessing Game</h2>
                    <p>Guess a number between 1 and 100!</p>

                    <div class="d-flex gap-2 justify-content-center my-4">
                        <input type="number" id="userGuess" class="form-control" min="1" max="100" style="width: 140px;" placeholder="Enter your guess">
                        <button id="checkGuess" class="btn btn-primary">Check</button>
                    </div>

                    <p id="message"></p>
                    <button id="restart" class="btn btn-success" style="display:none;">Restart</button>
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
<script src='" . home_url("/assets/js/pages/number-guessing.js") . "'></script>
";
$additionJs = ob_get_clean();
