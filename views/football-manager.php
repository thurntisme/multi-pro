<?php

global $user_id;
$pageTitle = "Football Manager";

require_once DIR . '/functions/generate-player.php';
require_once DIR . '/controllers/FootballTeamController.php';
require_once DIR . '/controllers/FootballLeagueController.php';

// Generate 8 random players
$popular_players = generateRandomPlayers(8);
$commonController = new CommonController();

$footballTeamController = new FootballTeamController();
$footballLeagueController = new FootballLeagueController();
$teams = $footballTeamController->listTeams();

$curr_league = $footballLeagueController->getNewestLeague();
$schedule = $footballLeagueController->getMySchedule();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action_name'])) {
        if ($_POST['action_name'] === 'add_team') {
            $footballTeamController->createTeam();
        }
        if ($_POST['action_name'] === 'add_league') {
            $footballLeagueController->createLeague();
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
    <?php if (count($teams) > 0) { ?>
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <div class="card-title mb-0">
                        Popular Players
                    </div>
                </div>
                <div class="card-body">
                    <div class="list-group">
                        <?php foreach ($popular_players as $player) { ?>
                            <div class="list-group-item list-group-item-action">
                                <div class="float-end">
                                    <div class="d-flex flex-column align-items-end">
                                        <?= formatCurrency($player['market_value']) ?>
                                        <button class="btn btn-sm btn-outline-success mt-1" style="width: 40px">Buy
                                        </button>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        <img src="assets/images/users/avatar-1.jpg" alt=""
                                            class="avatar-sm rounded-circle" />
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h5 class="list-title fs-15 mb-1"><?= $player['name'] ?></h5>
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
            <?php if ($curr_league) { ?>
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title mb-0 text-center"><?= $curr_league['name'] ?></h4>
                        <div class="text-muted text-center mt-1 fs-12"><?= $curr_league['season'] ?></div>
                    </div>
                    <div class="card-body text-center">
                        <div class="row py-3 mb-2">
                            <div class="col-6">
                                <div class="avatar-sm mx-auto mb-3">
                                    <div class="avatar-title bg-success-subtle text-success fs-17 rounded">
                                        <i class="ri-add-line"></i>
                                    </div>
                                </div>
                                <h4 class="card-title"><?= $schedule['home']['team_name'] ?></h4>
                            </div>
                            <div class="col-6">
                                <div class="avatar-sm mx-auto mb-3">
                                    <div class="avatar-title bg-success-subtle text-success fs-17 rounded">
                                        <i class="ri-add-line"></i>
                                    </div>
                                </div>
                                <h4 class="card-title"><?= $schedule['away']['team_name'] ?></h4>
                            </div>
                        </div>
                        <a class="btn btn-success" href="<?= home_url('football-manager/match') ?>">Game On</a>
                    </div>
                </div>
            <?php } else { ?>
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title mb-0 text-center">League</h4>
                    </div>
                    <div class="card-body text-center">
                        <p class="card-text py-2 mb-3">No league found.<br />Please create a new league to get started!</p>
                        <form method="POST" action="<?= $_SERVER['REQUEST_URI'] ?>">
                            <input type="hidden" name="action_name" value="add_league">
                            <div class="text-center">
                                <button type="submit" class="btn btn-primary">Create League</button>
                            </div>
                        </form>
                    </div>
                </div>
            <?php } ?>
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
                        Team Ranking
                    </div>
                </div>
                <div class="card-body p-0">
                    <table class="table align-middle table-nowrap mb-0">
                        <thead class="table-light">
                            <tr>
                                <th scope="col" class="text-center" style="width: 52px">Rank</th>
                                <th scope="col">Team</th>
                                <th scope="col" class="text-center" style="width: 52px">Win</th>
                                <th scope="col" class="text-center" style="width: 52px">Draw</th>
                                <th scope="col" class="text-center" style="width: 52px">Lose</th>
                            </tr>
                        </thead>
                    </table>
                    <div data-simplebar style="max-height: 560px;">
                        <table class="table align-middle table-nowrap mb-0">
                            <tbody>
                                <?php foreach ($teams as $index => $team) { ?>
                                    <tr class="<?= $team['manager_id'] === $user_id ? 'table-primary' : '' ?>">
                                        <td class="text-center" style="width: 52px"><?= $index + 1 ?></td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0 avatar-xs">
                                                    <div class="avatar-title bg-danger-subtle text-danger rounded">
                                                        <i class="ri-netflix-fill"></i>
                                                    </div>
                                                </div>
                                                <div class="flex-shrink-0 ms-2">
                                                    <h6 class="fs-14 mb-0">
                                                        <?php if ($team['manager_id'] === $user_id) { ?>
                                                            <a href="<?= home_url("football-manager/my-club") ?>"><?= $team['team_name'] ?></a>
                                                        <?php } else { ?>
                                                            <?= $team['team_name'] ?>
                                                    </h6>
                                                <?php } ?>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center" style="width: 52px"><?= $team['total_wins'] ?></td>
                                        <td class="text-center" style="width: 52px"><?= $team['total_draws'] ?></td>
                                        <td class="text-center" style="width: 52px"><?= $team['total_losses'] ?></td>
                                    </tr>
                                <?php } ?>
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
                            <label for="employeeName" class="form-label">Team Name</label>
                            <input type="text" class="form-control" name="team_name"
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

include 'layout.php';
