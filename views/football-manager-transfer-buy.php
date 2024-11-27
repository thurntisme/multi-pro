<?php
$pageTitle = "Football Manager Transfer";

require_once DIR . '/functions/generate-player.php';
require_once DIR . '/controllers/FootballPlayerController.php';
$players = getPlayersJson();
$commonController = new CommonController();
$list = $commonController->convertResources($players);

$footballPlayerController = new FootballPlayerController();

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
            <div class="col-xl-3 col-sm-6">
                <div class="card card-animate">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="flex-grow-1">
                                <h6 class="text-muted mb-3">Total Buy</h6>
                                <h2 class="mb-0">$<span class="counter-value" data-target="243"></span><small class="text-muted fs-13">.10k</small></h2>
                            </div>
                            <div class="flex-shrink-0 avatar-sm">
                                <div class="avatar-title bg-danger-subtle text-danger fs-22 rounded">
                                    <i class="ri-shopping-bag-line"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--end card-->
            </div>
            <!--end col-->
            <div class="col-xl-3 col-sm-6">
                <div class="card card-animate">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="flex-grow-1">
                                <h6 class="text-muted mb-3">Total Sell</h6>
                                <h2 class="mb-0">$<span class="counter-value" data-target="658"></span><small class="text-muted fs-13">.00k</small></h2>
                            </div>
                            <div class="flex-shrink-0 avatar-sm">
                                <div class="avatar-title bg-info-subtle text-info fs-22 rounded">
                                    <i class="ri-funds-line"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--end card-->
            </div>
            <!--end col-->
            <div class="col-xl-3 col-sm-6">
                <div class="card card-animate">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="flex-grow-1">
                                <h6 class="text-muted mb-3">Today's Buy</h6>
                                <h2 class="mb-0">$<span class="counter-value" data-target="104"></span><small class="text-muted fs-13">.85k</small></h2>
                            </div>
                            <div class="flex-shrink-0 avatar-sm">
                                <div class="avatar-title bg-warning-subtle text-warning fs-22 rounded">
                                    <i class="ri-arrow-left-down-fill"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--end card-->
            </div>
            <!--end col-->
            <div class="col-xl-3 col-sm-6">
                <div class="card card-animate">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="flex-grow-1">
                                <h6 class="text-muted mb-3">Today's Sell</h6>
                                <h2 class="mb-0">$<span class="counter-value" data-target="87"></span><small class="text-muted fs-13">.35k</small></h2>
                            </div>
                            <div class="flex-shrink-0 avatar-sm">
                                <div class="avatar-title bg-success-subtle text-success fs-22 rounded">
                                    <i class="ri-arrow-right-up-fill"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--end card-->
            </div>
            <!--end col-->
        </div>
        <div class="row">
            <div class="col-xxl-9">
                <div class="card card-height-100">
                    <div class="card-header border-0 align-items-center d-flex">
                        <h4 class="card-title mb-0 flex-grow-1">Player Data</h4>
                    </div><!-- end card header -->
                    <div class="card-body p-0">
                        <div class="bg-light-subtle border-top-dashed border border-start-0 border-end-0 border-bottom-dashed py-3 px-4">
                            <div class="row align-items-center">
                                <div class="col-6">
                                    <div class="d-flex flex-wrap gap-4 align-items-center">
                                        <div>
                                            <h3 class="fs-19">$46,959.<small class="fs-14 text-muted">00</small></h3>
                                            <p class="text-muted text-uppercase fw-medium mb-0">Bitcoin (BTC) <small class="badge bg-success-subtle text-success"><i class="ri-arrow-right-up-line align-bottom"></i> 2.15%</small></p>
                                        </div>
                                    </div>
                                </div><!-- end col -->
                                <div class="col-6">
                                    <div class="d-flex">
                                        <div class="d-flex justify-content-end text-end flex-wrap gap-4 ms-auto">
                                            <div class="pe-3">
                                                <h6 class="mb-2 text-muted">High</h6>
                                                <h5 class="text-success mb-0">$28,722.76</h5>
                                            </div>
                                            <div class="pe-3">
                                                <h6 class="mb-2 text-muted">Low</h6>
                                                <h5 class="text-danger mb-0">$68,789.63</h5>
                                            </div>
                                            <div>
                                                <h6 class="mb-2 text-muted">Market Volume</h6>
                                                <h5 class="text-danger mb-0">$888,411,910</h5>
                                            </div>
                                        </div>
                                    </div>
                                </div><!-- end col -->
                            </div><!-- end row -->
                        </div>
                    </div><!-- end cardbody -->
                    <div class="card-body p-0 pb-3">
                        <div id="Market_chart" data-colors='["--vz-success", "--vz-danger"]' class="apex-charts" dir="ltr"></div>
                    </div><!-- end cardbody -->
                </div><!-- end card -->
            </div>
            <!--end col-->
            <div class="col-xxl-3">
                <div class="card card-height-100">
                    <div class="card-header">
                        <ul class="nav nav-tabs-custom rounded card-header-tabs nav-justified border-bottom-0 mx-n3" role="tablist">
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
                                <div class="p-3 bg-warning-subtle">
                                    <div class="float-end ms-2">
                                        <h6 class="text-warning mb-0">Balance : <span class="text-body">$12,426.07</span></h6>
                                    </div>
                                    <h6 class="mb-0 text-danger">Buy Player</h6>
                                </div>
                                <div class="p-3">
                                    <div>
                                        <div class="input-group mb-3">
                                            <label class="input-group-text">Market Value</label>
                                            <input type="text" class="form-control" placeholder="0" readonly>
                                            <label class="input-group-text">$</label>
                                        </div>

                                        <div class="input-group mb-3">
                                            <label class="input-group-text">Your Price</label>
                                            <input type="text" class="form-control" placeholder="2.045585">
                                            <label class="input-group-text">$</label>
                                        </div>

                                        <div class="input-group mb-0">
                                            <label class="input-group-text">Bonus</label>
                                            <input type="text" class="form-control" placeholder="2700.16">
                                            <label class="input-group-text">$</label>
                                        </div>
                                    </div>
                                    <div class="mt-3 pt-2">
                                        <div class="d-flex mb-2">
                                            <div class="flex-grow-1">
                                                <p class="fs-13 mb-0">Transaction Fees<span class="text-muted ms-1 fs-11">(0.05%)</span></p>
                                            </div>
                                            <div class="flex-shrink-0">
                                                <h6 class="mb-0">$1.08</h6>
                                            </div>
                                        </div>
                                        <div class="d-flex mb-2">
                                            <div class="flex-grow-1">
                                                <p class="fs-13 mb-0">Minimum Received<span class="text-muted ms-1 fs-11">(2%)</span></p>
                                            </div>
                                            <div class="flex-shrink-0">
                                                <h6 class="mb-0">$7.85</h6>
                                            </div>
                                        </div>
                                        <div class="d-flex">
                                            <div class="flex-grow-1">
                                                <p class="fs-13 mb-0">Estimated Rate</p>
                                            </div>
                                            <div class="flex-shrink-0">
                                                <h6 class="mb-0">1 BTC ~ $46982.70</h6>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mt-3 pt-2">
                                        <button type="button" class="btn btn-primary w-100">Enter Auction</button>
                                    </div>
                                </div>
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
