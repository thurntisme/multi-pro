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
            <div class="row">
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
