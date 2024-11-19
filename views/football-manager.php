<?php
$pageTitle = "Football Manager";

require_once DIR . '/functions/generate-player.php';
// Generate 10 random players
$players = generateRandomPlayers(20);
$commonController = new CommonController();

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
        <div class="card crm-widget">
            <div class="card-body">
                <div class="flex-grow-1 ms-2">
                    Home page is coming soon
                    <div class="mt-2">
                        <a href="<?= home_url("football-manager/my-club") ?>" class="btn btn-link">Checkout My Club <i class="ri-arrow-right-line ms-1 align-middle"></i></a>
                    </div>
                </div>
            </div><!-- end card body -->
        </div>
    </div>
    <!--end col-->
</div>

<?php
$pageContent = ob_get_clean();

include 'layout.php';
