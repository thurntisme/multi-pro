<?php
$pageTitle = "Football Manager - My Club";

require_once DIR . '/functions/generate-player.php';
require_once DIR . '/controllers/FootballTeamController.php';
require_once DIR . '/controllers/FootballPlayerController.php';

$footballPlayerController = new FootballPlayerController();
$footballTeamController = new FootballTeamController();

$lineupPlayers = [];
$subPlayers = [];
$myTeam = $footballTeamController->getMyTeam();
if ($myTeam['players']) {
    $teamPlayerData = getTeamPlayerData($myTeam['players']);
    $lineupPlayers = array_slice($myTeam['players'], 0, 11);
    $subPlayers = array_slice($myTeam['players'], 11);
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
        </div>
        <!--end col-->
        <div class="col-lg-12">
            <?php if ($myTeam) { ?>
                <div class="row">
                    <div class="col-4">
                        <div class="card">
                            <div class="card-body p-0">
                                <table class="table align-middle table-nowrap mb-0">
                                    <thead class="table-light">
                                    <tr>
                                        <th>
                                            <div class="d-flex align-items-center justify-content-between">
                                                <span>Team Formations</span>
                                                <select class="form-select form-select-sm" data-choices
                                                        data-choices-sorting-false
                                                        data-choices-search-false id="formation"
                                                        value="<?= $myTeam['formation'] ?>">
                                                    <?php foreach (DEFAULT_FOOTBALL_FORMATION as $formation): ?>
                                                        <option value="<?= $formation['slug']; ?>" <?= $formation['slug'] === $myTeam['formation'] ? 'selected' : ''; ?>>
                                                            <?= $formation['name']; ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </th>
                                    </tr>
                                    </thead>
                                </table>
                                <div class="p-3 d-flex align-items-center justify-content-center">
                                    <canvas id="footballPitch" width="320" height="160"></canvas>
                                    <input type="hidden" name="team_formation" value="<?= $myTeam['formation'] ?>">
                                </div>
                            </div>
                        </div>
                        <?php if ($lineupPlayers) { ?>
                            <div class="card">
                                <div class="card-body p-4">
                                    <div class="row">
                                        <div class="col-4 text-center">
                                            <div class="profile-user position-relative d-inline-block mx-auto">
                                                <img src="<?= home_url('assets/images/users/avatar-1.jpg') ?>"
                                                     class="rounded-circle avatar-md img-thumbnail user-profile-image"
                                                     alt="user-profile-image">
                                            </div>
                                        </div>
                                        <div class="col-8">
                                            <h5 class="fs-16 mb-1"><?= $lineupPlayers[0]['name'] ?></h5>
                                            <p class="text-muted mb-0 fs-14"><?= $lineupPlayers[0]['nationality'] ?></p>
                                            <p class="text-muted mb-0 mt-2"><?= $lineupPlayers[0]['best_position'] ?>
                                                (<?= $lineupPlayers[0]['ability'] ?>)
                                                | <?= implode(", ", $lineupPlayers[0]['playable_positions']) ?></p>
                                        </div>
                                    </div>
                                    <div class="px-2 py-2 mt-4">
                                        <p class="mb-1 fs-12">Canada <span class="float-end">75%</span></p>
                                        <div class="progress mt-2" style="height: 6px;">
                                            <div class="progress-bar progress-bar-striped bg-primary" role="progressbar"
                                                 style="width: 75%" aria-valuenow="75" aria-valuemin="0"
                                                 aria-valuemax="75"></div>
                                        </div>

                                        <p class="mt-3 mb-1 fs-12">Greenland <span class="float-end">47%</span>
                                        </p>
                                        <div class="progress mt-2" style="height: 6px;">
                                            <div class="progress-bar progress-bar-striped bg-primary" role="progressbar"
                                                 style="width: 47%" aria-valuenow="47" aria-valuemin="0"
                                                 aria-valuemax="47"></div>
                                        </div>

                                        <p class="mt-3 mb-1 fs-12">Russia <span class="float-end">82%</span></p>
                                        <div class="progress mt-2" style="height: 6px;">
                                            <div class="progress-bar progress-bar-striped bg-primary" role="progressbar"
                                                 style="width: 82%" aria-valuenow="82" aria-valuemin="0"
                                                 aria-valuemax="82"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                    <div class="col-8">
                        <div class="card crm-widget">
                            <div class="card-body p-0">
                                <div class="row row-cols-md-3 row-cols-1">
                                    <div class="col col-lg border-end">
                                        <div class="py-2 px-3">
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0">
                                                    <i class="mdi mdi-account-group-outline fs-24 text-muted"></i>
                                                </div>
                                                <div class="flex-grow-1 ms-3">
                                                    <h2 class="mb-0 fs-24"><?= $teamPlayerData['total'] ?></h2>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col col-lg border-end">
                                        <div class="py-2 px-3">
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0">
                                                    <i class="mdi mdi-shield-outline fs-24 text-success"></i>
                                                </div>
                                                <div class="flex-grow-1 ms-3">
                                                    <h2 class="mb-0 fs-24"><?= $teamPlayerData['Defenders']['averageRating'] ?></h2>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col col-lg border-end">
                                        <div class="py-2 px-3">
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0">
                                                    <i class="mdi mdi-target fs-24 text-primary"></i>
                                                </div>
                                                <div class="flex-grow-1 ms-3">
                                                    <h2 class="mb-0 fs-24"><?= $teamPlayerData['Midfielders']['averageRating'] ?></h2>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col col-lg border-end">
                                        <div class="py-2 px-3">
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0">
                                                    <i class="mdi mdi-soccer fs-24 text-danger"></i>
                                                </div>
                                                <div class="flex-grow-1 ms-3">
                                                    <h2 class="mb-0 fs-24"><?= $teamPlayerData['Attackers']['averageRating'] ?></h2>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div><!-- end row -->
                            </div><!-- end card body -->
                        </div>
                        <div class="row">
                            <div class="col-7">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="table-responsive table-card">
                                            <table class="table align-middle table-nowrap mb-0">
                                                <thead class="table-light">
                                                <tr>
                                                    <th colspan="4">Lineup</th>
                                                </tr>
                                                </thead>
                                            </table>
                                            <div>
                                                <?php if (count($lineupPlayers) > 0) { ?>
                                                    <table class="table align-middle table-nowrap mb-0">
                                                        <tbody class="list form-check-all">
                                                        <?php foreach ($lineupPlayers as $item) { ?>
                                                            <tr style="background-color: <?= getBackgroundColor($item['ability']) ?>">
                                                                <td class="text-center"
                                                                    style="width: 10%;"><?= $item['best_position'] ?></td>
                                                                <td><?= $item['name'] ?></td>
                                                                <td class="text-center"><?= $item['ability'] ?></td>
                                                                <td class="text-center" style="width: 15%;">
                                                                    <div class="progress">
                                                                        <div class="progress-bar bg-success"
                                                                             role="progressbar"
                                                                             style="width: 25%" aria-valuenow="25"
                                                                             aria-valuemin="0"
                                                                             aria-valuemax="100"></div>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        <?php } ?>
                                                        </tbody>
                                                    </table>
                                                <?php } else { ?>
                                                    <div class="text-muted">Find player and register they into your
                                                        team
                                                    </div>
                                                    <a href="<?= home_url('football-manager/transfer') ?>"
                                                       class="btn btn-soft-primary mt-2">Go to Market <i
                                                                class="ri-arrow-right-line ms-1 align-middle"></i></a>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-5">
                                <div class="card">
                                    <div class="card-body p-0">
                                        <table class="table align-middle table-nowrap mb-0">
                                            <thead class="table-light">
                                            <tr>
                                                <th colspan="4">Subtitle</th>
                                            </tr>
                                            </thead>
                                        </table>
                                        <div class="p-3">
                                            <?php if (count($subPlayers) > 0) { ?>
                                                <div class="table-responsive table-card" data-simplebar
                                                     style="max-height: 442px;">
                                                    <table class="table align-middle table-nowrap mb-0">
                                                        <tbody class="list form-check-all">
                                                        <?php foreach ($subPlayers as $index => $item) { ?>
                                                            <tr style="background-color: <?= getBackgroundColor($item['ability']) ?>">
                                                                <td class="text-center"
                                                                    style="width: 10%;"><?= $item['best_position'] ?></td>
                                                                <td><?= $item['name'] ?></td>
                                                                <td class="text-center"><?= $item['ability'] ?></td>
                                                                <td class="text-center" style="width: 20%;">
                                                                    <div class="progress">
                                                                        <div class="progress-bar bg-success"
                                                                             role="progressbar"
                                                                             style="width: 25%" aria-valuenow="25"
                                                                             aria-valuemin="0"
                                                                             aria-valuemax="100"></div>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        <?php } ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            <?php } else { ?>
                                                <div class="text-muted">Find player and register they into your team
                                                </div>
                                                <a href="<?= home_url('football-manager/transfer') ?>"
                                                   class="btn btn-soft-primary mt-2">Go to Market <i
                                                            class="ri-arrow-right-line ms-1 align-middle"></i></a>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex justify-center">
                                    <button class="btn btn-light me-2">Reset</button>
                                    <button class="btn btn-success">Save</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } else { ?>
                <div class="card">
                    <div class="card-body text-center py-5">
                        <h3>Please register a team</h3>
                        <a href="<?= home_url('football-manager') ?>" class="btn btn-link">Back to home <i
                                    class="ri-arrow-right-line ms-1 align-middle"></i></a>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
    <!--end col-->
    </div>

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
            name: '" . $myTeam['team_name'] . "',
            formation: '" . $myTeam['formation'] . "',
            score: 0,
            players: " . json_encode($lineupPlayers) . ",
            bench: " . json_encode($subPlayers) . ",
        }
        const groupTeams = [team1];
        const pitchX = 50 * groupTeams.length;

        const positionGroups = " . json_encode($positionGroupsExtra) . ";
    </script>
    <script src='" . home_url("/assets/js/pages/football-manager-formation.js") . "'></script>
    <script src='" . home_url("/assets/js/pages/football-manager.js") . "'></script>
    <script src='" . home_url("/assets/js/pages/football-manager-club.js") . "'></script>
";
$additionJs = ob_get_clean();

include 'layout.php';
