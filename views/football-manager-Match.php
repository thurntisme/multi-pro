<?php
$pageTitle = "Football Manager - Match";

require_once DIR . '/functions/generate-player.php';
// Generate 10 random players
$lineupPlayers = generateRandomPlayers(10);
$subPlayers = generateRandomPlayers(22);
$commonController = new CommonController();
$lineupList = $commonController->convertResources($lineupPlayers);

ob_start();
?>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <?php includeFileWithVariables('components/football-player-topbar.php'); ?>
            </div>
        </div>
    </div>
    <!--end col-->
    <div class="col-lg-12">
        <div class="row">
            <div class="col-8">
                <div class="card">
                    <div class="card-header align-items-center justify-content-center d-flex">
                        <span class="text-muted fs-12" id="team-1-name">AA</span>
                        <h4 class="card-title mb-0 fs-20 mx-2">
                            <span id="team-1-score">0</span>
                            <span class="mx-1">:</span>
                            <span id="team-2-score">0</span>
                        </h4>
                        <span class="text-muted fs-12" id="team-2-name">BB</span>
                    </div>
                    <div class="card-body p-0">
                        <div class="p-3 d-flex align-items-center justify-content-center">
                            <canvas id="footballPitch" width="740" height="370"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-4">
                <div class="card card-height-100">
                    <div class="card-header align-items-center d-flex justify-content-center">
                        <h4 class="card-title mb-0 fs-14 " id="curr-time">00:00</h4>
                    </div>
                    <div class="card-body">
                        <div class="profile-timeline" data-simplebar style="height: 370px;">
                            <div class="acitivity-timeline" id="match-timeline">
                            </div>
                        </div>
                    </div><!-- end card body -->
                </div>
            </div>
        </div>
    </div>
</div>
<!--end col-->
</div>

<?php
$pageContent = ob_get_clean();

ob_start();
echo "
    <script src='" . home_url("/assets/js/pages/football-manager-match.js") . "'></script>
";
$additionJs = ob_get_clean();

include 'layout.php';
