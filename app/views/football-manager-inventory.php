<?php
$pageTitle = "Football Manager - Inventory";
$commonController = new CommonController();
$list = $commonController->convertResources(DEFAULT_STORE_ITEMS);

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
                <ul class="nav nav-tabs nav-border-top nav-border-top-primary" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link" href="<?= App\Helpers\Network::home_url("football-manager/store") ?>">
                            Items
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active"
                            href="<?= App\Helpers\Network::home_url('football-manager/store/inventory') ?>">
                            My Inventory
                        </a>
                    </li>
                </ul>
            </div><!-- end card-body -->
        </div>
    </div>
    <div class="col-lg-12">
        <div class="row">
            <div class="col-lg-3">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex mb-3">
                            <div class="flex-grow-1">
                                <h5 class="fs-16">Filters</h5>
                            </div>
                            <div class="flex-shrink-0">
                                <a href="#" class="text-decoration-underline" id="clearall">Clear All</a>
                            </div>
                        </div>

                        <div class="filter-choices-input">
                            <input class="form-control" data-choices data-choices-removeItem type="text"
                                id="filter-choices-input" value="T-Shirts" />
                        </div>
                    </div>

                    <div class="accordion accordion-flush filter-accordion">

                        <div class="card-body border-bottom">
                            <div>
                                <p class="text-muted text-uppercase fs-12 fw-medium mb-2">Products</p>
                                <ul class="list-unstyled mb-0 filter-list">
                                    <li>
                                        <a href="#" class="d-flex py-1 align-items-center">
                                            <div class="flex-grow-1">
                                                <h5 class="fs-13 mb-0 listname">Grocery</h5>
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#" class="d-flex py-1 align-items-center">
                                            <div class="flex-grow-1">
                                                <h5 class="fs-13 mb-0 listname">Fashion</h5>
                                            </div>
                                            <div class="flex-shrink-0 ms-2">
                                                <span class="badge bg-light text-muted">5</span>
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#" class="d-flex py-1 align-items-center">
                                            <div class="flex-grow-1">
                                                <h5 class="fs-13 mb-0 listname">Watches</h5>
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#" class="d-flex py-1 align-items-center">
                                            <div class="flex-grow-1">
                                                <h5 class="fs-13 mb-0 listname">Electronics</h5>
                                            </div>
                                            <div class="flex-shrink-0 ms-2">
                                                <span class="badge bg-light text-muted">5</span>
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#" class="d-flex py-1 align-items-center">
                                            <div class="flex-grow-1">
                                                <h5 class="fs-13 mb-0 listname">Furniture</h5>
                                            </div>
                                            <div class="flex-shrink-0 ms-2">
                                                <span class="badge bg-light text-muted">6</span>
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#" class="d-flex py-1 align-items-center">
                                            <div class="flex-grow-1">
                                                <h5 class="fs-13 mb-0 listname">Automotive Accessories</h5>
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#" class="d-flex py-1 align-items-center">
                                            <div class="flex-grow-1">
                                                <h5 class="fs-13 mb-0 listname">Appliances</h5>
                                            </div>
                                            <div class="flex-shrink-0 ms-2">
                                                <span class="badge bg-light text-muted">7</span>
                                            </div>
                                        </a>
                                    </li>

                                    <li>
                                        <a href="#" class="d-flex py-1 align-items-center">
                                            <div class="flex-grow-1">
                                                <h5 class="fs-13 mb-0 listname">Kids</h5>
                                            </div>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <div class="accordion-item">
                            <h2 class="accordion-header" id="flush-headingBrands">
                                <button class="accordion-button bg-transparent shadow-none" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#flush-collapseBrands"
                                    aria-expanded="true" aria-controls="flush-collapseBrands">
                                    <span class="text-muted text-uppercase fs-12 fw-medium">Brands</span> <span
                                        class="badge bg-success rounded-pill align-middle ms-1 filter-badge"></span>
                                </button>
                            </h2>

                            <div id="flush-collapseBrands" class="accordion-collapse collapse show"
                                aria-labelledby="flush-headingBrands">
                                <div class="accordion-body text-body pt-0">
                                    <div class="search-box search-box-sm">
                                        <input type="text" class="form-control bg-light border-0" id="searchBrandsList"
                                            placeholder="Search Brands...">
                                        <i class="ri-search-line search-icon"></i>
                                    </div>
                                    <div class="d-flex flex-column gap-2 mt-3 filter-check">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="Boat"
                                                id="productBrandRadio5" checked>
                                            <label class="form-check-label" for="productBrandRadio5">Boat</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="OnePlus"
                                                id="productBrandRadio4">
                                            <label class="form-check-label" for="productBrandRadio4">OnePlus</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="Realme"
                                                id="productBrandRadio3">
                                            <label class="form-check-label" for="productBrandRadio3">Realme</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="Sony"
                                                id="productBrandRadio2">
                                            <label class="form-check-label" for="productBrandRadio2">Sony</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="JBL"
                                                id="productBrandRadio1" checked>
                                            <label class="form-check-label" for="productBrandRadio1">JBL</label>
                                        </div>

                                        <div>
                                            <button type="button"
                                                class="btn btn-link text-decoration-none text-uppercase fw-medium p-0">
                                                1,235
                                                More
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- end accordion-item -->

                        <div class="accordion-item">
                            <h2 class="accordion-header" id="flush-headingDiscount">
                                <button class="accordion-button bg-transparent shadow-none collapsed" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#flush-collapseDiscount"
                                    aria-expanded="true" aria-controls="flush-collapseDiscount">
                                    <span class="text-muted text-uppercase fs-12 fw-medium">Discount</span> <span
                                        class="badge bg-success rounded-pill align-middle ms-1 filter-badge"></span>
                                </button>
                            </h2>
                            <div id="flush-collapseDiscount" class="accordion-collapse collapse"
                                aria-labelledby="flush-headingDiscount">
                                <div class="accordion-body text-body pt-1">
                                    <div class="d-flex flex-column gap-2 filter-check">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="50% or more"
                                                id="productdiscountRadio6">
                                            <label class="form-check-label" for="productdiscountRadio6">50% or
                                                more</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="40% or more"
                                                id="productdiscountRadio5">
                                            <label class="form-check-label" for="productdiscountRadio5">40% or
                                                more</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="30% or more"
                                                id="productdiscountRadio4">
                                            <label class="form-check-label" for="productdiscountRadio4">
                                                30% or more
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="20% or more"
                                                id="productdiscountRadio3" checked>
                                            <label class="form-check-label" for="productdiscountRadio3">
                                                20% or more
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="10% or more"
                                                id="productdiscountRadio2">
                                            <label class="form-check-label" for="productdiscountRadio2">
                                                10% or more
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="Less than 10%"
                                                id="productdiscountRadio1">
                                            <label class="form-check-label" for="productdiscountRadio1">
                                                Less than 10%
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- end accordion-item -->

                        <div class="accordion-item">
                            <h2 class="accordion-header" id="flush-headingRating">
                                <button class="accordion-button bg-transparent shadow-none collapsed" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#flush-collapseRating"
                                    aria-expanded="false" aria-controls="flush-collapseRating">
                                    <span class="text-muted text-uppercase fs-12 fw-medium">Rating</span> <span
                                        class="badge bg-success rounded-pill align-middle ms-1 filter-badge"></span>
                                </button>
                            </h2>

                            <div id="flush-collapseRating" class="accordion-collapse collapse"
                                aria-labelledby="flush-headingRating">
                                <div class="accordion-body text-body">
                                    <div class="d-flex flex-column gap-2 filter-check">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="4 & Above Star"
                                                id="productratingRadio4" checked>
                                            <label class="form-check-label" for="productratingRadio4">
                                                <span class="text-muted">
                                                    <i class="mdi mdi-star text-warning"></i>
                                                    <i class="mdi mdi-star text-warning"></i>
                                                    <i class="mdi mdi-star text-warning"></i>
                                                    <i class="mdi mdi-star text-warning"></i>
                                                    <i class="mdi mdi-star"></i>
                                                </span> 4 & Above
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="3 & Above Star"
                                                id="productratingRadio3">
                                            <label class="form-check-label" for="productratingRadio3">
                                                <span class="text-muted">
                                                    <i class="mdi mdi-star text-warning"></i>
                                                    <i class="mdi mdi-star text-warning"></i>
                                                    <i class="mdi mdi-star text-warning"></i>
                                                    <i class="mdi mdi-star"></i>
                                                    <i class="mdi mdi-star"></i>
                                                </span> 3 & Above
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="2 & Above Star"
                                                id="productratingRadio2">
                                            <label class="form-check-label" for="productratingRadio2">
                                                <span class="text-muted">
                                                    <i class="mdi mdi-star text-warning"></i>
                                                    <i class="mdi mdi-star text-warning"></i>
                                                    <i class="mdi mdi-star"></i>
                                                    <i class="mdi mdi-star"></i>
                                                    <i class="mdi mdi-star"></i>
                                                </span> 2 & Above
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="1 Star"
                                                id="productratingRadio1">
                                            <label class="form-check-label" for="productratingRadio1">
                                                <span class="text-muted">
                                                    <i class="mdi mdi-star text-warning"></i>
                                                    <i class="mdi mdi-star"></i>
                                                    <i class="mdi mdi-star"></i>
                                                    <i class="mdi mdi-star"></i>
                                                    <i class="mdi mdi-star"></i>
                                                </span> 1
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- end accordion-item -->
                    </div>
                </div>
            </div>
            <div class="col-lg-9">
                <div class="row">
                    <?php foreach ($list['resources'] as $index => $item): ?>
                        <div class="col-lg-6">
                            <div class="card product">
                                <div class="card-body">
                                    <div class="row gy-3">
                                        <div class="col-sm-auto">
                                            <div class="avatar-lg bg-light rounded p-1">
                                                <img src="assets/images/products/img-8.png" alt=""
                                                    class="img-fluid d-block">
                                            </div>
                                        </div>
                                        <div class="col-sm">
                                            <h5 class="fs-14 text-truncate"><?= $item['name'] ?></h5>
                                            <ul class="list-inline text-muted">
                                                <li class="list-inline-item"><?= $item['description'] ?>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <!-- card body -->
                                <div class="card-footer">
                                    <div class="row align-items-center gy-3">
                                        <div class="col-sm">
                                            <div class="d-flex align-items-center gap-2 text-muted">
                                                <div>Price :</div>
                                                <h5 class="fs-14 mb-0"><span
                                                        class="product-line-price"><?= formatCurrency($item['price']) ?></span>
                                                </h5>
                                            </div>
                                        </div>
                                        <div class="col-sm-auto">
                                            <div class="d-flex align-items-center gap-2 text-muted">
                                                <?php if ($item['type'] === 'item') { ?>
                                                    <a href="<?= App\Helpers\Network::home_url('/football-manager/store/item?uuid=' . uniqid()) ?>"
                                                        class="btn btn-success btn-label right ms-auto"><i
                                                            class="ri-arrow-right-line label-icon align-bottom fs-16 ms-2"></i>
                                                        Take it</a>
                                                <?php } else { ?>
                                                    <a href="#" data-item-uuid="<?= uniqid() ?>"
                                                        data-item-slug="<?= $item['slug'] ?>"
                                                        data-item-type="<?= $item['type'] ?>" data-bs-toggle="modal"
                                                        data-bs-target="#inventoryItemModal"
                                                        class="btn btn-success btn-label right ms-auto btn-take-inventory-item"><i
                                                            class="ri-arrow-right-line label-icon align-bottom fs-16 ms-2"></i>
                                                        Take it</a>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- end card footer -->
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <?php
                includeFileWithVariables('components/pagination.php', array("count" => $list['total_items'], "perPage" => $list['per_page']));
                ?>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="inventoryItemModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    role="dialog" aria-labelledby="inventoryItemModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body p-5">
                <div class="text-center">
                    <h4 class="mb-3">Your Player</h4>
                    <p class="text-muted mb-0"> The transfer was not successfully received by us. the email of
                        the
                        recipient wasn't correct.</p>
                </div>
                <div class="card-body pb-5 pt-4" id="player-info">
                    <div class="row">
                        <div class="col-4 text-center">
                            <div class="profile-user position-relative d-inline-block mx-auto">
                                <img src="<?= App\Helpers\Network::home_url('assets/images/users/avatar-1.jpg') ?>"
                                    class="rounded-circle avatar-xl img-thumbnail user-profile-image"
                                    alt="user-profile-image">
                            </div>
                        </div>
                        <div class="col-8">
                            <div class="d-flex flex-column h-100 justify-content-center">
                                <h5 class="fs-16 mb-2" id="player-name">&nbsp;</h5>
                                <p class="mb-0 fs-13" id="player-nationality">&nbsp;</p>
                                <p class="text-muted mb-0 fs-12 mt-1" id="player-meta">&nbsp;</p>
                                <p class="text-muted mb-0 fs-12 mt-1"><span id="player-best_position">&nbsp;</span>
                                    <span id="player-ability"></span>
                                    <span id="player-playable_positions"></span>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="px-2 py-2 mt-4">
                        <p class="mb-1 fs-12">Mental <span class="float-end"><span id="mental-label">0</span></span>
                        </p>
                        <div class="progress mt-2" style="height: 6px;">
                            <div class="progress-bar progress-bar-striped bg-primary" role="progressbar"
                                style="width: 0" aria-valuenow="0" aria-valuemin="0" aria-valuemax="0"
                                id="mental-value"></div>
                        </div>

                        <p class="mt-3 mb-1 fs-12">Physical <span class="float-end"><span
                                    id="physical-label">0</span></span>
                        </p>
                        <div class="progress mt-2" style="height: 6px;">
                            <div class="progress-bar progress-bar-striped bg-primary" role="progressbar"
                                style="width: 0" aria-valuenow="0" aria-valuemin="0" aria-valuemax="0"
                                id="physical-value"></div>
                        </div>

                        <p class="mt-3 mb-1 fs-12">Technical <span class="float-end"><span
                                    id="technical-label">0</span></span>
                        </p>
                        <div class="progress mt-2" style="height: 6px;">
                            <div class="progress-bar progress-bar-striped bg-primary" role="progressbar"
                                style="width: 0" aria-valuenow="0" aria-valuemin="0" aria-valuemax="0"
                                id="technical-value"></div>
                        </div>

                        <p class="mt-3 mb-1 fs-12">Goalkeeping<span class="float-end"><span
                                    id="goalkeeping-label">0</span></span>
                        </p>
                        <div class="progress mt-2" style="height: 6px;">
                            <div class="progress-bar progress-bar-striped bg-primary" role="progressbar"
                                style="width: 0" aria-valuenow="0" aria-valuemin="0" aria-valuemax="0"
                                id="goalkeeping-value"></div>
                        </div>
                    </div>
                </div>
                <form action="">
                    <div class="hstack gap-2 justify-content-center">
                        <a href="javascript:void(0);" class="btn btn-link link-success fw-medium"
                            data-bs-dismiss="modal"><i class="ri-close-line me-1 align-middle"></i> Close</a>
                        <button type="submit" class="btn btn-success" data-bs-dismiss="modal">Join My Team
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php
$pageContent = ob_get_clean();

ob_start();
echo "
    <script type='text/javascript'>
        let apiUrl = '" . home_url("/api") . "';
    </script>
    <script src='" . home_url("/assets/js/pages/inventory.js") . "'></script>
";
$additionJs = ob_get_clean();
