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
                                        $now = new DateTime();
                                        foreach ($buyList['list'] as $item) {
                                            ?>
                                            <tr>
                                                <td><?= $item['name'] ?? '' ?></td>
                                                <td class="text-center"><?= $item['nationality'] ?? '' ?></td>
                                                <td class="text-center"><?= $item['best_position'] ?? '' ?></td>
                                                <td class="text-center"><?= !empty($item['playable_positions']) ? implode(", ", $item['playable_positions']) : '' ?></td>
                                                <td class="text-center"><?= $item['season'] ?? '' ?></td>
                                                <td class="text-center"><?= $item['ability'] ?? '' ?></td>
                                                <td class="text-center"><?= formatCurrency($item['contract_wage'] ?? 0) ?></td>
                                                <td class="text-center"><?= formatCurrency($item['market_value'] ?? 0) ?></td>
                                                <td class="text-center">
                                                    <?php
                                                        $response_at = new DateTime($item['response_at']);
                                                        if ($now < $response_at) {
                                                            echo 'Processing';
                                                        } else {
                                                            if ($item['is_success']){
                                                                echo '<button class="btn btn-soft-success btn-sm"><i class="ri ri-user-received-2-line"></i> Join</button>
                                                                    <button class="btn btn-soft-danger btn-sm"><i class="ri ri-user-shared-2-line"></i> Sell</button>';
                                                            } else {
                                                                echo '<button class="btn btn-soft-warning btn-sm"><i class="ri ri-money-dollar-circle-line"></i> Get a refund</button>';
                                                            }
                                                        }
                                                    ?>
                                                    
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
