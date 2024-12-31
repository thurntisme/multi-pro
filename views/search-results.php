<?php
require_once 'controllers/SearchController.php';

$url = extractPathFromCurrentUrl();
$parts = explode("/", $url);
$firstSlug = $parts[1] ?? '';

$pageTitle = "Search Results";
$keyword = $firstSlug !== 'search' ? $firstSlug : $_GET['s'];

$searchController = new SearchController();
$result = $searchController->listSearchResults($keyword);

$searchCategories = [
    [
        'name' => 'To Do',
        'icon' => 'ri-task-line',
        'slug' => 'todo'
    ],
    [
        'name' => 'Note',
        'icon' => 'ri-sticky-note-line',
        'slug' => 'note'
    ],
    [
        'name' => 'Bookmark',
        'icon' => 'ri-bookmark-line',
        'slug' => 'bookmark'
    ],
    [
        'name' => 'Book',
        'icon' => 'ri-book-open-line',
        'slug' => 'book'
    ],
    [
        'name' => 'Git',
        'icon' => 'ri-git-merge-line',
        'slug' => 'git'
    ],
    [
        'name' => 'Course',
        'icon' => 'ri-graduation-cap-line',
        'slug' => 'course'
    ],
    [
        'name' => 'Blog',
        'icon' => 'ri-article-line',
        'slug' => 'blog'
    ]
];

ob_start();
?>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header border-0">
                <div class="row justify-content-center mb-4">
                    <div class="col-lg-6">
                        <form method="get" action="<?= home_url('app/search') ?>">
                            <div class="row g-2">
                                <div class="col">
                                    <div class="position-relative mb-3">
                                        <input type="text"
                                            class="form-control form-control-lg bg-light border-light"
                                            placeholder="Search here.." name="s"
                                            value="<?= trim($keyword) ?? '' ?>">
                                        <a class="btn btn-link link-success btn-lg position-absolute end-0 top-0"
                                            data-bs-toggle="offcanvas" data-bs-target="#offcanvasExample"
                                            aria-controls="offcanvasExample"><i class="ri-mic-fill"></i></a>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <button type="submit" class="btn btn-primary btn-lg waves-effect waves-light"><i
                                            class="mdi mdi-magnify me-1"></i> Search
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <!--end col-->
                    <?php if (isset($keyword)) { ?>
                        <div class="col-lg-12">
                            <h5 class="fs-16 fw-semibold text-center mb-0">Showing results for "<span
                                    class="text-primary fw-medium fst-italic"><?= trim($keyword) ?></span> "
                            </h5>
                        </div>
                    <?php } ?>
                </div>
                <!--end row-->

                <div class="offcanvas offcanvas-top" tabindex="-1" id="offcanvasExample"
                    aria-labelledby="offcanvasExampleLabel">
                    <div class="offcanvas-body">
                        <button type="button" class="btn-close text-reset float-end" data-bs-dismiss="offcanvas"
                            aria-label="Close"></button>
                        <div class="d-flex flex-column h-100 justify-content-center align-items-center">
                            <div class="search-voice">
                                <i class="ri-mic-fill align-middle"></i>
                                <span class="voice-wave"></span>
                                <span class="voice-wave"></span>
                                <span class="voice-wave"></span>
                            </div>
                            <h4>Talk to me, what can I do for you?</h4>
                        </div>
                    </div>
                </div>
            </div>
            <div>
                <ul class="nav nav-tabs nav-tabs-custom">
                    <li class="nav-item">
                        <a class="nav-link"
                            href="<?= home_url('app/search?s=' . $_GET['s']) ?>">
                            <i class="ri-search-2-line text-muted align-bottom me-1"></i> All Results
                        </a>
                    </li>
                    <li class="nav-item ms-auto">
                        <a class="nav-link"
                            href="<?= home_url('app/search') ?>">
                            <i class="ri-delete-bin-5-line text-muted align-bottom me-1"></i> Reset
                        </a>
                    </li>
                </ul>
            </div>
            <div class="card-body p-4">
                <div class="tab-content text-muted">
                    <div class="tab-pane active" id="all" role="tabpanel">
                        <?php foreach ($result['list'] as $item): ?>
                            <div class="pb-3">
                                <h5 class="mb-1"><a
                                        href="<?= home_url('app/' . $item['slug'] . '/detail?id=' . $item['id']) ?>"><?= $item['title'] ?></a>
                                </h5>
                                <p class="text-success mb-2 fs-12"><?= ucwords(str_replace('_', ' ', $item['table_name'])) ?></p>
                                <p class="text-muted mb-2">
                                    <?= generateShortDescription($item['content']) ?>
                                </p>
                                <ul class="list-inline d-flex align-items-center g-3 text-muted fs-14 mb-0">
                                    <li class="list-inline-item me-3"><i
                                            class="ri-thumb-up-line align-middle me-1"></i>10
                                    </li>
                                    <li class="list-inline-item me-3"><i
                                            class="ri-question-answer-line align-middle me-1"></i>8
                                    </li>
                                    <li class="list-inline-item">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0">
                                                <i class="ri-time-line"></i>
                                            </div>
                                            <div class="flex-grow-1 fs-13 ms-1">
                                                <?= $systemController->convertDateTime($item['updated_at']) ?>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>

                            <div class="border border-dashed mb-3"></div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <!--end tab-content-->
                <?php
                includeFileWithVariables('components/pagination.php', array("count" => $result['count']));
                ?>
            </div>
            <!--end card-body-->
        </div>
        <!--end card -->
    </div>
    <!--end card -->
</div>

<?php
$pageContent = ob_get_clean();
