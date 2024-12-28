<?php
require_once 'controllers/BlogController.php';
$pageTitle = "Blog";

$blogController = new BlogController();

if (!empty($_GET['cate'])) {
    $list = $blogController->listBlogsByCategory($_GET['cate']);
} else {
    $list = $blogController->listBlogs();
}

ob_start();
?>

<?php
include_once DIR . '/components/alert.php';
?>

    <div class="row">
        <div class="col-xxl-3">
            <div class="card">
                <div class="card-body p-4">
                    <form method="get" action="<?= home_url('app/blog') ?>">
                        <div class="search-box">
                            <p class="text-muted">Search</p>
                            <div class="d-flex justify-content-between">
                                <div class="position-relative flex-grow-1">
                                    <input type="text" class="form-control rounded bg-light border-light"
                                           placeholder="Enter keyword..." value="<?= $_GET['s'] ?? '' ?>" name="s">
                                    <i class="mdi mdi-magnify search-icon"></i>
                                </div>
                                <a href="<?= home_url("app/blog") ?>" class="btn btn-danger ms-1"><i
                                            class="ri-delete-bin-2-fill align-bottom"></i></a>
                            </div>
                        </div>
                    </form>

                    <div class="mt-4 pt-4 border-top border-dashed border-bottom-0 border-start-0 border-end-0">
                        <p class="text-muted">Categories</p>

                        <ul class="list-unstyled fw-medium">
                            <li><a href="<?= home_url('app/blog') ?>"
                                   class="text-<?= empty($_GET['cate']) ? 'link' : 'muted' ?> py-2 d-block"><i
                                            class="mdi mdi-chevron-right me-1"></i> All</a></li>
                            <?php foreach (DEFAULT_BLOG_CATEGORIES as $key => $value) { ?>
                                <li><a href="<?= home_url('app/blog?cate=' . $key) ?>"
                                       class="text-<?= !empty($_GET['cate']) && $_GET['cate'] == $key ? 'link' : 'muted' ?> py-2 d-block"><i
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
                        <p class="text-muted">Tags</p>

                        <div class="d-flex flex-wrap gap-2 widget-tag">
                            <div><a href="javascript: void(0);"
                                    class="badge bg-light text-muted font-size-12">Design</a></div>
                            <div><a href="javascript: void(0);" class="badge bg-light text-muted font-size-12">Development</a>
                            </div>
                            <div><a href="javascript: void(0);"
                                    class="badge bg-light text-muted font-size-12">Business</a></div>
                            <div><a href="javascript: void(0);"
                                    class="badge bg-light text-muted font-size-12">Project</a></div>
                            <div><a href="javascript: void(0);"
                                    class="badge bg-light text-muted font-size-12">Travel</a></div>
                            <div><a href="javascript: void(0);"
                                    class="badge bg-light text-muted font-size-12">Lifestyle</a></div>
                            <div><a href="javascript: void(0);" class="badge bg-light text-muted font-size-12">Photography</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xxl-9">
            <form method="get" action="<?= home_url('app/blog') ?>">
                <div class="row g-4 mb-3">
                    <div class="col-sm-auto">
                        <div>
                            <a href="<?= home_url('app/blog' . '/new') ?>" class="btn btn-success"><i
                                        class="ri-add-line align-bottom me-1"></i> Add New</a>
                        </div>
                    </div>
                </div><!--end row-->
            </form>
            <div class="row gx-4">
                <?php if (count($list['list']) > 0) {
                    foreach ($list['list'] as $item) { ?>
                        <div class="col-xxl-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row g-4">
                                        <div class="col-xxl-3 col-lg-5">
                                            <img src="<?= $item['thumbnail'] ?? home_url('assets/images/blog.jpg') ?>"
                                                 alt="<?= $item['title'] ?>"
                                                 class="img-fluid rounded w-100 object-fit-cover">
                                        </div><!--end col-->
                                        <div class="col-xxl-9 col-lg-7">
                                            <p class="mb-2 text-primary text-uppercase"><?= $item['category'] ?? '' ?></p>
                                            <a href="<?= home_url('app/blog' . '/detail?id=' . $item['id']) ?>">
                                                <h5 class="fs-15 fw-semibold"><?= $item['title'] ?></h5>
                                            </a>
                                            <div class="d-flex align-items-center gap-2 mb-3 flex-wrap">
                                                <span class="text-muted"><i
                                                            class="ri-calendar-event-line me-1"></i> <?= $commonController->convertDate($item['created_at']) ?></span>
                                                | <span class="text-muted"><i class="ri-eye-line me-1"></i> 451</span> |
                                                <a href="pages-profile.html"><i class="ri-user-3-line me-1"></i>
                                                    Admin</a>
                                            </div>
                                            <p class="text-muted mb-2"><?= generateShortDescription($item['content']) ?></p>
                                            <a href="<?= home_url('app/blog' . '/detail?id=' . $item['id']) ?>"
                                               class="text-decoration-underline">Read more <i
                                                        class="ri-arrow-right-line"></i></a>
                                            <div class="d-flex align-items-center gap-2 mt-3 flex-wrap">
                                                <?php
                                                if (count(explode(",", $item['tags'])) > 0) {
                                                    foreach (explode(",", $item['tags']) as $tag) { ?>
                                                        <a href="#!"
                                                           class="badge text-success bg-success-subtle"><?= $tag ?></a>
                                                    <?php }
                                                }
                                                ?>
                                            </div>
                                        </div><!--end col-->
                                    </div><!--end row-->
                                </div>
                            </div>
                        </div><!--end col-->
                    <?php }
                } ?>
            </div><!--end row-->
            <?php
            includeFileWithVariables('components/pagination.php', array("count" => $list['count']));
            ?>
        </div><!--end col-->
    </div>

<?php
$pageContent = ob_get_clean();

include 'layout.php';
