<?php
$pageTitle = "Football Manager - My Players";

require_once DIR . '/controllers/FootballTeamController.php';
require_once DIR . '/controllers/FootballPlayerController.php';
require_once DIR . '/controllers/FootballTransferController.php';
$footballTeamController = new FootballTeamController();
$footballPlayerController = new FootballPlayerController();
$footballTransferController = new FootballTransferController();

$myTeam = $footballTeamController->getMyTeamPlayers();

$sort_order = !empty($_GET['sort_order']) && $_GET['sort_order'] === 'asc' ? 'desc' : 'asc';
$list = $commonController->convertResources($myTeam['players'] ?? []);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action_name'])) {
        if (isset($_POST['player_id'])){
            if ($_POST['action_name'] === 'player_join_team') {
                $footballTeamController->assignPlayerToTeam($myTeam['id'], $_POST['player_id'], $_POST['player_name']);
            }
            if ($_POST['action_name'] === 'player_in_market') {
                $footballTransferController->createTransferSellPlayer();
            }
            if ($_POST['action_name'] === 'delete_player') {
                $footballPlayerController->deletePlayer($myTeam['id'], $_POST['player_id'], $_POST['player_name']);
            }
        }
        if ($_POST['action_name'] === 'all_players_join_team') {
            $footballTeamController->joinAllPlayersToTeam();
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

        <div class="col-lg-12">
            <?php include_once DIR . '/components/alert.php'; ?>
        </div>

        <!--end col-->
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <?php if (count($list['resources']) > 0) { ?>
                        <div id="tasksList" class="px-3">
                            <div class="table-responsive table-card my-3">
                                <table class="table align-middle table-nowrap mb-0" id="customerTable">
                                    <thead class="table-light">
                                    <tr>
                                        <th class="sort" scope="col">Title</th>
                                        <th class="sort text-center" scope="col">Nationality</th>
                                        <th class="sort text-center" scope="col">Position</th>
                                        <th class="sort text-center" scope="col">Playable</th>
                                        <th class="sort text-center" scope="col">Season</th>
                                        <th class="sort text-center" scope="col"><a
                                                    href="<?= generatePageUrl(['sort_by' => 'ability', 'sort_order' => $sort_order]) ?>">Ability</a>
                                        </th>
                                        <th class="sort text-center" scope="col">Height</th>
                                        <th class="sort text-center" scope="col">Weight</th>
                                        <th class="sort text-center" scope="col">Contract Wage</th>
                                        <th class="sort text-center" scope="col">Market Price</th>
                                        <th class="text-center" scope="col">
                                            <?php if ($list['resources'] > 0) { ?>
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
                                    <?php if (count($list['resources']) > 0) {
                                        foreach ($list['resources'] as $item) { ?>
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
                                                <td class="text-center"><?= $item['nationality'] ?></td>
                                                <td class="text-center"><?= $item['best_position'] ?></td>
                                                <td class="text-center"><?= implode(", ", $item['playable_positions']) ?></td>
                                                <td class="text-center"><?= $item['season'] ?></td>
                                                <td class="text-center"><?= $item['ability'] ?></td>
                                                <td class="text-center"><?= $item['height'] ?> cm</td>
                                                <td class="text-center"><?= $item['weight'] ?> kg</td>
                                                <td class="text-center"><?= formatCurrency($item['contract_wage']) ?></td>
                                                <td class="text-center"><?= formatCurrency($item['market_value']) ?></td>
                                                <td class="text-center hstack gap-1">
                                                    <form method="POST" action="<?= $_SERVER['REQUEST_URI'] ?>">
                                                        <input type="hidden" name="action_name"
                                                               value="player_join_team">
                                                        <input type="hidden" name="player_id"
                                                               value="<?= $item['id'] ?>">
                                                        <input type="hidden" name="player_name"
                                                               value="<?= $item['name'] ?>">
                                                        <button type="submit" class="btn btn-soft-success btn-sm"><i
                                                                    class="ri ri-user-received-2-line"></i> Join
                                                        </button>
                                                    </form>
                                                    <form method="POST" action="<?= $_SERVER['REQUEST_URI'] ?>">
                                                        <input type="hidden" name="action_name"
                                                               value="player_in_market">
                                                        <input type="hidden" name="player_id"
                                                               value="<?= $item['id'] ?>">
                                                        <button class="btn btn-soft-danger btn-sm"><i
                                                                    class="ri ri-user-shared-2-line"></i> Sell
                                                        </button>
                                                    </form>
                                                    <form method="POST" action="<?= $_SERVER['REQUEST_URI'] ?>">
                                                        <input type="hidden" name="action_name"
                                                               value="delete_player">
                                                        <input type="hidden" name="player_id"
                                                               value="<?= $item['id'] ?>">
                                                        <input type="hidden" name="player_name"
                                                               value="<?= $item['name'] ?>">
                                                        <button class="btn btn-light btn-sm"><i
                                                                    class="ri ri-close-line"></i></button>
                                                    </form>
                                                </td>
                                            </tr>
                                        <?php }
                                    } ?>

                                    </tbody>
                                </table>
                            </div>
                            <?php
                            includeFileWithVariables('components/pagination.php', array("count" => $list['total_items']));
                            ?>
                        </div>
                    <?php } else { ?>
                        <div class="p-3">
                            <div class="text-muted">Find players and register them for your team.</div>
                            <a href="<?= home_url('app/football-manager/transfer') ?>"
                               class="btn btn-soft-primary mt-2">Go to Market <i
                                        class="ri-arrow-right-line ms-1 align-middle"></i></a>
                        </div>
                    <?php } ?>
                </div><!-- end card-body -->
            </div>
        </div>
        <!--end col-->

        <div class="col-lg-12">
            <?php include_once DIR . '/components/football-player-detail-modal.php'; ?>
        </div>

    </div>

<?php
$pageContent = ob_get_clean();

ob_start();
echo "<script src='" . home_url("/assets/js/pages/football-manager-player-detail.js") . "'></script>";
$additionJs = ob_get_clean();
