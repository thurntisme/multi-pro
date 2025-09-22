<?php
$pageTitle = "Football Manager Transfer";

require_once DIR . '/functions/generate-player.php';
require_once DIR . '/controllers/FootballPlayerController.php';
require_once DIR . '/controllers/FootballTeamController.php';

$players = getTransferPlayerJson();
$commonController = new CommonController();
$footballPlayerController = new FootballPlayerController();
$footballTeamController = new FootballTeamController();
$list = $footballPlayerController->listFavoritePlayers();
$myTeam = $footballTeamController->getMyTeamInTransfer();

$sort_order = !empty($_GET['sort_order']) && $_GET['sort_order'] === 'asc' ? 'desc' : 'asc';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action_name'])) {
        if ($_POST['action_name'] === 'remove_favorite_player') {
            $footballPlayerController->removeFavoritePlayer($_POST['player_uuid'], $_POST['player_name']);
        }
    }
}

function isPlayerInTeam($player_uuid): bool
{
    global $myTeam;
    foreach ($myTeam['players'] as $teamPlayer) {
        if ($teamPlayer['player_uuid'] === $player_uuid) {
            return true;
        }
    }
    return false;
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
                                        <th class="sort" scope="col">Title</th>
                                        <th class="sort text-center" scope="col">Nationality</th>
                                        <th class="sort text-center" scope="col"><a
                                                href="?sort_by=age&sort_order=<?= $sort_order ?>">Age</a></th>
                                        <th class="sort text-center" scope="col">Height</th>
                                        <th class="sort text-center" scope="col">Weight</th>
                                        <th class="sort text-center" scope="col">Position</th>
                                        <th class="sort text-center" scope="col">Playable</th>
                                        <th class="sort text-center" scope="col">Season</th>
                                        <th class="sort text-center" scope="col"><a
                                                href="<?= generatePageUrl(['sort_by' => 'ability', 'sort_order' => $sort_order]) ?>">Ability</a>
                                        </th>
                                        <th class="sort text-center" scope="col"><a
                                                href="<?= generatePageUrl(['sort_by' => 'contract_wage', 'sort_order' => $sort_order]) ?>">Contract
                                                Wage</a></th>
                                        <th class="sort text-center" scope="col"><a
                                                href="<?= generatePageUrl(['sort_by' => 'market_value', 'sort_order' => $sort_order]) ?>">Market
                                                Value</a></th>
                                        <th class="text-center" scope="col"></th>
                                    </tr>
                                </thead>
                                <tbody class="list form-check-all">
                                    <?php if ($list['resources'] && count($list['resources']) > 0) {
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
                                                <td class="text-center"><?= $item['age'] ?></td>
                                                <td class="text-center"><?= $item['height'] ?> cm</td>
                                                <td class="text-center"><?= $item['weight'] ?> kg</td>
                                                <td class="text-center"><?= $item['best_position'] ?></td>
                                                <td class="text-center"><?= implode(", ", $item['playable_positions']) ?></td>
                                                <td class="text-center"><?= $item['season'] ?></td>
                                                <td class="text-center"><?= $item['ability'] ?></td>
                                                <td class="text-center"><?= formatCurrency($item['contract_wage']) ?></td>
                                                <td class="text-center"><?= formatCurrency($item['market_value']) ?></td>
                                                <td class="text-center hstack gap-1 justify-content-center">
                                                    <?php if ($myTeam['budget'] >= $item['market_value'] && !isPlayerInTeam($item['uuid'])) { ?>
                                                        <a href="<?= App\Helpers\NetworkHelper::home_url("app/football-manager/transfer/buy?p_uuid=" . $item['uuid']) ?>"
                                                            class="btn btn-soft-success">
                                                            <i class="ri ri-shopping-cart-line"></i>
                                                        </a>
                                                    <?php } else { ?>
                                                        <a href="#" class="btn btn-soft-success opacity-50"
                                                            style="cursor: not-allowed; pointer-events: none;">
                                                            <i class="ri ri-shopping-cart-line"></i>
                                                        </a>
                                                    <?php } ?>
                                                    <form method="POST" action="<?= $_SERVER['REQUEST_URI'] ?>">
                                                        <input type="hidden" name="action_name" value="remove_favorite_player">
                                                        <input type="hidden" name="player_uuid" value="<?= $item['uuid'] ?>">
                                                        <input type="hidden" name="player_name" value="<?= $item['name'] ?>">
                                                        <button class="btn btn-soft-danger" type="submit">
                                                            <i class="ri ri-heart-fill"></i>
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        <?php }
                                    } ?>

                                </tbody>
                            </table>
                        </div>
                        <?php
                        includeFileWithVariables('components/pagination.php', array("count" => $list['count']));
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
