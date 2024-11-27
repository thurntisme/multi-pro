<?php
$pageTitle = "Football Manager Transfer";

require_once DIR . '/functions/generate-player.php';

$players = getPlayersJson();
$commonController = new CommonController();
$list = $commonController->convertResources($players);

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
        <div class="card">
            <div class="card-body">
                <?php includeFileWithVariables('components/football-market-topbar.php'); ?>
                <div class="tab-content text-muted">
                    <form method="get" class="d-block mb-2" action="<?= home_url('football-manager') ?>">
                        <div class="row g-2">
                            <div class="col-md-3">
                                <div class="search-box">
                                    <input type="text" class="form-control search" name="s"
                                        placeholder="Search for player..." value="<?= $_GET['s'] ?? '' ?>" />
                                    <i class="ri-search-line search-icon"></i>
                                </div>
                            </div>
                            <button class="btn btn-light w-auto ms-2" type="button" data-bs-toggle="collapse"
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
                            <div class="card mb-0">
                                <div class="card-body">
                                    Advanced filter here
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
                                    <?php if (count($list['resources']) > 0) {
                                        foreach ($list['resources'] as $item) { ?>
                                            <tr>
                                                <td>
                                                    <div class="d-flex">
                                                        <div class="flex-grow-1"><?= $item['name'] ?></div>
                                                        <div class="flex-shrink-0 ms-4">
                                                            <ul class="list-inline tasks-list-menu mb-0 pe-4">
                                                                <li class="list-inline-item">
                                                                    <a class="edit-item-btn"
                                                                        href="#<?= $item['uuid'] ?>"><i
                                                                            class="ri-eye-fill align-bottom me-2 text-muted"></i></a>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="text-center"><?= $item['nationality'] ?></td>
                                                <td class="text-center"><?= $item['best_position'] ?></td>
                                                <td class="text-center"><?= implode(", ", $item['playable_positions']) ?></td>
                                                <td class="text-center"><?= $item['season'] ?></td>
                                                <td class="text-center"><?= $item['ability'] ?></td>
                                                <td class="text-center"><?= formatCurrency($item['contract_wage']) ?></td>
                                                <td class="text-center"><?= formatCurrency($item['market_value']) ?></td>
                                                <td class="text-center">
                                                    <a href="<?= home_url("football-manager/transfer/buy?p_uuid=" . $item['uuid']) ?>" class="btn btn-soft-success">
                                                        <i class="ri ri-shopping-cart-line"></i>
                                                    </a>
                                                    <button class="btn btn-soft-danger">
                                                        <i class="ri ri-heart-line"></i>
                                                    </button>
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
</div>

<?php
$pageContent = ob_get_clean();

include 'layout.php';
