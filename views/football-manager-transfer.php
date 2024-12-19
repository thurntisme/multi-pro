<?php
$pageTitle = "Football Manager Transfer";

require_once DIR . '/functions/generate-player.php';
require_once DIR . '/controllers/FootballPlayerController.php';
require_once DIR . '/controllers/FootballTeamController.php';

$players = getTransferPlayerJson();
$commonController = new CommonController();
$footballPlayerController = new FootballPlayerController();
$footballTeamController = new FootballTeamController();
$list = $commonController->convertResources($players);
$myTeam = $footballTeamController->getMyTeam();

$sort_order = !empty($_GET['sort_order']) && $_GET['sort_order'] === 'asc' ? 'desc' : 'asc';

function isFilter($array)
{
    $result = [];

    foreach ($array as $key => $value) {
        // Check if the key matches "player_*" and the value is not empty
        if (preg_match('/^player_/', $key) && !empty($value)) {
            $result[$key] = $value;
        }
    }

    return $result;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action_name'])) {
        if ($_POST['action_name'] === 'add_favorite_player') {
            $footballPlayerController->createFavoritePlayer($_POST['player_uuid'], $_POST['player_name']);
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
            ?>
            <div class="card">
                <div class="card-body">
                    <?php includeFileWithVariables('components/football-market-topbar.php'); ?>
                    <div class="tab-content text-muted">
                        <form method="get" class="d-block mb-2" action="<?= home_url('football-manager/transfer') ?>">
                            <div class="row g-2">
                                <div class="col-md-3">
                                    <div class="search-box">
                                        <input type="text" class="form-control search" name="s"
                                               placeholder="Search for player..." value="<?= $_GET['s'] ?? '' ?>"/>
                                        <i class="ri-search-line search-icon"></i>
                                    </div>
                                </div>
                                <button class="btn btn-<?= isFilter($_GET) ? 'primary' : 'light' ?> w-auto ms-2"
                                        type="button" data-bs-toggle="collapse"
                                        data-bs-target="#advancedFilter" aria-expanded="true"
                                        aria-controls="advancedFilter">
                                    <i class="ri-filter-2-line"></i>
                                </button>
                                <button type="submit" class="btn btn-primary w-auto ms-2"><i
                                            class="ri-refresh-line me-1 align-bottom"></i>Filter
                                </button>
                                <a class="btn btn-soft-success w-auto ms-2"
                                   href="<?= home_url("football-manager/transfer") ?>"><i
                                            class="ri-refresh-line me-1 align-bottom"></i>Reset</a>
                            </div>
                            <div class="collapse" id="advancedFilter">
                                <div class="row g-4 mt-0 mb-4">
                                    <div class="col-lg-4">
                                        <div>
                                            <label for="player_season" class="form-label">Season</label>
                                            <select class="form-control" data-choices data-choices-sorting-false
                                                    name="player_season">
                                                <option value="">Select Season</option>
                                                <?php foreach ($seasons as $season => $threshold): ?>
                                                    <option value="<?= $season ?>" <?= !empty($_GET['player_season']) && $_GET['player_season'] === $season ? 'selected' : '' ?>><?= ucfirst($season) ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div>
                                            <label for="player_nationality" class="form-label">Nationality</label>
                                            <select class="form-control" data-choices name="player_nationality">
                                                <option value="">Select Nationality</option>
                                                <?php foreach (DEFAULT_NATIONALITY as $index => $nation): ?>
                                                    <option value="<?= $nation ?>" <?= !empty($_GET['player_nationality']) && $_GET['player_nationality'] === $nation ? 'selected' : '' ?>><?= $nation ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-lg-4">
                                        <div>
                                            <label for="player_age" class="form-label">Age</label>
                                            <input type="number" class="form-control" name="player_age" id="player_age"
                                                   min="18" max="35" placeholder="Age"
                                                   value="<?= $_GET['player_age'] ?? '' ?>"/>
                                        </div>
                                    </div>

                                    <div class="col-lg-4">
                                        <div>
                                            <label for="player_weight" class="form-label">Weight (kg)</label>
                                            <input type="number" class="form-control" name="player_weight"
                                                   id="player_weight" min="60" max="110" placeholder="Weight (kg)"
                                                   value="<?= $_GET['player_weight'] ?? '' ?>"/>
                                        </div>
                                    </div>

                                    <div class="col-lg-4">
                                        <div>
                                            <label for="player_height" class="form-label">Height (cm)</label>
                                            <input type="number" class="form-control" name="player_height"
                                                   id="player_height" min="165" max="195" placeholder="Height (cm)"
                                                   value="<?= $_GET['player_height'] ?? '' ?>"/>
                                        </div>
                                    </div>

                                    <div class="col-lg-4">
                                        <div>
                                            <label for="player_position" class="form-label">Position</label>
                                            <select class="form-control" data-choices name="player_position">
                                                <option value="">Select Position</option>
                                                <?php foreach ($positions as $position): ?>
                                                    <option value="<?= $position ?>" <?= !empty($_GET['player_position']) && $_GET['player_position'] === $position ? 'selected' : '' ?>><?= $position ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
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
                                                    <?php if ($myTeam['budget'] >= $item['market_value']) { ?>
                                                        <a href="<?= home_url("football-manager/transfer/buy?p_uuid=" . $item['uuid']) ?>"
                                                           class="btn btn-soft-success">
                                                            <i class="ri ri-shopping-cart-line"></i>
                                                        </a>
                                                    <?php } ?>
                                                    <form method="POST" action="<?= $_SERVER['REQUEST_URI'] ?>">
                                                        <input type="hidden" name="action_name"
                                                               value="add_favorite_player">
                                                        <input type="hidden" name="player_uuid"
                                                               value="<?= $item['uuid'] ?>">
                                                        <input type="hidden" name="player_name"
                                                               value="<?= $item['name'] ?>">
                                                        <button class="btn btn-soft-danger" type="submit">
                                                            <i class="ri ri-heart-line"></i>
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
                            includeFileWithVariables('components/pagination.php', array("count" => $list['total_items'], "perPage" => $list['per_page']));
                            ?>
                        </div>
                    </div>
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

include 'layout.php';
