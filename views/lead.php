<?php
$pageTitle = "Lead";

$commonController = new CommonController();

$list = $commonController->paginateResources(DEFAULT_NETWORK_LEADS);

$selectedTag = $_GET['tag'] ?? '';

ob_start();
?>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center flex-wrap gap-2">
                        <div class="flex-grow-1">
                            <button class="btn btn-info add-btn" data-bs-toggle="modal" data-bs-target="#showModal"><i
                                        class="ri-add-fill me-1 align-bottom"></i> Add Company
                            </button>
                        </div>
                        <div class="flex-shrink-0">
                            <div class="hstack text-nowrap gap-2">
                                <button class="btn btn-soft-danger" id="remove-actions" onClick="deleteMultiple()"><i
                                            class="ri-delete-bin-2-line"></i></button>
                                <button class="btn btn-danger"><i class="ri-filter-2-line me-1 align-bottom"></i>
                                    Filters
                                </button>
                                <button type="button" id="dropdownMenuLink1" data-bs-toggle="dropdown"
                                        aria-expanded="false" class="btn btn-soft-info"><i class="ri-more-2-fill"></i>
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink1">
                                    <li><a class="dropdown-item" href="#">All</a></li>
                                    <li><a class="dropdown-item" href="#">Last Week</a></li>
                                    <li><a class="dropdown-item" href="#">Last Month</a></li>
                                    <li><a class="dropdown-item" href="#">Last Year</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--end col-->
        <div class="col-lg-12">
            <div class="card" id="companyList">
                <div class="card-header">
                    <form method="get" action="<?= home_url('lead') ?>">
                        <div class="row g-2">
                            <div class="col-md-3">
                                <div class="search-box">
                                    <input type="text" class="form-control search" name="s"
                                           placeholder="Search for webiste..." value="<?= $_GET['s'] ?? '' ?>"/>
                                    <i class="ri-search-line search-icon"></i>
                                </div>
                            </div>
                            <div class="col-md-3 ms-auto">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="text-muted">Sort by: </span>
                                    <div class="flex-grow-1">
                                        <select class="form-control mb-0" data-choices data-choices-search-false
                                                name="tag"
                                                onchange="this.form.submit()">
                                            <option value="">-- Select Tag --</option>
                                            <?php foreach ($list['tags'] as $tag): ?>
                                                <option value="<?= htmlspecialchars($tag) ?>" <?= $tag === $selectedTag ? 'selected' : '' ?>>
                                                    <?= htmlspecialchars($tag) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <a class="btn btn-soft-success w-auto ms-2" href="<?= home_url("lead") ?>"><i
                                        class="ri-refresh-line me-1 align-bottom"></i>Reset</a>
                        </div>
                    </form>
                </div>
                <div class="card-body">
                    <div id="tasksList">
                        <div class="table-responsive table-card mb-3">
                            <table class="table align-middle table-nowrap mb-0" id="customerTable">
                                <thead class="table-light">
                                <tr>
                                    <th class="sort" scope="col">Title</th>
                                    <th class="sort" scope="col">Description</th>
                                    <th class="sort" scope="col">Tags</th>
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
                                                        <ul class="list-inline tasks-list-menu mb-0">
                                                            <?php foreach ($item['social_links'] as $slug => $link):
                                                                if (!empty($link)): ?>
                                                                    <li class="list-inline-item"><a
                                                                                target="_blank"
                                                                                rel="noopener noreferrer"
                                                                                href="<?= $link ?>"><i
                                                                                    class="ri-<?= $slug ?>-fill align-bottom me-2 text-muted"></i></a>
                                                                    </li>
                                                                <?php
                                                                endif;
                                                            endforeach; ?>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </td>
                                            <td><?= truncateString($item['description'], 50) ?></td>
                                            <td><?= implode(", ", $item['tags']) ?></td>
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
                                    <p class="text-muted mb-0">We've searched more than 150+ companies We did not find
                                        any companies for you search.</p>
                                </div>
                            </div>
                        </div>
                        <?php
                        includeFileWithVariables('components/pagination.php', array("count" => $list['total_items']));
                        ?>
                    </div>

                </div>
            </div>
            <!--end card-->
        </div>
        <!--end col-->
    </div>

<?php
$pageContent = ob_get_clean();

include 'layout.php';
