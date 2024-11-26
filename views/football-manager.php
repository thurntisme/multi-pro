<?php
$pageTitle = "Football Manager";

require_once DIR . '/functions/generate-player.php';
// Generate 5 random players
$popular_players = generateRandomPlayers(8);
$commonController = new CommonController();

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
                                             class="avatar-sm rounded-circle"/>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h5 class="list-title fs-15 mb-1"><?= $player['name'] ?></h5>
                                        <p class="list-text mb-0 fs-12"><?= $player['best_position'] ?>
                                            | <?= $player['abilities']['current_ability'] ?>
                                            | <?= implode(", ", $player['playable_positions']) ?></p>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4"></div>
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
                            <?php foreach (DEFAULT_FOOTBALL_TEAM as $index => $team) { ?>
                                <tr>
                                    <td class="text-center" style="width: 52px"><?= $index + 1 ?></td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0 avatar-xs">
                                                <div class="avatar-title bg-danger-subtle text-danger rounded">
                                                    <i class="ri-netflix-fill"></i>
                                                </div>
                                            </div>
                                            <div class="flex-shrink-0 ms-2">
                                                <h6 class="fs-14 mb-0"><?= $team['name'] ?></h6>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center" style="width: 52px">1</td>
                                    <td class="text-center" style="width: 52px">1</td>
                                    <td class="text-center" style="width: 52px">1</td>
                                </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!--end col-->
    </div>

<?php
$pageContent = ob_get_clean();

include 'layout.php';
