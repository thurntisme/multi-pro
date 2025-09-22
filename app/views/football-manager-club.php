<?php
$pageTitle = "Football Manager - My Club";

require_once DIR . '/functions/generate-player.php';
require_once DIR . '/controllers/FootballTeamController.php';

$footballTeamController = new FootballTeamController();

$lineupPlayers = [];
$subPlayers = [];
$myTeam = $footballTeamController->getMyTeamInClub();
if ($myTeam['players']) {
    $lineupPlayers = array_slice($myTeam['players'], 0, 11);
    $subPlayers = array_slice($myTeam['players'], 11);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action_name'])) {
        if ($_POST['action_name'] === 'update_my_club') {
            $footballTeamController->updateMyClub();
        }
        if ($_POST['action_name'] === 'renew_contract') {
            $footballTeamController->renewPlayerContract($_POST['player_id'], $_POST['player_name']);
        }
        if ($_POST['action_name'] === 'terminate_contract') {
            $footballTeamController->terminatePlayerContract($_POST['player_id'], $_POST['player_name']);
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
                                                    data-choices-sorting-false data-choices-search-false id="formation"
                                                    value="<?= $myTeam['formation'] ?>">
                                                    <?php foreach (DEFAULT_FOOTBALL_FORMATION as $formation): ?>
                                                        <option value="<?= $formation['slug']; ?>"
                                                            <?= $formation['slug'] === $myTeam['formation'] ? 'selected' : ''; ?>>
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
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-center">
                        <form action="<?= $_SERVER['REQUEST_URI'] ?>" method="post" id="my-club-form">
                            <input type="hidden" name="action_name" value="update_my_club">
                            <input type="hidden" name="team_formation" value="<?= $myTeam['formation'] ?>">
                            <input type="hidden" name="team_players" value="">
                            <button class="btn btn-success" type="submit">Save</button>
                        </form>
                    </div>
                    <div class="card position-sticky mt-3" style="top: 100px;">
                        <div class="card-body pt-4 pb-2">
                            <div class="row">
                                <?php foreach ($playerItems as $item) { ?>
                                    <div class="col-3 text-center">
                                        <button class="btn btn-outline-light btn-player-action"
                                            data-confirm-text="<?= $item['msg'] ?>" data-item-uuid="<?= $item['uuid'] ?>">
                                            <img src="<?= App\Helpers\NetworkHelper::home_url('assets/images/football-manager/' . $item['image']) ?>"
                                                class="img-responsive avatar-xs" alt="" />
                                        </button>
                                        <div class="text-muted text-center mt-2 fw-500 fs-12">
                                            <?= formatCurrency($item['price']) ?></div>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-8">
                    <div class="row">
                        <div class="col-7">
                            <div class="card">
                                <div class="card-body">
                                    <div class="table-responsive table-card">
                                        <table class="table align-middle table-nowrap mb-0">
                                            <thead class="table-light">
                                                <tr>
                                                    <th colspan="4"
                                                        class="d-flex justify-content-between align-items-center">Lineup
                                                        <button class="btn btn-sm btn-info" id="btn-best-players">Best
                                                            Players
                                                        </button>
                                                    </th>
                                                </tr>
                                            </thead>
                                        </table>
                                        <?php if (count($lineupPlayers) > 0) { ?>
                                            <table class="table align-middle table-nowrap mb-0" id="lineup">
                                                <tbody class="list form-check-all">
                                                    <?php foreach ($lineupPlayers as $item) {
                                                        $player_ability = getPlayerAbility($item['position_in_formation'] ?? $item['ability'], $item['attributes']);
                                                        ?>
                                                        <tr style="background-color: <?= getBackgroundColor($player_ability) ?>"
                                                            class="my-club-player-row"
                                                            data-player-uuid="<?= $item['player_uuid'] ?>">
                                                            <td style="width: 10%;">
                                                                <span class="ps-2 position"
                                                                    style="border-left: solid 4px <?= getPositionColor($item['position_in_formation'] ?? $item['best_position']) ?>">
                                                                    <?= $item['position_in_formation'] ?? $item['best_position'] ?></span>
                                                            </td>
                                                            <td>
                                                                <div class="d-flex align-items-center">
                                                                    <div class="me-auto">
                                                                        <?= $item['name'] ?>
                                                                        <?php if ($item['is_lock'] === 1) { ?>
                                                                            <i class="bx bxs-lock-alt me-2"></i>
                                                                        <?php } ?>
                                                                    </div>
                                                                    <?= displayContractAlert($item['remaining_contract_date']); ?>
                                                                    <div class="hstack btn-action">
                                                                        <button class="btn btn-info btn-sm btn-search ms-1">
                                                                            <i class="las la-search fs-14"></i>
                                                                        </button>
                                                                        <button class="btn btn-success btn-sm btn-change ms-1">
                                                                            <i class="las la-exchange-alt fs-14"></i>
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td class="text-center" style="width: 42px;">
                                                                <span
                                                                    class="form"><?php echo getFormDirection($item['player_form']) ?></span>
                                                            </td>
                                                            <td class="text-center"><span
                                                                    class="ability"><?= $player_ability ?></span>
                                                            </td>
                                                            <td class="text-center player_stamina" style="width: 15%;">
                                                                <?php if ($item['is_injury']) { ?>
                                                                    <span
                                                                        class="btn rounded-pill btn-danger waves-effect waves-light mx-auto player-alert">
                                                                        <i class="bx bx-first-aid text-white"></i>
                                                                    </span>
                                                                <?php } else { ?>
                                                                    <div class="progress">
                                                                        <div class="progress-bar bg-success" role="progressbar"
                                                                            style="width: <?= $item['player_stamina'] ?? 100 ?>%"
                                                                            aria-valuenow="<?= $item['player_stamina'] ?? 100 ?>"
                                                                            aria-valuemin="0" aria-valuemax="100"></div>
                                                                    </div>
                                                                <?php } ?>
                                                            </td>
                                                        </tr>
                                                    <?php } ?>
                                                </tbody>
                                            </table>
                                        <?php } else { ?>
                                            <div class="p-3">
                                                <div class="text-muted">Find players and register them for your
                                                    team.
                                                </div>
                                                <a href="<?= App\Helpers\NetworkHelper::home_url('app/football-manager/transfer') ?>"
                                                    class="btn btn-soft-primary mt-2">Go to Market <i
                                                        class="ri-arrow-right-line ms-1 align-middle"></i></a>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body p-0">
                                    <table class="table align-middle table-nowrap mb-0" id="substitute">
                                        <thead class="table-light">
                                            <tr>
                                                <th colspan="4">Substitute (<?= count($subPlayers) ?>)</th>
                                            </tr>
                                        </thead>
                                    </table>
                                    <div class="p-3">
                                        <?php if (count($subPlayers) > 0) { ?>
                                            <div class="table-responsive table-card" data-simplebar style="max-height: 261px;">
                                                <table class="table align-middle table-nowrap mb-0" id="subtitle">
                                                    <tbody class="list form-check-all">
                                                        <?php foreach ($subPlayers as $index => $item) { ?>
                                                            <tr style="background-color: <?= getBackgroundColor($item['ability']) ?>"
                                                                class="my-club-player-row"
                                                                data-player-uuid="<?= $item['player_uuid'] ?>">
                                                                <td style="width: 10%;">
                                                                    <span class="ps-2 position"
                                                                        style="border-left: solid 4px <?= getPositionColor($item['best_position']) ?>">
                                                                        <?= $item['best_position'] ?></span>
                                                                </td>
                                                                <td>
                                                                    <div class="d-flex align-items-center">
                                                                        <div class="me-auto">
                                                                            <?= $item['name'] ?>
                                                                            <?php if ($item['is_lock'] === 1) { ?>
                                                                                <i class="bx bxs-lock-alt me-2"></i>
                                                                            <?php } ?>
                                                                        </div>
                                                                        <?= displayContractAlert($item['remaining_contract_date']); ?>
                                                                        <div class="hstack btn-action">
                                                                            <button class="btn btn-info btn-sm btn-search ms-1">
                                                                                <i class="las la-search fs-14"></i>
                                                                            </button>
                                                                            <button class="btn btn-success btn-sm btn-change ms-1">
                                                                                <i class="las la-exchange-alt fs-14"></i>
                                                                            </button>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td class="text-center" style="width: 42px;">
                                                                    <?php echo getFormDirection($item['player_form']) ?>
                                                                </td>
                                                                <td class="text-center"><span
                                                                        class="ability"><?= $item['ability'] ?></span>
                                                                </td>
                                                                <td class="text-center" style="width: 15%;">
                                                                    <?php if ($item['is_injury']) { ?>
                                                                        <span
                                                                            class="btn rounded-pill btn-danger waves-effect waves-light mx-auto player-alert">
                                                                            <i class="bx bx-first-aid text-white"></i>
                                                                        </span>
                                                                    <?php } else { ?>
                                                                        <div class="progress">
                                                                            <div class="progress-bar bg-success" role="progressbar"
                                                                                style="width: <?= $item['player_stamina'] ?? 100 ?>%"
                                                                                aria-valuenow="<?= $item['player_stamina'] ?? 100 ?>"
                                                                                aria-valuemin="0" aria-valuemax="100"></div>
                                                                        </div>
                                                                    <?php } ?>
                                                                </td>
                                                            </tr>
                                                        <?php } ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        <?php } else { ?>
                                            <div class="text-muted">Find players and register them for your team.
                                            </div>
                                            <a href="<?= App\Helpers\NetworkHelper::home_url('app/football-manager/transfer') ?>"
                                                class="btn btn-soft-primary mt-2">Go to Market <i
                                                    class="ri-arrow-right-line ms-1 align-middle"></i></a>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-5">
                            <?php if ($lineupPlayers) { ?>
                                <div class="card position-sticky" style="top: 100px;">
                                    <div class="card-body p-4" id="player-info">
                                        <div class="row">
                                            <div class="col-6 text-center">
                                                <div class="d-flex align-items-center justify-content-center mb-3">
                                                    <span class="text-muted fs-12 me-1">Level: </span>
                                                    <span class="fs-24"
                                                        id="player-level-num"><?= $lineupPlayers[0]['level']['num'] ?></span>
                                                </div>
                                                <div class="progress progress-sm mb-4 w-75 mx-auto">
                                                    <div class="progress-bar bg-warning" id="player-level-percent"
                                                        role="progressbar"
                                                        style="width: <?= $lineupPlayers[0]['level']['percentageToNextLevel'] ?>%"
                                                        aria-valuenow="<?= $lineupPlayers[0]['level']['percentageToNextLevel'] ?>"
                                                        aria-valuemin="0" aria-valuemax="100"></div>
                                                </div>
                                            </div>
                                            <div class="col-6 text-center">
                                                <h5 class="fs-14 mb-1" id="player-name"><?= $lineupPlayers[0]['name'] ?></h5>
                                                <p class="text-muted mb-0 fs-12 mb-1" id="player-nationality">
                                                    <?= $lineupPlayers[0]['nationality'] ?></p>
                                                <p class="text-muted mb-0 fs-12"><span
                                                        id="player-best_position"><?= $lineupPlayers[0]['best_position'] ?></span>
                                                    (<span id="player-ability"><?= $lineupPlayers[0]['ability'] ?></span>)
                                                    |
                                                    <span
                                                        id="player-playable_positions"><?= implode(", ", $lineupPlayers[0]['playable_positions']) ?></span>
                                                </p>
                                            </div>
                                        </div>
                                        <div class="px-2 py-2 mt-2">
                                            <?php
                                            $results = [];
                                            foreach ($lineupPlayers[0]['attributes'] as $category => $attributes) {
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
                                                <div class="progress-bar progress-bar-striped bg-primary" role="progressbar"
                                                    style="width: <?= $results['mental']['percentage'] ?>%"
                                                    aria-valuenow="<?= $results['mental']['percentage'] ?>" aria-valuemin="0"
                                                    aria-valuemax="<?= $results['mental']['percentage'] ?>" id="mental-value">
                                                </div>
                                            </div>

                                            <p class="mt-3 mb-1 fs-12">Physical <span class="float-end"><span
                                                        id="physical-label"><?= $results['physical']['sum'] ?></span></span>
                                            </p>
                                            <div class="progress mt-2" style="height: 6px;">
                                                <div class="progress-bar progress-bar-striped bg-primary" role="progressbar"
                                                    style="width: <?= $results['physical']['percentage'] ?>%"
                                                    aria-valuenow="<?= $results['physical']['percentage'] ?>" aria-valuemin="0"
                                                    aria-valuemax="<?= $results['physical']['percentage'] ?>"
                                                    id="physical-value"></div>
                                            </div>

                                            <p class="mt-3 mb-1 fs-12">Technical <span class="float-end"><span
                                                        id="technical-label"><?= $results['technical']['sum'] ?></span></span>
                                            </p>
                                            <div class="progress mt-2" style="height: 6px;">
                                                <div class="progress-bar progress-bar-striped bg-primary" role="progressbar"
                                                    style="width: <?= $results['technical']['percentage'] ?>%"
                                                    aria-valuenow="<?= $results['technical']['percentage'] ?>" aria-valuemin="0"
                                                    aria-valuemax="<?= $results['technical']['percentage'] ?>"
                                                    id="technical-value"></div>
                                            </div>

                                            <p class="mt-3 mb-1 fs-12">Goalkeeping <span class="float-end"><span
                                                        id="goalkeeping-label"><?= $results['goalkeeping']['sum'] ?></span></span>
                                            </p>
                                            <div class="progress mt-2" style="height: 6px;">
                                                <div class="progress-bar progress-bar-striped bg-primary" role="progressbar"
                                                    style="width: <?= $results['goalkeeping']['percentage'] ?>%"
                                                    aria-valuenow="<?= $results['goalkeeping']['percentage'] ?>"
                                                    aria-valuemin="0"
                                                    aria-valuemax="<?= $results['goalkeeping']['percentage'] ?>"
                                                    id="goalkeeping-value"></div>
                                            </div>
                                        </div>
                                        <div class="row mt-4">
                                            <ul class="list-group px-3">
                                                <li class="list-group-item hstack gap-2">
                                                    <div class="w-50 d-flex justify-content-between">
                                                        <span class="fs-12">Age</span>
                                                        <span class="fs-12"
                                                            id="player-age"><?= $lineupPlayers[0]['age'] ?></span>
                                                    </div>
                                                    |
                                                    <div class="w-50 d-flex justify-content-between">
                                                        <span class="fs-12">Shirt</span>
                                                        <span class="fs-12"
                                                            id="player-shirt_number"><?= $lineupPlayers[0]['shirt_number'] ?></span>
                                                    </div>
                                                </li>
                                                <li class="list-group-item hstack gap-2">
                                                    <div class="w-50 d-flex justify-content-between">
                                                        <span class="fs-12">Height</span>
                                                        <span class="fs-12"><span
                                                                id="player-height"><?= $lineupPlayers[0]['height'] ?></span>
                                                            cm</span>
                                                    </div>
                                                    |
                                                    <div class="w-50 d-flex justify-content-between">
                                                        <span class="fs-12">Weight</span>
                                                        <span class="fs-12"><span
                                                                id="player-weight"><?= $lineupPlayers[0]['weight'] ?></span>
                                                            kg</span>
                                                    </div>
                                                </li>
                                                <li class="list-group-item hstack gap-2">
                                                    <div class="w-100 d-flex justify-content-between">
                                                        <span class="fs-12">Nationality</span>
                                                        <span class="fs-12"
                                                            id="player-nationality"><?= $lineupPlayers[0]['nationality'] ?></span>
                                                    </div>
                                                </li>
                                                <li class="list-group-item hstack gap-2">
                                                    <div class="w-100 d-flex justify-content-between">
                                                        <span class="fs-12">Market Value</span>
                                                        <span class="fs-12"
                                                            id="player-market_value"><?= $lineupPlayers[0]['market_value'] ?></span>
                                                    </div>
                                                </li>
                                                <li class="list-group-item hstack gap-2">
                                                    <div class="w-100 d-flex justify-content-between">
                                                        <span class="fs-12">Contract Wage</span>
                                                        <span class="fs-12"
                                                            id="player-contract_wage"><?= $lineupPlayers[0]['contract_wage'] ?></span>
                                                    </div>
                                                </li>
                                                <li class="list-group-item hstack gap-2">
                                                    <div class="w-100 d-flex justify-content-between">
                                                        <span class="fs-12">Contract End Date</span>
                                                        <span class="fs-12"
                                                            id="player-contract_end_date"><?= $lineupPlayers[0]['contract_end_date'] ?></span>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="mt-3 hstack gap-2 justify-content-between px-1">
                                            <form method="POST" action="<?= $_SERVER['REQUEST_URI'] ?>">
                                                <input type="hidden" name="action_name" value="renew_contract">
                                                <input type="hidden" name="player_id" class="contract-player-id"
                                                    value="<?= $lineupPlayers[0]['id'] ?>">
                                                <input type="hidden" name="player_name" class="contract-player-name"
                                                    value="<?= $lineupPlayers[0]['name'] ?>">
                                                <button type="submit" data-confirm-text="Do you want to renew contract?"
                                                    class="btn btn-info btn-sm btn-label waves-effect waves-light btn-confirm-action">
                                                    <i class="ri-check-double-line label-icon align-middle fs-16 me-2"></i>
                                                    Renew Contract
                                                </button>
                                            </form>
                                            <form method="POST" action="<?= $_SERVER['REQUEST_URI'] ?>">
                                                <input type="hidden" name="action_name" value="terminate_contract">
                                                <input type="hidden" name="player_id" class="contract-player-id"
                                                    value="<?= $lineupPlayers[0]['id'] ?>">
                                                <input type="hidden" name="player_name" class="contract-player-name"
                                                    value="<?= $lineupPlayers[0]['name'] ?>">
                                                <button type="submit"
                                                    data-confirm-text="Terminating this player's, are you sure you want to proceed?"
                                                    class="btn btn-danger btn-sm btn-label waves-effect waves-light btn-confirm-action">
                                                    <i class="ri-check-double-line label-icon align-middle fs-16 me-2"></i>
                                                    Terminate Contract
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php } else { ?>
            <div class="card">
                <div class="card-body text-center py-5">
                    <h3>Please register a team</h3>
                    <a href="<?= App\Helpers\NetworkHelper::home_url('football-manager') ?>" class="btn btn-link">Back to
                        home <i class="ri-arrow-right-line ms-1 align-middle"></i></a>
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
        let apiUrl = '" . home_url("/api") . "';
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
            playerSelected: null,
        }
        const groupTeams = [team1];
        const pitchX = 50;

        const positionGroups = " . json_encode($positionGroupsExtra) . ";
        const allBasePlayers = " . json_encode($myTeam['players']) . ";
        const allPlayers = " . json_encode($myTeam['players']) . ";
    </script>
    <script src='" . home_url("/assets/js/pages/football-manager-formation.js") . "'></script>
    <script src='" . home_url("/assets/js/pages/football-manager.js") . "'></script>
    <script src='" . home_url("/assets/js/pages/football-manager-club.js") . "'></script>
";
$additionJs = ob_get_clean();
