<?php
$pageTitle = "Football Manager - Match";

require_once DIR . '/functions/generate-player.php';
require_once DIR . '/controllers/FootballTeamController.php';
require_once DIR . '/controllers/FootballLeagueController.php';
require_once DIR . '/controllers/FootballMatchController.php';

$footballTeamController = new FootballTeamController();
$footballLeagueController = new FootballLeagueController();
$footballMatchController = new FootballMatchController();

$match_uuid = $_GET['uuid'];
$match = $footballMatchController->getTeamInMatch($match_uuid);

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
            <?php if (empty($match)) { ?>
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="text-muted">Match not found.</h4>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } else {
                $home_team = $match['home_team_data'];
                $away_team = $match['away_team_data'];
                if (count($home_team['bench']) > 5 && count($away_team['bench']) > 5) {
                    ?>
                    <div class="row" id="match-progress">
                        <div class="col-8">
                            <div class="card">
                                <div class="card-header align-items-center justify-content-center d-flex">
                                <span class="text-muted fs-12 w-25" id="team-1-name"
                                      style="text-align: right"><?= $match['home_team'] ?></span>
                                    <h4 class="card-title mb-0 fs-20 mx-2">
                                        <span id="team-1-score">0</span>
                                        <span class="mx-1">:</span>
                                        <span id="team-2-score">0</span>
                                    </h4>
                                    <span class="text-muted fs-12 w-25"
                                          id="team-2-name"><?= $match['away_team'] ?></span>
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
                                <div class="card-header align-items-center d-flex gap-1">
                                    <h4 class="card-title mb-0 fs-14 me-auto" id="curr-time">
                                    <span id="minute" class="d-inline-block text-center"
                                          style="width: 20px">00</span>:<span
                                                id="second" class="d-inline-block text-center"
                                                style="width: 20px">00</span>
                                    </h4>
                                    <button class="btn btn-sm btn-primary" id="btn-start-match">Start</button>
                                    <button class="btn btn-sm btn-info d-none" id="btn-match-info"
                                            data-bs-toggle="modal"
                                            data-bs-target="#matchInfoBackdrop">Match Info
                                    </button>
                                    <button class="btn btn-sm btn-light d-none" id="btn-cancel-match">Cancel</button>
                                    <form action="<?= $_SERVER['REQUEST_URI'] ?>" class="d-none" method="post"
                                          id="match-form">
                                        <input type="hidden" name="action_name" value="accept_match">
                                        <input type="hidden" name="match_result" value="">
                                        <button class="btn btn-sm btn-success" type="submit">Next</button>
                                    </form>
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
                    <div class="row d-none" id="match-result">
                        <div class="col-4">
                            <div class="card">
                                <div class="card-body">
                                    <div class="table-responsive table-card">
                                        <table class="table align-middle table-nowrap mb-0">
                                            <thead class="table-light">
                                            <tr>
                                                <th colspan="4">My Team</th>
                                            </tr>
                                            </thead>
                                        </table>
                                        <table class="table align-middle table-nowrap mb-0">
                                            <tbody class="list form-check-all">
                                            <?php foreach (array_merge($home_team['lineup'], $home_team['bench']) as $item) { ?>
                                                <tr data-player-uuid="<?= $item['uuid'] ?>">
                                                    <td style="width: 10%;">
                                                        <span class="ps-2"
                                                              style="border-left: solid 4px <?= getPositionColor($item['best_position']) ?>"> <?= $item['best_position'] ?></span>
                                                    </td>
                                                    <td><?= $item['name'] ?></td>
                                                    <td class="text-center player-score"><?= $item['score'] ?></td>
                                                </tr>
                                            <?php } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-8">
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
                                                            <img src="<?= home_url("assets/images/users/avatar-1.jpg") ?>"
                                                                 alt="" id="candidate-img"
                                                                 class="img-thumbnail rounded-circle shadow-none">
                                                        </div>
                                                        <h5 id="candidate-name" class="mb-0">Anna Adame</h5>
                                                    </div>
                                                </div>
                                                <div class="col-2">
                                                    <div class="d-flex align-items-center justify-content-center h-100">
                                                        <h1 class="mb-0 d-flex align-items-baseline">
                                                            <span>0</span><small
                                                                    class="fs-14 px-2">:</small><span>0</span></h1>
                                                    </div>
                                                </div>
                                                <div class="col-5">
                                                    <div class="d-flex align-items-center justify-content-start">
                                                        <h5 id="candidate-name" class="mb-0">Anna Adame</h5>
                                                        <div class="avatar-md me-3">
                                                            <img src="<?= home_url("assets/images/users/avatar-1.jpg") ?>"
                                                                 alt="" id="candidate-img"
                                                                 class="img-thumbnail rounded-circle shadow-none">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="p-5 border-bottom-dashed border-1"
                                             style="border-color: var(--vz-border-color);">
                                            <h4>Best player: aaa</h4>
                                            <h4>Best player: aaa</h4>
                                        </div>
                                        <div class="p-5">
                                            <div class="row">
                                                <?php foreach ([1, 2, 3] as $idx) { ?>
                                                    <div class="col-4">
                                                        <div class="text-center">
                                                            <div class="avatar-xl mb-3 mx-auto rounded-circle d-flex justify-content-center align-items-center bg-success-subtle bg-opacity-10">
                                                                <i class="ri ri-gift-line fs-40"
                                                                   style="font-size: 44px!important;"></i>
                                                            </div>

                                                            <div>
                                                                <button type="button"
                                                                        class="btn btn-success rounded-pill w-sm"
                                                                        data-gift-idx="<?= $idx ?>">Open Gift
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
                        </div>
                    </div>
                <?php } else { ?>
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="text-muted">Your team does not meet the requirements to join this match.
                                        Please make the necessary adjustments to your club.</h4>
                                    <a href="<?= home_url('app/football-manager/my-club') ?>"
                                       class="btn btn-soft-primary mt-2">Go to My Club <i
                                                class="ri-arrow-right-line ms-1 align-middle"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php }
            } ?>
        </div>
    </div>
    <!--end col-->

    <div class="modal modal-xl fade" id="matchInfoBackdrop" data-bs-backdrop="static" data-bs-keyboard="false"
         tabindex="-1" role="dialog" aria-labelledby="playerDetailBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body text-center p-5">
                    <h4 class="mb-2">Match Info</h4>
                    <div id="matchAttributes" class="py-3 mt-2"></div>
                    <div class="hstack gap-2 justify-content-center mt-2">
                        <a href="javascript:void(0);" class="btn btn-link link-success fw-medium"
                           data-bs-dismiss="modal"><i class="ri-close-line me-1 align-middle"></i> Close</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php
$pageContent = ob_get_clean();

if (!empty($match) && (count($home_team['bench']) > 5 && count($away_team['bench']) > 5)) {
    ob_start();
    echo "
    <script type='text/javascript'>
        let apiUrl = '" . home_url("/api") . "';
        const canvas = document.getElementById('footballPitch');
        const ctx = canvas.getContext('2d');

        // Pitch Dimensions
        const width = canvas.width;
        const height = canvas.height;

        const team1 = {
            name: '" . $match['home_team'] . "',
            formation: '" . $home_team['formation'] . "',
            score: 0,
            players: " . json_encode($home_team['lineup']) . ",
            bench: " . json_encode($home_team['bench']) . ",
            is_my_team: " . json_encode($match['is_home']) . " === 1,
        }

        const team2 = {
            name: '" . $match['away_team'] . "',
            formation: '" . $away_team['formation'] . "',
            score: 0,
            players: " . json_encode($away_team['lineup']) . ",
            bench: " . json_encode($away_team['bench']) . ",
            is_my_team: " . json_encode($match['is_home']) . " === 0,
        }
        const groupTeams = [team1, team2];
        const pitchX = 50 * groupTeams.length;

        const positionGroups = " . json_encode($positionGroupsExtra) . "; 
        const crowdAudioPath = '" . home_url('assets/audio/football-crowd.mp3') . "'; 
        const ballImagePath = '" . home_url('assets/images/football-manager/ball.png') . "'; 
        const yellowCardImagePath = '" . home_url('assets/images/football-manager/yellow-card.png') . "'; 
        const redCardImagePath = '" . home_url('assets/images/football-manager/red-card.png') . "'; 
        const goalImagePath = '" . home_url('assets/images/football-manager/goal.png') . "';  
    </script>
    <script src='" . home_url("/assets/js/pages/football-manager-formation.js") . "'></script>
    <script src='" . home_url("/assets/js/pages/football-manager.js") . "'></script>
    <script src='" . home_url("/assets/js/pages/football-manager-match.js") . "'></script>
";
    $additionJs = ob_get_clean();
}
