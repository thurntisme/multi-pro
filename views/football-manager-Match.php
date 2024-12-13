<?php
$pageTitle = "Football Manager - Match";

require_once DIR . '/functions/generate-player.php';
require_once DIR . '/controllers/FootballTeamController.php';
require_once DIR . '/controllers/FootballLeagueController.php';

$footballTeamController = new FootballTeamController();
$footballLeagueController = new FootballLeagueController();

$match_uuid = $_GET['uuid'];
$match = $footballLeagueController->getMatch($match_uuid);
$home_team = $match['home'];
$away_team = $match['away'];

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
            <div class="row d-none" id="match-progress">
                <div class="col-8">
                    <div class="card">
                        <div class="card-header align-items-center justify-content-center d-flex">
                            <span class="text-muted fs-12" id="team-1-name"><?= $home_team['team_name'] ?></span>
                            <h4 class="card-title mb-0 fs-20 mx-2">
                                <span id="team-1-score">0</span>
                                <span class="mx-1">:</span>
                                <span id="team-2-score">0</span>
                            </h4>
                            <span class="text-muted fs-12" id="team-2-name"><?= $away_team['team_name'] ?></span>
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
                        <div class="card-header align-items-center d-flex">
                            <h4 class="card-title mb-0 fs-14" id="curr-time">
                            <span id="minute" class="d-inline-block text-center"
                                  style="width: 20px">00</span>:<span
                                        id="second" class="d-inline-block text-center" style="width: 20px">00</span>
                            </h4>
                            <button class="btn btn-sm btn-light ms-auto me-1" id="btn-cancel-match">Cancel</button>
                            <button class="btn btn-sm btn-success" id="btn-accept-match" disabled>Next</button>
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
            <div class="row" id="match-result">
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
                                        <tr data-player-uuid="<?= $item['player_uuid'] ?>">
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
                                <div class="p-5 border-bottom-dashed border-1" style="border-color: var(--vz-border-color);">
                                    <div class="row">
                                        <div class="col-5">
                                            <div class="d-flex align-items-center justify-content-end">
                                                <div class="avatar-md me-3">
                                                    <img src="<?= home_url("assets/images/users/avatar-1.jpg") ?>" alt="" id="candidate-img" class="img-thumbnail rounded-circle shadow-none">
                                                </div>
                                                <h5 id="candidate-name" class="mb-0">Anna Adame</h5>
                                            </div>
                                        </div>
                                        <div class="col-2">
                                            <div class="d-flex align-items-center justify-content-center h-100">
                                                <h1 class="mb-0 d-flex align-items-baseline"><span>0</span><small class="fs-14 px-2">:</small><span>0</span></h1>
                                            </div>
                                        </div>
                                        <div class="col-5">
                                            <div class="d-flex align-items-center justify-content-start">
                                                <h5 id="candidate-name" class="mb-0">Anna Adame</h5>
                                                <div class="avatar-md me-3">
                                                    <img src="<?= home_url("assets/images/users/avatar-1.jpg") ?>" alt="" id="candidate-img" class="img-thumbnail rounded-circle shadow-none">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="p-5 border-bottom-dashed border-1" style="border-color: var(--vz-border-color);">
                                    <h4>Best player: aaa</h4>
                                    <h4>Best player: aaa</h4>
                                </div>
                                <div class="p-5">
                                    <div class="row">
                                        <?php foreach ([1,2,3] as $idx){ ?>
                                            <div class="col-4">
                                                <div class="text-center">
                                                    <div class="avatar-xl mb-3 mx-auto rounded-circle d-flex justify-content-center align-items-center bg-success-subtle bg-opacity-10">
                                                        <i class="ri ri-gift-line fs-40" style="font-size: 44px!important;"></i>
                                                    </div>
                            
                                                    <div>
                                                        <button type="button" class="btn btn-success rounded-pill w-sm" data-gift-idx="<?= $idx ?>">Open Gift</button>
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
        </div>
    </div>
    <!--end col-->

<?php
$pageContent = ob_get_clean();

ob_start();
echo "
    <script type='text/javascript'>
        const canvas = document.getElementById('footballPitch');
        const ctx = canvas.getContext('2d');

        // Pitch Dimensions
        const width = canvas.width;
        const height = canvas.height;

        const team1 = {
            name: '" . $home_team['team_name'] . "',
            formation: '" . $home_team['formation'] . "',
            score: 0,
            players: " . json_encode($home_team['lineup']) . ",
            bench: " . json_encode($home_team['bench']) . ",
        }

        const team2 = {
            name: '" . $away_team['team_name'] . "',
            formation: '" . $away_team['formation'] . "',
            score: 0,
            players: " . json_encode($away_team['lineup']) . ",
            bench: " . json_encode($away_team['bench']) . ",
        }
        const groupTeams = [team1, team2];
        const pitchX = 50 * groupTeams.length;

        const positionGroups = " . json_encode($positionGroupsExtra) . "; 
    </script>
    <script src='" . home_url("/assets/js/pages/football-manager-formation.js") . "'></script>
    <script src='" . home_url("/assets/js/pages/football-manager.js") . "'></script>
    <script src='" . home_url("/assets/js/pages/football-manager-match.js") . "'></script>
";
$additionJs = ob_get_clean();

include 'layout.php';
