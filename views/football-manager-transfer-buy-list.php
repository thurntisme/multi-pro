<?php
$pageTitle = "Football Manager Transfer";

require_once DIR . '/controllers/FootballTransferController.php';
require_once DIR . '/controllers/FootballPlayerController.php';
require_once DIR . '/controllers/FootballTeamController.php';

$footballTransferController = new FootballTransferController();
$footballPlayerController = new FootballPlayerController();
$footballTeamController = new FootballTeamController();
$buyList = $footballTransferController->listTransferPlayers('buy');
$myTeam = $footballTeamController->getMyTeamPlayers();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action_name'])) {
        if ($_POST['action_name'] === 'all_players_join_team') {
            $footballTeamController->moveAllPlayersToTeam('buy');
        }
        if ($_POST['action_name'] === 'player_join_team') {
            $footballTeamController->movePlayerToTeam($myTeam['id'], $_POST['player_id'], $_POST['player_name'], $_POST['transfer_id']);
        }
        if ($_POST['action_name'] === 'get_refund') {
            $footballTeamController->getRefundFromPlayer($_POST['player_id'], $_POST['player_name'], $_POST['transfer_id']);
        }
        if ($_POST['action_name'] === 'delete_transfer') {
            $footballTransferController->deleteTransfer($_POST['transfer_id'], $_POST['player_id'], $_POST['player_name']);
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
        </div>
        <!--end col-->
        <div class="col-lg-12">
            <?php
            include_once DIR . '/components/alert.php';
            unset($_SESSION['rowsAffected']);
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
                                        <th class="sort text-center" scope="col">Age</th>
                                        <th class="sort text-center" scope="col">Position</th>
                                        <th class="sort text-center" scope="col">Playable</th>
                                        <th class="sort text-center" scope="col">Season</th>
                                        <th class="sort text-center" scope="col">Ability</th>
                                        <th class="sort text-center" scope="col">Contract Wage</th>
                                        <th class="sort text-center" scope="col">Market Value</th>
                                        <th class="text-center" scope="col">
                                            <?php if (count($buyList['successCount']) > 0) { ?>
                                                <form method="POST" action="<?= $_SERVER['REQUEST_URI'] ?>">
                                                    <input type="hidden" name="action_name"
                                                           value="all_players_join_team">
                                                    <button type="submit"
                                                            class="btn btn-soft-success btn-sm"><i
                                                                class="ri ri-user-received-2-line"></i> Join All
                                                    </button>
                                                </form>
                                            <?php } ?>
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody class="list form-check-all">
                                    <?php if (count($buyList['list']) > 0) {
                                        $now = new DateTime();
                                        foreach ($buyList['list'] as $item) {
                                            ?>
                                            <tr>
                                                <td>
                                                    <div class="d-flex">
                                                        <div class="me-2"><?= $item['name'] ?></div>
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
                                                <td class="text-center"><?= $item['nationality'] ?? '' ?></td>
                                                <td class="text-center"><?= $item['age'] ?? '' ?></td>
                                                <td class="text-center"><?= $item['best_position'] ?? '' ?></td>
                                                <td class="text-center"><?= !empty($item['playable_positions']) ? implode(", ", $item['playable_positions']) : '' ?></td>
                                                <td class="text-center"><?= $item['season'] ?? '' ?></td>
                                                <td class="text-center"><?= $item['ability'] ?? '' ?></td>
                                                <td class="text-center"><?= formatCurrency($item['contract_wage'] ?? 0) ?></td>
                                                <td class="text-center"><?= formatCurrency($item['market_value'] ?? 0) ?></td>
                                                <td class="text-center hstack gap-1 justify-content-center">
                                                    <?php
                                                    $response_at = new DateTime($item['response_at']);
                                                    if ($now < $response_at) { ?>
                                                        <form method="POST" action="<?= $_SERVER['REQUEST_URI'] ?>">
                                                            <input type="hidden" name="action_name"
                                                                   value="delete_transfer">
                                                            <input type="hidden" name="transfer_id"
                                                                   value="<?= $item['id'] ?>">
                                                            <input type="hidden" name="player_id"
                                                                   value="<?= $item['player_id'] ?>">
                                                            <input type="hidden" name="player_name"
                                                                   value="<?= $item['name'] ?>">
                                                            <button class="btn btn-light btn-sm" type="submit"><i
                                                                        class="ri ri-close-line"></i> Cancel
                                                            </button>
                                                        </form>
                                                    <?php } else {
                                                        if ($item['is_success']) { ?>
                                                            <form method="POST" action="<?= $_SERVER['REQUEST_URI'] ?>">
                                                                <input type="hidden" name="action_name"
                                                                       value="player_join_team">
                                                                <input type="hidden" name="transfer_id"
                                                                       value="<?= $item['id'] ?>">
                                                                <input type="hidden" name="player_id"
                                                                       value="<?= $item['player_id'] ?>">
                                                                <input type="hidden" name="player_name"
                                                                       value="<?= $item['name'] ?>">
                                                                <button type="submit"
                                                                        class="btn btn-soft-success btn-sm"><i
                                                                            class="ri ri-user-received-2-line"></i> Join
                                                                </button>
                                                            </form>
                                                            <form method="POST" action="<?= $_SERVER['REQUEST_URI'] ?>">
                                                                <input type="hidden" name="action_name"
                                                                       value="player_in_market">
                                                                <input type="hidden" name="player_id"
                                                                       value="<?= $item['player_id'] ?>">
                                                                <button class="btn btn-soft-danger btn-sm"><i
                                                                            class="ri ri-user-shared-2-line"></i> Sell
                                                                </button>
                                                            </form>
                                                            <form method="POST" action="<?= $_SERVER['REQUEST_URI'] ?>">
                                                                <input type="hidden" name="action_name"
                                                                       value="delete_transfer">
                                                                <input type="hidden" name="transfer_id"
                                                                       value="<?= $item['id'] ?>">
                                                                <input type="hidden" name="player_id"
                                                                       value="<?= $item['player_id'] ?>">
                                                                <input type="hidden" name="player_name"
                                                                       value="<?= $item['name'] ?>">
                                                                <button class="btn btn-light btn-sm"><i
                                                                            class="ri ri-close-line"></i></button>
                                                            </form>
                                                        <?php } else { ?>
                                                            <form method="POST" action="<?= $_SERVER['REQUEST_URI'] ?>">
                                                                <input type="hidden" name="action_name"
                                                                       value="get_refund">
                                                                <input type="hidden" name="transfer_id"
                                                                       value="<?= $item['id'] ?>">
                                                                <input type="hidden" name="player_id"
                                                                       value="<?= $item['player_id'] ?>">
                                                                <input type="hidden" name="player_name"
                                                                       value="<?= $item['name'] ?>">
                                                                <button class="btn btn-soft-warning btn-sm"
                                                                        type="submit"><i
                                                                            class="ri ri-money-dollar-circle-line"></i>
                                                                    Get
                                                                    a refund
                                                                </button>
                                                            </form>

                                                            <form method="POST" action="<?= $_SERVER['REQUEST_URI'] ?>">
                                                                <input type="hidden" name="action_name"
                                                                       value="delete_transfer">
                                                                <input type="hidden" name="transfer_id"
                                                                       value="<?= $item['id'] ?>">
                                                                <input type="hidden" name="player_id"
                                                                       value="<?= $item['player_id'] ?>">
                                                                <input type="hidden" name="player_name"
                                                                       value="<?= $item['name'] ?>">
                                                                <button class="btn btn-light btn-sm" type="submit"><i
                                                                            class="ri ri-close-line"></i></button>
                                                            </form>
                                                        <?php }
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

<?php include_once DIR . '/components/football-player-detail-modal.php'; ?>
<?php
$pageContent = ob_get_clean();

ob_start();
echo "<script src='" . home_url("/assets/js/pages/football-manager-player-detail.js") . "'></script>";
$additionJs = ob_get_clean();
