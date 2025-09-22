<?php
$pageTitle = "Football Manager - Locker Room";

require_once DIR . '/functions/generate-player.php';
require_once DIR . '/controllers/FootballTeamController.php';
require_once DIR . '/controllers/FootballPlayerController.php';

$footballPlayerController = new FootballPlayerController();
$footballTeamController = new FootballTeamController();

$myTeam = $footballTeamController->getMyTeamInClub();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action_name'])) {
        if ($_POST['action_name'] === 'update_my_players') {
            $footballTeamController->updateMyPlayers();
        }
    }
}

$keys = range(1, 99);
$values = range(1, 99);
$shirt_numbers = array_combine($keys, $values);

ob_start(); ?>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<?php
$additionCss = ob_get_clean();

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
    <div class="col-lg-12 pb-3">
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
                                            <th class="text-center px-1" style="width: 52px;">
                                                <img src="<?= App\Helpers\NetworkHelper::home_url('assets/images/football-manager/stamina.png') ?>"
                                                    class="img-responsive avatar-xxs" alt="stamina" />
                                            </th>
                                            <th class="text-center px-1" style="width: 52px;">
                                                <img src="<?= App\Helpers\NetworkHelper::home_url('assets/images/football-manager/star.png') ?>"
                                                    class="img-responsive avatar-xxs" alt="average score">
                                            </th>
                                            <th class="text-center px-1" style="width: 52px;">
                                                <img src="<?= App\Helpers\NetworkHelper::home_url('assets/images/football-manager/player-ability.png') ?>"
                                                    class="img-responsive avatar-xxs" alt="player ability" />
                                            </th>
                                            <th class="text-center px-1" style="width: 52px;">
                                                <img src="<?= App\Helpers\NetworkHelper::home_url('assets/images/football-manager/shirt.png') ?>"
                                                    class="img-responsive avatar-xxs" alt="shirt number" />
                                            </th>
                                            <th class="text-center px-1" style="width: 52px;">
                                                <img src="<?= App\Helpers\NetworkHelper::home_url('assets/images/football-manager/assist.png') ?>"
                                                    class="img-responsive avatar-xxs" alt="assist" />
                                            </th>
                                            <th class="text-center px-1" style="width: 52px;">
                                                <img src="<?= App\Helpers\NetworkHelper::home_url('assets/images/football-manager/goal.png') ?>"
                                                    class="img-responsive avatar-xxs" alt="goals" />
                                            </th>
                                            <th class="text-center px-1" style="width: 52px;">
                                                <img src="<?= App\Helpers\NetworkHelper::home_url('assets/images/football-manager/yellow-card.png') ?>"
                                                    class="img-responsive avatar-xxs" alt="yellow card" />
                                            </th>
                                            <th class="text-center px-1" style="width: 52px;">
                                                <img src="<?= App\Helpers\NetworkHelper::home_url('assets/images/football-manager/red-card.png') ?>"
                                                    class="img-responsive avatar-xxs" alt="red card" />
                                            </th>
                                            <th class="text-center px-1" style="width: 52px;">
                                                <img src="<?= App\Helpers\NetworkHelper::home_url('assets/images/football-manager/contract-time.png') ?>"
                                                    class="img-responsive avatar-xxs" alt="contract time" />
                                            </th>
                                            <th style="width: 80px;"></th>
                                        </tr>
                                    </thead>
                                </table>
                                <?php if (count($myTeam['players']) > 0) { ?>
                                    <div data-simplebar style="max-height: 560px;" id="tasksList">
                                        <table class="table align-middle table-nowrap mb-0" id="player-list">
                                            <tbody class="list form-check-all">
                                                <?php foreach ($myTeam['players'] as $index => $item) { ?>
                                                    <tr class="my-club-player-row" data-player-uuid="<?= $item['player_uuid'] ?>">
                                                        <td class="text-center px-1" style="width: 40px;"><?= $index + 1 ?></td>
                                                        <td class="d-flex align-items-center px-1">
                                                            <span class="ps-2"
                                                                style="width: 58px; border-left: solid 4px <?= getPositionColor($item['best_position']) ?>">
                                                                <?= $item['best_position'] ?></span>
                                                            <div class="d-flex align-items-center">
                                                                <div class="me-2"><?= $item['name'] ?>
                                                                </div>
                                                                <?php if ($item['is_lock'] === 1) { ?>
                                                                    <i class="bx bxs-lock-alt me-2"></i>
                                                                <?php } ?>
                                                                <ul class="list-inline tasks-list-menu mb-0 pe-4">
                                                                    <li class="list-inline-item">
                                                                        <a href="#"
                                                                            class="edit-item-btn cursor-pointer btn-player-detail"
                                                                            data-player-uuid="<?= $item['uuid'] ?>"
                                                                            data-player-name="<?= $item['name'] ?>"
                                                                            data-player-nationality="<?= $item['nationality'] ?>"
                                                                            data-player-meta="<?= $item['age'] ?> yrd | <?= $item['height'] ?> cm | <?= $item['weight'] ?> kg"
                                                                            data-player-positions="<?= $item['best_position'] . " (" . $item['ability'] . ") | " . implode(", ", $item['playable_positions']) ?>"
                                                                            data-player-attributes="<?= htmlspecialchars(json_encode($item['attributes'])) ?>"
                                                                            data-bs-toggle="modal"
                                                                            data-bs-target="#playerDetailBackdrop"><i
                                                                                class="ri-eye-fill align-bottom me-2 text-muted"></i></a>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </td>
                                                        <td class="text-center px-1" style="width: 52px;">
                                                            <?= $item['match_played'] ?></td>
                                                        <td class="text-center px-1" style="width: 52px;"><?= $item['avg_score'] ?>
                                                        </td>
                                                        <td class="text-center px-1" style="width: 52px;"><?= $item['ability'] ?>
                                                        </td>
                                                        <td class="text-center px-1" style="width: 52px;"><span
                                                                class="shirt_number"><?= $item['shirt_number'] ?></span>
                                                        </td>
                                                        <td class="text-center px-1" style="width: 52px;"><?= $item['assists'] ?>
                                                        </td>
                                                        <td class="text-center px-1" style="width: 52px;">
                                                            <?= $item['goals_scored'] ?></td>
                                                        <td class="text-center px-1" style="width: 52px;">
                                                            <?= $item['yellow_cards'] ?></td>
                                                        <td class="text-center px-1" style="width: 52px;"><?= $item['red_cards'] ?>
                                                        </td>
                                                        <td class="text-center px-1" style="width: 52px;">
                                                            <?= $item['remaining_contract_date'] > 0 ? $item['remaining_contract_date'] : 0 ?>
                                                        </td>
                                                        <td class="text-center" style="width: 80px;">
                                                            <?php if ($item['is_injury']) { ?>
                                                                <i class="bx bx-first-aid text-danger"></i>
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
                                    <div class="p-3">
                                        <div class="text-muted">Find player and register they into your
                                            team
                                        </div>
                                        <a href="<?= App\Helpers\NetworkHelper::home_url('football-manager/transfer') ?>"
                                            class="btn btn-soft-primary mt-2">Go to Market <i
                                                class="ri-arrow-right-line ms-1 align-middle"></i></a>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-center">
                        <button class="btn btn-light me-2">Reset</button>
                        <form action="<?= $_SERVER['REQUEST_URI'] ?>" method="post" id="my-players-form">
                            <input type="hidden" name="action_name" value="update_my_players">
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
                                            <div class="progress-bar bg-warning" id="player-level-percent" role="progressbar"
                                                style="width: <?= $myTeam['players'][0]['level']['percentageToNextLevel'] ?>%"
                                                aria-valuenow="<?= $myTeam['players'][0]['level']['percentageToNextLevel'] ?>"
                                                aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                    <div class="col-6 text-center">
                                        <h5 class="fs-14 mb-1" id="player-name"><?= $myTeam['players'][0]['name'] ?></h5>
                                        <p class="text-muted mb-0 fs-12 mb-1" id="player-nationality">
                                            <?= $myTeam['players'][0]['nationality'] ?></p>
                                        <p class="text-muted mb-0 fs-12"><span
                                                id="player-best_position"><?= $myTeam['players'][0]['best_position'] ?></span>
                                            (<span id="player-ability"><?= $myTeam['players'][0]['ability'] ?></span>)
                                            |
                                            <span
                                                id="player-playable_positions"><?= implode(", ", $myTeam['players'][0]['playable_positions']) ?></span>
                                        </p>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <ul class="list-group px-1">
                                        <li class="list-group-item hstack gap-2">
                                            <div class="w-50 d-flex justify-content-between">
                                                <span class="fs-12">Age</span>
                                                <span class="fs-12" id="player-age"><?= $myTeam['players'][0]['age'] ?></span>
                                            </div>
                                            |
                                            <div class="w-50 d-flex justify-content-between align-items-center">
                                                <span class="fs-12">Shirt</span>
                                                <span class="fs-12 ms-2" id="player-shirt">
                                                    <?= generateFormControl("shirt_number", "shirt_number", $myTeam['players'][0]['shirt_number'], "", "select", "", $shirt_numbers) ?></span>
                                            </div>
                                        </li>
                                        <li class="list-group-item hstack gap-2">
                                            <div class="w-50 d-flex justify-content-between">
                                                <span class="fs-12">Height</span>
                                                <span class="fs-12"><span
                                                        id="player-height"><?= $myTeam['players'][0]['height'] ?></span>
                                                    cm</span>
                                            </div>
                                            |
                                            <div class="w-50 d-flex justify-content-between">
                                                <span class="fs-12">Weight</span>
                                                <span class="fs-12"><span
                                                        id="player-weight"><?= $myTeam['players'][0]['weight'] ?></span>
                                                    kg</span>
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
                                        <li class="list-group-item hstack gap-2">
                                            <div class="w-100 d-flex justify-content-between">
                                                <span class="fs-12">Special Skills</span>
                                                <span class="fs-12"
                                                    id="player-special_skills"><?= implode(", ", $myTeam['players'][0]['special_skills']) ?></span>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-12 px-1">
                                        <div class="mb-3">
                                            <label for="choices-text-input" class="form-label">Player Roles</label>
                                            <select class="js-example-basic-multiple" name="player_roles[]" multiple="multiple">
                                                <?php foreach ($teamRoles as $key => $value) { ?>
                                                    <option value="<?= $key ?>"><?= $value ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
            <?php include_once DIR . '/components/football-player-detail-modal.php'; ?>
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
    <script src='https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js'></script>
    <script type='text/javascript'>
        $('.js-example-basic-multiple').select2();
        const allBasePlayers = " . json_encode($myTeam['players']) . ";
        const allPlayers = " . json_encode($myTeam['players']) . ";
    </script>
    <script src='" . home_url("/assets/js/pages/football-manager-player-detail.js") . "'></script>
    <script src='" . home_url("/assets/js/pages/football-manager-locker-room.js") . "'></script>
";
$additionJs = ob_get_clean();
