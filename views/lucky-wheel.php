<?php
$pageTitle = "Lucky Wheel";

ob_start();
?>

<div class="auth-page-content overflow-hidden p-0" id="lucky-wheel-container">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-4">
                <div class="mb-3">
                    <label for="title" class="form-label">Title</label>
                    <input type="text" class="form-control" name="title" placeholder="Enter the title of the wheel">
                </div>
                <form action="javascript:void(0);">
                    <div class="d-flex gap-2">
                        <input type="text" class="form-control" placeholder="Enter keyword" name="keyword">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
                <ul class="list-group mt-4" id="list">
                </ul>
            </div>
            <div class="col-8">
                <div id="wheel-wrapper">
                    <div id="cursor"></div>
                    <canvas id="wheelCanvas" width="400" height="400"></canvas>
                    <button id="spinBtn" class="btn btn-primary">Spin</button>
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
<script src='" . home_url("/assets/js/pages/lucky-wheel.js") . "'></script>
";
$additionJs = ob_get_clean();
