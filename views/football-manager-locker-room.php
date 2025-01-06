<?php
$pageTitle = "Football Manager - Locker Room";

require_once DIR . '/functions/generate-player.php';
require_once DIR . '/controllers/FootballTeamController.php';
require_once DIR . '/controllers/FootballPlayerController.php';

$footballPlayerController = new FootballPlayerController();
$footballTeamController = new FootballTeamController();

$myTeam = $footballTeamController->getMyTeam();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action_name'])) {
        if ($_POST['action_name'] === 'update_my_club') {
            $footballTeamController->updateMyClub();
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
        <?php if ($myTeam) { ?>
            <div class="row">
                <div class="col-8">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive table-card">
                                <table class="table align-middle table-nowrap mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="text-center px-1" style="width: 40px;">#</th>
                                            <th style="padding-left: 62px;">Name</th>
                                            <th class="text-center px-1" style="width: 52px;">Shirt</th>
                                            <th class="text-center px-1" style="width: 52px;">Ability</th>
                                            <th class="text-center px-1" style="width: 52px;">RCD</th>
                                            <th class="text-center px-1" style="width: 90px;">Stamina</th>
                                        </tr>
                                    </thead>
                                </table>
                                <?php if (count($myTeam['players']) > 0) { ?>
                                    <div data-simplebar style="max-height: 560px;">
                                        <table class="table align-middle table-nowrap mb-0" id="lineup">
                                            <tbody class="list form-check-all">
                                                <?php foreach ($myTeam['players'] as $index => $item) { ?>
                                                    <tr class="my-club-player-row"
                                                        data-player-uuid="<?= $item['player_uuid'] ?>">
                                                        <td class="text-center px-1" style="width: 40px;"><?= $index + 1 ?></td>
                                                        <td class="d-flex align-items-center px-1">
                                                            <span class="ps-2"
                                                                style="width: 58px; border-left: solid 4px <?= getPositionColor($item['best_position']) ?>"> <?= $item['best_position'] ?></span><?= $item['name'] ?>
                                                        </td>
                                                        <td class="text-center px-1" style="width: 52px;"><?= $item['shirt_number'] ?></td>
                                                        <td class="text-center px-1" style="width: 52px;"><?= $item['ability'] ?></td>
                                                        <td class="text-center px-1" style="width: 52px;"><?= $item['remaining_contract_date'] > 0 ? $item['remaining_contract_date'] : 0 ?></td>
                                                        <td class="text-center" style="width: 90px;">
                                                            <div class="progress">
                                                                <div class="progress-bar bg-success"
                                                                    role="progressbar"
                                                                    style="width: <?= $item['stamina'] ?? 100 ?>%"
                                                                    aria-valuenow="<?= $item['stamina'] ?? 100 ?>"
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
                                    <div class="p-3">
                                        <div class="text-muted">Find player and register they into your
                                            team
                                        </div>
                                        <a href="<?= home_url('football-manager/transfer') ?>"
                                            class="btn btn-soft-primary mt-2">Go to Market <i
                                                class="ri-arrow-right-line ms-1 align-middle"></i></a>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-center">
                        <button class="btn btn-light me-2">Reset</button>
                        <form action="<?= $_SERVER['REQUEST_URI'] ?>" method="post" id="my-club-form">
                            <input type="hidden" name="action_name" value="update_my_club">
                            <input type="hidden" name="team_formation" value="<?= $myTeam['formation'] ?>">
                            <input type="hidden" name="team_players" value="">
                            <button class="btn btn-success" type="submit">Save</button>
                        </form>
                    </div>
                </div>
                <div class="col-4">
                    <?php if ($myTeam['players']) { ?>
                        <div class="card position-sticky" style="top: 100px;">
                            <div class="card-body p-4" id="player-info">
                                <div class="row">
                                    <div class="col-6 text-center">
                                        <div class="d-flex align-items-center justify-content-center mb-3">
                                            <span class="text-muted fs-12 me-1">Level: </span>
                                            <span class="fs-24"
                                                id="player-level-num"><?= $myTeam['players'][0]['level']['num'] ?></span>
                                        </div>
                                        <div class="progress progress-sm mb-4 w-75 mx-auto">
                                            <div class="progress-bar bg-warning" id="player-level-percent"
                                                role="progressbar"
                                                style="width: <?= $myTeam['players'][0]['level']['percentageToNextLevel'] ?>%"
                                                aria-valuenow="<?= $myTeam['players'][0]['level']['percentageToNextLevel'] ?>"
                                                aria-valuemin="0"
                                                aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                    <div class="col-6 text-center">
                                        <h5 class="fs-14 mb-1"
                                            id="player-name"><?= $myTeam['players'][0]['name'] ?></h5>
                                        <p class="text-muted mb-0 fs-12 mb-1"
                                            id="player-nationality"><?= $myTeam['players'][0]['nationality'] ?></p>
                                        <p class="text-muted mb-0 fs-12"><span
                                                id="player-best_position"><?= $myTeam['players'][0]['best_position'] ?></span>
                                            (<span id="player-ability"><?= $myTeam['players'][0]['ability'] ?></span>)
                                            |
                                            <span id="player-playable_positions"><?= implode(", ", $myTeam['players'][0]['playable_positions']) ?></span>
                                        </p>
                                    </div>
                                </div>
                                <div class="px-2 py-2 mt-2">
                                    <?php
                                    $results = [];
                                    foreach ($myTeam['players'][0]['attributes'] as $category => $attributes) {
                                        $sum = array_sum($attributes);
                                        $maxPossible = 120 * count($attributes);
                                        $percentage = ($sum / $maxPossible) * 100;
                                        $results[$category] = [
                                            'sum' => $sum,
                                            'percentage' => round($percentage, 2),
                                        ];
                                    }
                                    ?>
                                    <p class="mb-1 fs-12">Mental <span class="float-end"><span
                                                id="mental-label"><?= $results['mental']['sum'] ?></span></span>
                                    </p>
                                    <div class="progress mt-2" style="height: 6px;">
                                        <div class="progress-bar progress-bar-striped bg-primary"
                                            role="progressbar"
                                            style="width: <?= $results['mental']['percentage'] ?>%"
                                            aria-valuenow="<?= $results['mental']['percentage'] ?>"
                                            aria-valuemin="0"
                                            aria-valuemax="<?= $results['mental']['percentage'] ?>"
                                            id="mental-value"></div>
                                    </div>

                                    <p class="mt-3 mb-1 fs-12">Physical <span class="float-end"><span
                                                id="physical-label"><?= $results['physical']['sum'] ?></span></span>
                                    </p>
                                    <div class="progress mt-2" style="height: 6px;">
                                        <div class="progress-bar progress-bar-striped bg-primary"
                                            role="progressbar"
                                            style="width: <?= $results['physical']['percentage'] ?>%"
                                            aria-valuenow="<?= $results['physical']['percentage'] ?>"
                                            aria-valuemin="0"
                                            aria-valuemax="<?= $results['physical']['percentage'] ?>"
                                            id="physical-value"></div>
                                    </div>

                                    <p class="mt-3 mb-1 fs-12">Technical <span class="float-end"><span
                                                id="technical-label"><?= $results['technical']['sum'] ?></span></span>
                                    </p>
                                    <div class="progress mt-2" style="height: 6px;">
                                        <div class="progress-bar progress-bar-striped bg-primary"
                                            role="progressbar"
                                            style="width: <?= $results['technical']['percentage'] ?>%"
                                            aria-valuenow="<?= $results['technical']['percentage'] ?>"
                                            aria-valuemin="0"
                                            aria-valuemax="<?= $results['technical']['percentage'] ?>"
                                            id="technical-value"></div>
                                    </div>
                                </div>
                                <div class="row mt-4">
                                    <ul class="list-group px-3">
                                        <li class="list-group-item hstack gap-2">
                                            <div class="w-50 d-flex justify-content-between">
                                                <span class="fs-12">Age</span>
                                                <span class="fs-12"
                                                    id="player-age"><?= $myTeam['players'][0]['age'] ?></span>
                                            </div>
                                            |
                                            <div class="w-50 d-flex justify-content-between">
                                                <span class="fs-12">Shirt</span>
                                                <span class="fs-12"
                                                    id="player-shirt_number"><?= $myTeam['players'][0]['shirt_number'] ?></span>
                                            </div>
                                        </li>
                                        <li class="list-group-item hstack gap-2">
                                            <div class="w-50 d-flex justify-content-between">
                                                <span class="fs-12">Height</span>
                                                <span class="fs-12"><span
                                                        id="player-height"><?= $myTeam['players'][0]['height'] ?></span> cm</span>
                                            </div>
                                            |
                                            <div class="w-50 d-flex justify-content-between">
                                                <span class="fs-12">Weight</span>
                                                <span class="fs-12"><span
                                                        id="player-weight"><?= $myTeam['players'][0]['weight'] ?></span> kg</span>
                                            </div>
                                        </li>
                                        <li class="list-group-item hstack gap-2">
                                            <div class="w-100 d-flex justify-content-between">
                                                <span class="fs-12">Nationality</span>
                                                <span class="fs-12"
                                                    id="player-nationality"><?= $myTeam['players'][0]['nationality'] ?></span>
                                            </div>
                                        </li>
                                        <li class="list-group-item hstack gap-2">
                                            <div class="w-100 d-flex justify-content-between">
                                                <span class="fs-12">Market Value</span>
                                                <span class="fs-12"
                                                    id="player-market_value"><?= $myTeam['players'][0]['market_value'] ?></span>
                                            </div>
                                        </li>
                                        <li class="list-group-item hstack gap-2">
                                            <div class="w-100 d-flex justify-content-between">
                                                <span class="fs-12">Contract Wage</span>
                                                <span class="fs-12"
                                                    id="player-contract_wage"><?= $myTeam['players'][0]['contract_wage'] ?></span>
                                            </div>
                                        </li>
                                        <li class="list-group-item hstack gap-2">
                                            <div class="w-100 d-flex justify-content-between">
                                                <span class="fs-12">Contract End Date</span>
                                                <span class="fs-12"
                                                    id="player-contract_end_date"><?= $myTeam['players'][0]['contract_end_date'] ?></span>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                                <div class="mt-3 hstack gap-2 justify-content-between px-1">
                                    <button type="button"
                                        class="btn btn-info btn-sm btn-label waves-effect waves-light">
                                        <i
                                            class="ri-check-double-line label-icon align-middle fs-16 me-2"></i>
                                        Renew Contract
                                    </button>
                                    <button type="button"
                                        class="btn btn-danger btn-sm btn-label waves-effect waves-light">
                                        <i
                                            class="ri-check-double-line label-icon align-middle fs-16 me-2"></i>
                                        Terminate Contract
                                    </button>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
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
        const allPlayers = " . json_encode($myTeam['players']) . ";
    </script>
    <script src='" . home_url("/assets/js/pages/football-manager-locker-room.js") . "'></script>
";
$additionJs = ob_get_clean();
