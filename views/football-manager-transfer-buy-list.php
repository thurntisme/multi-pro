<?php
$pageTitle = "Football Manager Transfer";

require_once DIR . '/controllers/FootballTransferController.php';
require_once DIR . '/controllers/FootballPlayerController.php';

$footballTransferController = new FootballTransferController();
$footballPlayerController = new FootballPlayerController();
$buyList = $footballTransferController->listTransferPlayers('buy');

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
            <?php
            include_once DIR . '/components/alert.php';
            ?>
            <div class="card">
                <div class="card-body">
                    <?php includeFileWithVariables('components/football-market-topbar.php'); ?>
                    <div class="tab-content text-muted">
                        <div id="tasksList" class="px-3">
                            <div class="table-responsive table-card my-3">
                                <table class="table align-middle table-nowrap mb-0" id="customerTable">
                                    <thead class="table-light">
                                    <tr>
                                        <th class="sort" scope="col">Name</th>
                                        <th class="sort text-center" scope="col">Nationality</th>
                                        <th class="sort text-center" scope="col">Position</th>
                                        <th class="sort text-center" scope="col">Playable</th>
                                        <th class="sort text-center" scope="col">Season</th>
                                        <th class="sort text-center" scope="col">Rating</th>
                                        <th class="sort text-center" scope="col">Contract Wage</th>
                                        <th class="sort text-center" scope="col">Price</th>
                                        <th class="text-center" scope="col"></th>
                                    </tr>
                                    </thead>
                                    <tbody class="list form-check-all">
                                    <?php if (count($buyList['list']) > 0) {
                                        foreach ($buyList['list'] as $item) {
                                            $playerData = $footballPlayerController->viewPlayer($item['player_id']);
                                            ?>
                                            <tr>
                                                <td><?= $playerData['name'] ?? '' ?></td>
                                                <td class="text-center"><?= $playerData['nationality'] ?? '' ?></td>
                                                <td class="text-center"><?= $playerData['best_position'] ?? '' ?></td>
                                                <td class="text-center"><?= !empty($playerData['playable_positions']) ? implode(", ", $playerData['playable_positions']) : '' ?></td>
                                                <td class="text-center"><?= $playerData['season'] ?? '' ?></td>
                                                <td class="text-center"><?= $playerData['ability'] ?? '' ?></td>
                                                <td class="text-center"><?= formatCurrency($playerData['contract_wage'] ?? 0) ?></td>
                                                <td class="text-center"><?= formatCurrency($playerData['market_value'] ?? 0) ?></td>
                                                <td class="text-center">
                                                    <button class="btn btn-soft-primary btn-sm">Cancel</button>
                                                </td>
                                            </tr>
                                        <?php }
                                    } ?>
                                    </tbody>
                                </table>
                            </div>
                            <?php
                            includeFileWithVariables('components/pagination.php', array("count" => $buyList['count']));
                            ?>
                        </div>
                    </div>
                </div><!-- end card-body -->
            </div>
        </div>
        <!--end col-->
    </div>

<?php
$pageContent = ob_get_clean();

include 'layout.php';
