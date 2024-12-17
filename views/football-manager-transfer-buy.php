<?php
$pageTitle = "Football Manager Transfer";

require_once DIR . '/functions/generate-player.php';
require_once DIR . '/controllers/FootballTeamController.php';
require_once DIR . '/controllers/FootballTransferController.php';

$player = getPlayerJsonByUuid($_GET['p_uuid']);
$commonController = new CommonController();

$footballTransferController = new FootballTransferController();

$footballTeamController = new FootballTeamController();
$myTeam = $footballTeamController->getMyTeam();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $footballTransferController->createTransferBuyPlayer();
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
            <div class="row">
                <div class="col-xxl-9">
                    <div class="card card-height-100">
                        <div class="card-header hstack align-items-baseline d-flex">
                            <h4 class="card-title mb-0 me-2 font-weight-bold"><?= $player['name'] ?></h4>
                            <p class="text-muted mb-0 flex-grow-1"
                                id="player-nationality"><?= $player['nationality'] ?></p>
                            <p class="text-muted mb-0"><span
                                        id="player-best_position"><?= $player['best_position'] ?></span>
                                (<span id="player-ability"><?= $player['ability'] ?></span>)
                                |
                                <span id="player-playable_positions"><?= implode(", ", $player['playable_positions']) ?></span>
                            </p>
                        </div><!-- end card header -->
                        <div class="card-body">
                            <div class="row">
                                <?php
                                    if (count($player['attributes']) > 0) {
                                        foreach ($player['attributes'] as $key => $attributes){ ?>
                                            <div class="col-4">
                                                <h6 class="card-title flex-grow-1 mb-3 fs-15"><?= ucfirst($key) ?></h6>
                                                <table class="table table-borderless mb-0">
                                                    <tbody>
                                                        <?php foreach ($attributes as $key => $val) { ?>
                                                            <tr>
                                                                <th class="ps-0" scope="row"><?= ucwords(str_replace('_', ' ', $key)) ?> :</th>
                                                                <td class="text-muted"><?= $val ?></td>
                                                            </tr>
                                                        <?php } ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                    <?php }
                                    }
                                ?>
                            </div>
                        </div>
                    </div><!-- end card -->
                </div>
                <!--end col-->
                <div class="col-xxl-3">
                    <div class="card card-height-100">
                        <div class="card-header">
                            <ul class="nav nav-tabs-custom rounded card-header-tabs nav-justified border-bottom-0 mx-n3"
                                role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" data-bs-toggle="tab" href="#cryptoBuy" role="tab">
                                        Buy
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div class="card-body p-0">
                            <div class="tab-content text-muted">
                                <div class="tab-pane active" id="cryptoBuy" role="tabpanel">
                                    <?php
                                    $market_value = formatCurrency($player['market_value'], false);
                                    $fees = $player['market_value'] * 0.05 / 100;
                                    $total_amount = $player['market_value'] + $fees;
                                    ?>
                                    <?php if ($myTeam['budget'] >= $player['market_value']) { ?>
                                        <div class="p-3 bg-warning-subtle">
                                            <div class="float-end ms-2">
                                                <h6 class="text-warning mb-0">Balance : <span
                                                            class="text-body"><?= formatCurrency($myTeam['budget'] - $total_amount) ?></span>
                                                </h6>
                                            </div>
                                            <h6 class="mb-0 text-danger">Remaining</h6>
                                        </div>
                                    <?php } ?>
                                    <form method="POST" action="<?= $_SERVER['REQUEST_URI'] ?>" class="p-3">
                                        <input type="hidden" name="player_uuid" value="<?= $_GET['p_uuid'] ?>"/>
                                        <div>
                                            <div class="input-group mb-3">
                                                <label class="input-group-text">Market Value</label>
                                                <input type="text" class="form-control text-end"
                                                       placeholder="<?= $market_value ?>" readonly>
                                                <label class="input-group-text">$</label>
                                            </div>

                                            <div class="input-group mb-3">
                                                <label class="input-group-text">Your Price</label>
                                                <input type="text" class="form-control text-end"
                                                       placeholder="<?= $market_value ?>" readonly>
                                                <label class="input-group-text">$</label>
                                            </div>
                                        </div>
                                        <div class="mt-3 pt-2">
                                            <div class="d-flex mb-2">
                                                <div class="flex-grow-1">
                                                    <p class="fs-13 mb-0">Transaction Fees<span
                                                                class="text-muted ms-1 fs-11">(0.05%)</span></p>
                                                </div>
                                                <div class="flex-shrink-0">
                                                    <h6 class="mb-0"><?= formatCurrency($fees) ?></h6>
                                                </div>
                                            </div>
                                            <div class="d-flex">
                                                <div class="flex-grow-1">
                                                    <p class="fs-13 mb-0">Total amount</p>
                                                </div>
                                                <div class="flex-shrink-0">
                                                    <h6 class="mb-0"><?= formatCurrency($total_amount) ?></h6>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mt-3 pt-2">
                                            <?php if ($myTeam['budget'] >= $player['market_value']) { ?>
                                                <button type="submit" class="btn btn-primary w-100">Enter Auction</button>
                                            <?php } else { ?>
                                                <div class="alert alert-danger" role="alert">
                                                    Insufficient budget! You need <b><?= formatCurrency($total_amount - $myTeam['budget']) ?></b> more to complete this transfer.
                                                </div>
                                            <?php } ?>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--end col-->
            </div>
        </div>
        <!--end col-->
    </div>

<?php
$pageContent = ob_get_clean();

include 'layout.php';
