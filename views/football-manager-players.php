<?php
$pageTitle = "Football Manager - My Players";

require_once DIR . '/functions/generate-player.php';

$players = generateRandomPlayers(20, 'ballon-d-or');
$commonController = new CommonController();
$list = $commonController->convertResources($players);

// Download player data as json file
$filteredPlayers = array_filter($players, function ($player) {
    return $player['name'] !== 'No names found.';
});

//exportPlayersToJson($filteredPlayers);

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
                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs nav-border-top nav-border-top-primary mb-3" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-bs-toggle="tab" href="#my-players" role="tab"
                               aria-selected="false">
                                All players
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#bingo" role="tab" aria-selected="true">
                                Bingo
                            </a>
                        </li>
                    </ul>
                    <div class="tab-content text-muted">
                        <div class="tab-pane active" id="my-players" role="tabpanel">
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
                                            <th class="sort text-center" scope="col">Height</th>
                                            <th class="sort text-center" scope="col">Weight</th>
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
                                                    <td class="text-center"><?= $item['height'] ?> cm</td>
                                                    <td class="text-center"><?= $item['weight'] ?> kg</td>
                                                    <td class="text-center"><?= formatCurrency($item['contract_wage']) ?></td>
                                                    <td class="text-center"><?= formatCurrency($item['market_value']) ?></td>
                                                    <td class="text-center">
                                                        <button class="btn btn-sm btn-soft-primary">Join</button>
                                                        <button class="btn btn-sm btn-soft-success">Sell</button>
                                                        <button class="btn btn-sm btn-soft-danger">Delete</button>
                                                    </td>
                                                </tr>
                                            <?php }
                                        } ?>

                                        </tbody>
                                    </table>
                                    <div class="noresult" style="display: none">
                                        <div class="text-center">
                                            <lord-icon src="https://cdn.lordicon.com/msoeawqm.json" trigger="loop"
                                                       colors="primary:#121331,secondary:#08a88a"
                                                       style="width:75px;height:75px"></lord-icon>
                                            <h5 class="mt-2">Sorry! No Result Found</h5>
                                            <p class="text-muted mb-0">We've searched more than 150+ companies We did
                                                not find
                                                any companies for you search.</p>
                                        </div>
                                    </div>
                                </div>
                                <?php
                                includeFileWithVariables('components/pagination.php', array("count" => $list['total_items']));
                                ?>
                            </div>
                        </div>
                        <div class="tab-pane" id="bingo" role="tabpanel">
                            <div class="d-flex">
                                <div class="flex-shrink-0">
                                    <i class="ri-checkbox-circle-line text-success"></i>
                                </div>
                                <div class="flex-grow-1 ms-2">
                                    Bingo player is coming soon
                                    <div class="mt-2">
                                        <a href="javascript:void(0);" class="btn btn-link">Read More <i
                                                    class="ri-arrow-right-line ms-1 align-middle"></i></a>
                                    </div>
                                </div>
                            </div>
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
