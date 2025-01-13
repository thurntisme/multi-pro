<?php
$pageTitle = "Football Manager - Player Detail";

require_once DIR . '/functions/generate-player.php';

$player = !empty($_GET['uuid']) ? getPlayerJsonByUuid($_GET['uuid']) : null;

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
                    <div class="text-center p-5">
                        <?php if (!empty($player)) { ?>
                            <h4 class="mb-2" id="playerName"><?= $player['name'] ?></h4>
                            <p class="mb-0" id="playerNationality"><?= $player['nationality'] ?></p>
                            <p class="mb-1 text-muted" id="playerMeta"><?= $player['age'] ?> yrd
                                | <?= $player['height'] ?> cm | <?= $player['weight'] ?> kg</p>
                            <p class="mb-1 text-muted" id="playerPositions"><?= $player['best_position'] ?>
                                (<?= $player['ability'] ?>) | <?= implode(", ", $player['playable_positions']) ?></p>
                            <p class="mb-1 text-muted" id="playerPositions">
                                <i class="ri-quill-pen-fill align-bottom me-1 text-muted"></i><?= formatCurrency($player['contract_wage']) ?>
                                |
                                <i class="ri-auction-fill align-bottom me-1 text-muted"></i><?= formatCurrency($player['market_value']) ?>
                            </p>
                            <div class="border-top-dashed border-opacity-10 mt-3 border-1"></div>
                            <div id="playerAttributes" class="py-3 mt-2">
                                <div class="row">
                                    <?php foreach ($player['attributes'] as $key => $attribute) { ?>
                                        <div class="col-3">
                                            <h6 class="card-title flex-grow-1 mb-3 fs-15 text-capitalize"><?= $key ?></h6>
                                            <table class="table table-borderless mb-0">
                                                <tbody>
                                                <?php foreach ($attribute as $attr_key => $attr_value) { ?>
                                                    <tr>
                                                        <th class="ps-0 text-capitalize text-start"
                                                            scope="row"><?= convertToTitleCase($attr_key) ?> :
                                                        </th>
                                                        <td class="text-muted"><?= $attr_value ?></td>
                                                    </tr>
                                                <?php } ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                        <?php } else { ?>
                            <h2 class="text-muted">No player found.</h2>
                        <?php } ?>
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
