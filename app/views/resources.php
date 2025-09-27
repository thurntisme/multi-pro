<?php
$pageTitle = "Resources";

require_once DIR . '/controllers/BlogController.php';
$blogController = new BlogController();

$resources = $blogController->apiListBlogs();

ob_start();
?>

<section class="section pb-0 hero-section" id="hero">
    <div class="bg-overlay bg-overlay-pattern"></div>
    <div class="container py-5">
        <div class="row">
            <div class="col-xxl-3">
                <div class="card">
                    <div class="card-body p-4">
                        <div class="search-box">
                            <p class="text-muted">Search</p>
                            <div class="position-relative d-flex align-items-center justify-content-center gap-1">
                                <form method="get" action="<?= App\Helpers\Network::home_url('resources') ?>">
                                    <input type="text" class="form-control rounded bg-light border-light"
                                        placeholder="Search..." name="s" value="<?= $_GET['s'] ?? '' ?>">
                                    <i class="mdi mdi-magnify search-icon"></i>
                                </form>
                                <a href="<?= App\Helpers\Network::home_url("resources") ?>" class="btn btn-danger"><i
                                        class="ri-delete-bin-2-fill align-bottom"></i></a>
                            </div>
                        </div>

                        <div class="mt-4 pt-4 border-top border-dashed border-bottom-0 border-start-0 border-end-0">
                            <p class="text-muted">Categories</p>

                            <ul class="list-unstyled fw-medium">
                                <?php foreach (DEFAULT_BLOG_CATEGORIES as $key => $value) { ?>
                                    <li><a href="<?= generatePageUrl(['cat' => $key]) ?>"
                                            class="<?= !empty($_GET['cat']) && $_GET['cat'] === $key ? '' : 'text-muted' ?> py-2 d-block"><i
                                                class="mdi mdi-chevron-right me-1"></i> <?= $value ?></a></li>
                                <?php } ?>
                            </ul>
                        </div>

                        <div class="mt-4 pt-4 border-top border-dashed border-bottom-0 border-start-0 border-end-0">
                            <p class="text-muted">Archive</p>

                            <ul class="list-unstyled fw-medium">
                                <li><a href="javascript: void(0);" class="text-muted py-2 d-block"><i
                                            class="mdi mdi-chevron-right me-1"></i> 2024 <span
                                            class="badge badge-soft-success rounded-pill float-end ms-1 font-size-12">03</span></a>
                                </li>
                                <li><a href="javascript: void(0);" class="text-muted py-2 d-block"><i
                                            class="mdi mdi-chevron-right me-1"></i> 2023 <span
                                            class="badge badge-soft-success rounded-pill float-end ms-1 font-size-12">06</span></a>
                                </li>
                                <li><a href="javascript: void(0);" class="text-muted py-2 d-block"><i
                                            class="mdi mdi-chevron-right me-1"></i> 2022 <span
                                            class="badge badge-soft-success rounded-pill float-end ms-1 font-size-12">05</span></a>
                                </li>
                                <li><a href="javascript: void(0);" class="text-muted py-2 d-block"><i
                                            class="mdi mdi-chevron-right me-1"></i> 2021 <span
                                            class="badge badge-soft-success rounded-pill float-end ms-1 font-size-12">05</span></a>
                                </li>
                                <li><a href="javascript: void(0);" class="text-muted py-2 d-block"><i
                                            class="mdi mdi-chevron-right me-1"></i> 2020 <span
                                            class="badge badge-soft-success rounded-pill float-end ms-1 font-size-12">05</span></a>
                                </li>
                            </ul>
                        </div>

                        <div class="mt-4 pt-4 border-top border-dashed border-bottom-0 border-start-0 border-end-0">
                            <p class="text-muted mb-2">Popular Posts</p>

                            <div class="list-group list-group-flush">

                                <a href="javascript: void(0);" class="list-group-item text-muted py-3 px-2">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0 me-3">
                                            <img src="assets/images/small/img-7.jpg" alt=""
                                                class="avatar-md h-auto d-block rounded">
                                        </div>
                                        <div class="flex-grow-1 overflow-hidden">
                                            <h5 class="fs-15 text-truncate">Beautiful Day with Friends</h5>
                                            <p class="mb-0 text-truncate">10 Apr, 2024</p>
                                        </div>
                                    </div>
                                </a>

                                <a href="javascript: void(0);" class="list-group-item text-muted py-3 px-2">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0 me-3">
                                            <img src="assets/images/small/img-4.jpg" alt=""
                                                class="avatar-md h-auto d-block rounded">
                                        </div>
                                        <div class="flex-grow-1 overflow-hidden">
                                            <h5 class="fs-15 text-truncate">Drawing a sketch</h5>
                                            <p class="mb-0 text-truncate">24 Mar, 2024</p>
                                        </div>
                                    </div>
                                </a>

                                <a href="javascript: void(0);" class="list-group-item text-muted py-3 px-2">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0 me-3">
                                            <img src="assets/images/small/img-6.jpg" alt=""
                                                class="avatar-md h-auto d-block rounded">
                                        </div>
                                        <div class="flex-grow-1 overflow-hidden">
                                            <h5 class="fs-15 text-truncate">Project discussion with team</h5>
                                            <p class="mb-0 text-truncate">11 Mar, 2024</p>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>

                        <div class="mt-4 pt-4 border-top border-dashed border-bottom-0 border-start-0 border-end-0">
                            <p class="text-muted">Tags</p>

                            <div class="d-flex flex-wrap gap-2 widget-tag">
                                <div><a href="javascript: void(0);"
                                        class="badge bg-light text-muted font-size-12">Design</a>
                                </div>
                                <div><a href="javascript: void(0);"
                                        class="badge bg-light text-muted font-size-12">Development</a>
                                </div>
                                <div><a href="javascript: void(0);"
                                        class="badge bg-light text-muted font-size-12">Business</a>
                                </div>
                                <div><a href="javascript: void(0);"
                                        class="badge bg-light text-muted font-size-12">Project</a>
                                </div>
                                <div><a href="javascript: void(0);"
                                        class="badge bg-light text-muted font-size-12">Travel</a>
                                </div>
                                <div><a href="javascript: void(0);"
                                        class="badge bg-light text-muted font-size-12">Lifestyle</a>
                                </div>
                                <div><a href="javascript: void(0);"
                                        class="badge bg-light text-muted font-size-12">Photography</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xxl-9">
                <div class="row gx-4">
                    <?php if (count($resources['list']) > 0) {
                        foreach ($resources['list'] as $resource) { ?>
                            <div class="col-xxl-12">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row g-4">
                                            <div class="col-xxl-3 col-lg-5">
                                                <img src="<?= App\Helpers\Network::home_url('assets/images/blog.jpg') ?>" alt=""
                                                    class="img-fluid rounded w-100 object-fit-cover">
                                            </div><!--end col-->
                                            <div class="col-xxl-9 col-lg-7">
                                                <p class="mb-2 text-primary text-uppercase"><a
                                                        href="<?= App\Helpers\Network::home_url('resources?cat=' . $resource['category']) ?>"><?= $resource['category'] ?? '' ?></a>
                                                </p>
                                                <a
                                                    href="<?= App\Helpers\Network::home_url('resources/' . $resource['slug']) ?>">
                                                    <h5 class="fs-15 fw-semibold"><?= $resource['title'] ?? '' ?></h5>
                                                </a>
                                                <div class="d-flex align-items-center gap-2 mb-3 flex-wrap">
                                                    <span class="text-muted"><i class="ri-calendar-event-line me-1"></i>
                                                        <?= $resource['created_at'] ?? '' ?></span>
                                                    | <span class="text-muted"><i class="ri-eye-line me-1"></i> 713</span>
                                                    |
                                                    <a href="pages-profile.html"><i class="ri-user-3-line me-1"></i>
                                                        Admin</a>
                                                </div>
                                                <p class="text-muted mb-2"><?= $resource['excerpt'] ?? '' ?></p>
                                                <a href="<?= App\Helpers\Network::home_url('resources/' . $resource['slug']) ?>"
                                                    class="text-decoration-underline">Read
                                                    more <i class="ri-arrow-right-line"></i></a>
                                                <div class="d-flex align-items-center gap-2 mt-3 flex-wrap">
                                                    <?php
                                                    $tags = !empty($resource['tags']) ? explode(',', $resource['tags']) : [];
                                                    if (count($tags) > 0) {
                                                        foreach ($tags as $tag) { ?>
                                                            <a href="<?= App\Helpers\Network::home_url('resources?tag=' . $tag) ?>"
                                                                class="badge text-success bg-success-subtle">#<?= $tag ?></a>
                                                        <?php }
                                                    } ?>
                                                </div>
                                            </div><!--end col-->
                                        </div><!--end row-->
                                    </div>
                                </div>
                            </div><!--end col-->
                        <?php }
                    } else { ?>
                        <div>No resources found.</div>
                    <?php } ?>
                </div><!--end row-->

                <?php
                includeFileWithVariables('components/pagination.php', array("count" => $resources['count']));
                ?>
            </div><!--end col-->
        </div>
    </div>
</section>

<?php
$pageContent = ob_get_clean();