<?php
$pageTitle = "Football Manager - My Club";

require_once DIR . '/functions/generate-player.php';
// Generate 10 random players
$lineupPlayers = generateRandomPlayers(10);
$subPlayers = generateRandomPlayers(22);
$commonController = new CommonController();
$lineupList = $commonController->convertResources($lineupPlayers);

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
        <div class="row">
            <div class="col-4">
                <div class="card">
                    <div class="card-body p-0">
                        <table class="table align-middle table-nowrap mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>
                                        <div class="d-flex align-items-center justify-content-between">
                                            <span>Team Formations</span>
                                            <select class="form-select form-select-sm" data-choices
                                                data-choices-sorting-false
                                                data-choices-search-false id="formation">
                                                <option value="442">4-4-2</option>
                                                <option value="433">4-3-3</option>
                                                <option value="343">3-4-3</option>
                                            </select>
                                        </div>
                                    </th>
                                </tr>
                            </thead>
                        </table>
                        <div class="p-3 d-flex align-items-center justify-content-center">
                            <canvas id="footballPitch" width="320" height="160"></canvas>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body p-4">
                        <div class="row">
                            <div class="col-4 text-center">
                                <div class="profile-user position-relative d-inline-block mx-auto">
                                    <img src="<?= home_url('assets/images/users/avatar-1.jpg') ?>"
                                        class="rounded-circle avatar-md img-thumbnail user-profile-image"
                                        alt="user-profile-image">
                                </div>
                            </div>
                            <div class="col-8">
                                <h5 class="fs-16 mb-1"><?= $lineupPlayers[0]['name'] ?></h5>
                                <p class="text-muted mb-0 fs-14"><?= $lineupPlayers[0]['nationality'] ?></p>
                                <p class="text-muted mb-0 mt-2"><?= $lineupPlayers[0]['best_position'] ?>
                                    (<?= $lineupPlayers[0]['abilities']['current_ability'] ?>)
                                    | <?= implode(", ", $lineupPlayers[0]['playable_positions']) ?></p>
                            </div>
                        </div>
                        <div class="px-2 py-2 mt-4">
                            <p class="mb-1 fs-12">Canada <span class="float-end">75%</span></p>
                            <div class="progress mt-2" style="height: 6px;">
                                <div class="progress-bar progress-bar-striped bg-primary" role="progressbar"
                                    style="width: 75%" aria-valuenow="75" aria-valuemin="0"
                                    aria-valuemax="75"></div>
                            </div>

                            <p class="mt-3 mb-1 fs-12">Greenland <span class="float-end">47%</span>
                            </p>
                            <div class="progress mt-2" style="height: 6px;">
                                <div class="progress-bar progress-bar-striped bg-primary" role="progressbar"
                                    style="width: 47%" aria-valuenow="47" aria-valuemin="0"
                                    aria-valuemax="47"></div>
                            </div>

                            <p class="mt-3 mb-1 fs-12">Russia <span class="float-end">82%</span></p>
                            <div class="progress mt-2" style="height: 6px;">
                                <div class="progress-bar progress-bar-striped bg-primary" role="progressbar"
                                    style="width: 82%" aria-valuenow="82" aria-valuemin="0"
                                    aria-valuemax="82"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-8">
                <div class="card crm-widget">
                    <div class="card-body p-0">
                        <div class="row row-cols-md-3 row-cols-1">
                            <div class="col col-lg border-end">
                                <div class="py-2 px-3">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0">
                                            <i class="mdi mdi-account-group-outline fs-24 text-muted"></i>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <h2 class="mb-0 fs-24">197</h2>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col col-lg border-end">
                                <div class="py-2 px-3">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0">
                                            <i class="mdi mdi-shield-outline fs-24 text-muted"></i>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <h2 class="mb-0 fs-24">197</h2>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col col-lg border-end">
                                <div class="py-2 px-3">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0">
                                            <i class="mdi mdi-target fs-24 text-muted"></i>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <h2 class="mb-0 fs-24">197</h2>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col col-lg border-end">
                                <div class="py-2 px-3">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0">
                                            <i class="mdi mdi-soccer fs-24 text-muted"></i>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <h2 class="mb-0 fs-24">197</h2>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div><!-- end row -->
                    </div><!-- end card body -->
                </div>
                <div class="row">
                    <div class="col-7">
                        <div class="card">
                            <div class="card-body">
                                <div class="table-responsive table-card">
                                    <table class="table align-middle table-nowrap mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th colspan="4">Lineup</th>
                                            </tr>
                                        </thead>
                                        <tbody class="list form-check-all">
                                            <?php if (count($lineupList['resources']) > 0) {
                                                foreach ($lineupList['resources'] as $item) { ?>
                                                    <tr>
                                                        <td class="text-center"
                                                            style="width: 10%;"><?= $item['best_position'] ?></td>
                                                        <td><?= $item['name'] ?></td>
                                                        <td class="text-center"><?= $item['abilities']['current_ability'] ?></td>
                                                        <td class="text-center" style="width: 15%;">
                                                            <div class="progress">
                                                                <div class="progress-bar bg-success" role="progressbar"
                                                                    style="width: 25%" aria-valuenow="25" aria-valuemin="0"
                                                                    aria-valuemax="100"></div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                            <?php }
                                            } ?>

                                        </tbody>
                                    </table>
                                </div>
                                <?php
                                // includeFileWithVariables('components/pagination.php', array("count" => $lineupList['total_items']));
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-5">
                        <div class="card">
                            <div class="card-body p-0">
                                <table class="table align-middle table-nowrap mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th colspan="4">Subtitle</th>
                                        </tr>
                                    </thead>
                                </table>
                                <div class="p-3">
                                    <div class="table-responsive table-card" data-simplebar style="max-height: 442px;">
                                        <table class="table align-middle table-nowrap mb-0">
                                            <tbody class="list form-check-all">
                                                <?php if (count($subPlayers) > 0) {
                                                    foreach ($subPlayers as $item) { ?>
                                                        <tr>
                                                            <td class="text-center"
                                                                style="width: 10%;"><?= $item['best_position'] ?></td>
                                                            <td><?= $item['name'] ?></td>
                                                            <td class="text-center"><?= $item['abilities']['current_ability'] ?></td>
                                                            <td class="text-center" style="width: 20%;">
                                                                <div class="progress">
                                                                    <div class="progress-bar bg-success" role="progressbar"
                                                                        style="width: 25%" aria-valuenow="25" aria-valuemin="0"
                                                                        aria-valuemax="100"></div>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                <?php }
                                                } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--end col-->
</div>

<?php
$pageContent = ob_get_clean();

ob_start();
echo "
    <script src='" . home_url("/assets/js/pages/football-manager-club.js") . "'></script>
";
$additionJs = ob_get_clean();

include 'layout.php';
