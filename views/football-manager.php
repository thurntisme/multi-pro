<?php

global $user_id;
$pageTitle = "Football Manager";

require_once DIR . '/controllers/FootballMatchController.php';
require_once DIR . '/controllers/FootballTeamController.php';
require_once DIR . '/controllers/FootballPlayerController.php';

$footballMatchController = new FootballMatchController();
$footballTeamController = new FootballTeamController();
$footballPlayerController = new FootballPlayerController();
$myTeam = $footballTeamController->getMyTeam();
$mySchedule = $footballMatchController->getMatch();
$rcm_players = $footballTeamController->getRecommendPlayer($myTeam['formation']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action_name'])) {
        if ($_POST['action_name'] === 'game_on') {
            $footballMatchController->gameOn();
        }
        if ($_POST['action_name'] === 'create_match') {
            $footballMatchController->createMatch();
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
    <?php if (!empty($myTeam)) { ?>
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <div class="card-title mb-0">
                        Recommend Players
                    </div>
                </div>
                <div class="card-body">
                    <div class="list-group" data-simplebar style="max-height: 560px;">
                        <?php foreach ($rcm_players as $player) { ?>
                            <div class="list-group-item list-group-item-action">
                                <div class="float-end">
                                    <div class="d-flex flex-column align-items-end">
                                        <span class="fs-12"><?= formatCurrency($player['market_value']) ?></span>
                                        <a href="<?= home_url('app/football-manager/transfer?s=' . urlencode($player['name'])) ?>"
                                            class="btn btn-sm btn-outline-success mt-1" style="width: 30px"><i class="ri ri-search-2-line"></i>
                                        </a>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        <img src="assets/images/users/avatar-1.jpg" alt=""
                                            class="avatar-sm rounded-circle" />
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h5 class="list-title fs-14 mb-1"><?= $player['name'] ?></h5>
                                        <p class="list-text mb-0 fs-12"><?= $player['best_position'] ?>
                                            | <?= $player['ability'] ?>
                                            | <?= implode(", ", $player['playable_positions']) ?></p>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0 text-center">Next Match</h4>
                </div>
                <div class="card-body text-center">
                    <?php if (!empty($mySchedule)) { ?>
                        <div class="row py-3 mb-2">
                            <div class="col-6">
                                <div class="avatar-sm mx-auto mb-3">
                                    <div class="avatar-title bg-success-subtle text-success fs-17 rounded">
                                        <i class="ri-add-line"></i>
                                    </div>
                                </div>
                                <h4 class="card-title"><?= $mySchedule['home_team'] ?></h4>
                            </div>
                            <div class="col-6">
                                <div class="avatar-sm mx-auto mb-3">
                                    <div class="avatar-title bg-success-subtle text-success fs-17 rounded">
                                        <i class="ri-add-line"></i>
                                    </div>
                                </div>
                                <h4 class="card-title"><?= $mySchedule['away_team'] ?></h4>
                            </div>
                        </div>
                        <form method="POST" action="<?= $_SERVER['REQUEST_URI'] ?>">
                            <input type="hidden" name="action_name" value="game_on">
                            <div class="text-center">
                                <button type="submit" class="btn btn-success">Game on</button>
                            </div>
                        </form>
                    <?php } else { ?>
                        <div class="row py-3 mb-2">
                            <form method="POST" action="<?= $_SERVER['REQUEST_URI'] ?>">
                                <input type="hidden" name="action_name" value="create_match">
                                <div class="text-center">
                                    <button type="submit" class="btn btn-primary">Create Match</button>
                                </div>
                            </form>
                        </div>
                    <?php } ?>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0 text-center">Challenge Match</h4>
                </div>
                <div class="card-body text-center">
                    <p class="card-text py-2 mb-3">You must pay <b>$2,000</b> to participate in the challenge match.
                        If
                        you win, you'll take home <b>$2,800</b>!</p>
                    <button class="btn btn-soft-primary">Challenge Now</button>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card card-height-100">
                <div class="card-header">
                    <div class="card-title mb-0">
                        My Player
                    </div>
                </div>
                <div class="card-body p-0">
                    <table class="table align-middle table-nowrap mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="text-center fs-12 px-0" style="width: 28px">#</th>
                                <th class="fs-12">Name</th>
                                <th class="text-center fs-12 px-1" style="width: 52px">Match</th>
                                <th class="text-center fs-12 px-1" style="width: 52px">Goals</th>
                                <th class="text-center fs-12 px-1" style="width: 52px">Assists</th>
                            </tr>
                        </thead>
                    </table>
                    <div data-simplebar style="max-height: 560px;">
                        <table class="table align-middle table-nowrap mb-0">
                            <tbody>
                                <?php if (!empty($myTeam['players'])) {
                                    foreach ($myTeam['players'] as $index => $player) { ?>
                                        <tr>
                                            <td class="text-center fs-12 px-0" style="width: 28px"><?= $index + 1 ?></td>
                                            <td class="fs-12"><?= $player['name'] ?></td>
                                            <td class="text-center fs-12 px-1" style="width: 52px"><?= $player['match_played'] ?></td>
                                            <td class="text-center fs-12 px-1" style="width: 52px"><?= $player['goals_scored'] ?></td>
                                            <td class="text-center fs-12 px-1" style="width: 52px"><?= $player['assists'] ?></td>
                                        </tr>
                                <?php }
                                } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    <?php } else { ?>
        <div class="col-lg-4 col-md-6 offset-lg-4 offset-md-3 mt-3">
            <div class="card">
                <div class="card-body">
                    <form method="POST" action="<?= $_SERVER['REQUEST_URI'] ?>">
                        <div class="mb-3">
                            <label for="team_name" class="form-label">Team Name</label>
                            <input type="text" class="form-control" name="team_name" id="team_name"
                                placeholder="Enter your team name">
                            <input type="hidden" name="action_name" value="add_team">
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary">Create Team</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <?php } ?>
</div>

<?php
$pageContent = ob_get_clean();
