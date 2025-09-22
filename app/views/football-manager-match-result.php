<?php
$pageTitle = "Football Manager - Match Result";

require_once DIR . '/functions/generate-player.php';
require_once DIR . '/controllers/FootballTeamController.php';
require_once DIR . '/controllers/FootballLeagueController.php';
require_once DIR . '/controllers/FootballMatchController.php';

$footballTeamController = new FootballTeamController();
$footballLeagueController = new FootballLeagueController();
$footballMatchController = new FootballMatchController();

$match_uuid = $_GET['uuid'];
$match = $footballMatchController->getMatchResult($match_uuid);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action_name'])) {
        if ($_POST['action_name'] === 'accept_match') {
            $footballMatchController->acceptMatchResult($match_uuid);
        }
    }
}

ob_start();
?>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <?php includeFileWithVariables('components/football-player-topbar.php'); ?>
            </div>
        </div>

        <?php
        include_once DIR . '/components/alert.php';
        ?>
    </div>
    <!--end col-->
    <div class="col-lg-12">
        <div class="row">
            <div class="col-12">
                <?php if (empty($match)) { ?>
                    <div class="card">
                        <div class="card-body">
                            <h4 class="text-muted">Match not found.</h4>
                        </div>
                    </div>
                <?php } else {
                    ?>
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive table-card">
                                <table class="table align-middle table-nowrap mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th colspan="4" class="text-center">Result</th>
                                        </tr>
                                    </thead>
                                </table>
                                <div class="p-5 border-bottom-dashed border-1"
                                    style="border-color: var(--vz-border-color);">
                                    <div class="row">
                                        <div class="col-5">
                                            <div class="d-flex align-items-center justify-content-end">
                                                <div class="avatar-md me-3">
                                                    <img src="<?= App\Helpers\NetworkHelper::home_url("assets/images/users/avatar-1.jpg") ?>"
                                                        alt="" id="candidate-img"
                                                        class="img-thumbnail rounded-circle shadow-none">
                                                </div>
                                                <h5 class="mb-0 w-50 text-end"><?= $match['home_team'] ?></h5>
                                            </div>
                                        </div>
                                        <div class="col-2">
                                            <div class="d-flex align-items-center justify-content-center h-100">
                                                <h1 class="mb-0 d-flex align-items-baseline">
                                                    <span><?= $match['home_score'] ?></span><small
                                                        class="fs-14 px-2">:</small><span><?= $match['away_score'] ?></span>
                                                </h1>
                                            </div>
                                        </div>
                                        <div class="col-5">
                                            <div class="d-flex align-items-center justify-content-start">
                                                <h5 class="mb-0 w-50"><?= $match['away_team'] ?></h5>
                                                <div class="avatar-md me-3">
                                                    <img src="<?= App\Helpers\NetworkHelper::home_url("assets/images/users/avatar-1.jpg") ?>"
                                                        alt="" id="candidate-img"
                                                        class="img-thumbnail rounded-circle shadow-none">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-center mt-2">
                                        <h4 class="badge bg-warning-subtle text-warning fs-18 m-0">
                                            <?= formatCurrency($match['draft_budget']) ?></h4>
                                    </div>
                                </div>
                                <div class="p-5">
                                    <div class="row" id="gift-box">
                                        <?php foreach ([0, 1, 2] as $idx) { ?>
                                            <div class="col-4">
                                                <div class="text-center">
                                                    <div
                                                        class="avatar-xl mb-3 mx-auto rounded-circle d-flex justify-content-center align-items-center bg-success-subtle bg-opacity-10">
                                                        <i class="ri ri-gift-line fs-40" style="font-size: 44px!important;"></i>
                                                    </div>
                                                    <div class="gift-item">
                                                        <button type="button"
                                                            class="btn btn-success rounded-pill w-sm btn-open-gift"
                                                            data-item-idx="<?= $idx ?>">
                                                            <span class="d-flex align-items-center justify-content-center">
                                                                <span class="spinner-border flex-shrink-0 d-none"
                                                                    style="width: 20px; height: 20px;" role="status">
                                                                    <span class="visually-hidden">Loading...</span>
                                                                </span>
                                                                <span class="flex-grow-1 text">
                                                                    Open Gift
                                                                </span>
                                                            </span>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="text-center mb-4">
                        <a href="<?= App\Helpers\NetworkHelper::home_url("app/football-manager") ?>"
                            class="btn btn-primary">Next</a>
                    </div>
                    <?php
                } ?>
            </div>
        </div>
    </div>
</div>
<!--end col-->

<?php
$pageContent = ob_get_clean();

if (!empty($match)) {
    ob_start();
    echo "
    <script type='text/javascript'>
        let apiUrl = '" . home_url("/api") . "';
    </script>
    <script src='" . home_url("/assets/js/pages/football-manager-match-result.js") . "'></script>
";
    $additionJs = ob_get_clean();
}
