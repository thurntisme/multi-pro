<?php
$pageTitle = "Website Quick Links";

$tab = $_GET['tab'] ?? DEFAULT_BOOKMARKS[0]['slug'];
$keyword = $_GET['s'] ?? '';

if (!empty($keyword)) {
    foreach ($bookmarks as $category) {
        $filteredLinks = array_filter($category['links'], function ($link) use ($keyword) {
            return stripos($link['name'], $keyword) !== false || stripos($link['url'], $keyword) !== false;
        });

        if (!empty($filteredLinks)) {
            $bookmark_arr[] = [
                "name" => $category["name"],
                "slug" => $category["slug"],
                "links" => array_values($filteredLinks) // Reindex the array
            ];
        }
    }
} else {
    $bookmark_arr = array_filter(DEFAULT_BOOKMARKS, function ($item) use ($tab) {
        return $item['slug'] == $tab;
    });
}
$bookmarkLinks = [];
if (count($bookmark_arr) > 0) {
    $bookmarkLinks = array_values($bookmark_arr)[0]['links'];
}

ob_start();
?>

<div class="auth-page-content overflow-hidden p-0">
    <div class="row mb-4">
        <div class="col-md-9 offset-md-3">
            <div class="d-flex justify-content-center gap-1">
                <a href="<?= home_url('app/website') ?>"
                    class="btn btn-soft-primary btn-label waves-effect waves-light w-auto"><i
                        class="ri-arrow-left-line label-icon align-middle fs-16 me-2"></i>Back to List</a>
                <form method="GET" action="<?= home_url('app/website/quick') ?>" class="w-50 d-flex gap-1">
                    <input type="text" class="form-control" name="s"
                        placeholder="Enter your keyword..." value="<?= $keyword ?>">
                    <button class="btn btn-info" type="submit"><i class="ri-search-line"></i>
                    </button>
                </form>
                <a class="btn btn-danger" id="btn-reset" href="<?= home_url('app/website/quick') ?>"><i class="ri-delete-bin-line"></i></a>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-3 col-sm-4">
            <div class="card">
                <div class="card-body">
                    <div class="nav flex-column nav-pills text-center">
                        <?php 
                        if (empty($keyword)) {
                        foreach (DEFAULT_BOOKMARKS as $item) { ?>
                            <a class="nav-link mb-2 <?= $item['slug'] == $tab ? 'active' : '' ?>"
                                href="<?= generatePageUrl(['tab' => $item['slug']]) ?>"><?= $item['name'] ?></a>
                        <?php } 
                        } else { ?>
                            <a class="nav-link mb-2 active"
                            href="#">All</a>
                        <?php }
                        ?>
                    </div>
                    <!--end row-->
                </div><!-- end card-body -->
            </div>
        </div><!-- end col -->
        <div class="col-lg-9 col-sm-8">
            <div class="row">
                <?php foreach ($bookmarkLinks as $bookmark) { ?>
                    <div class="col-lg-4 col-sm-6">
                        <div class="card card-body card-height-100 text-center">
                            <div class="avatar-sm mx-auto mb-3">
                                <div class="avatar-title text-success bg-transparent rounded">
                                    <img class="img-thumbnail rounded-circle avatar-xl w-100 h-100 object-fit-contain"
                                        alt="200x200" src="<?= $bookmark['logo'] ?>">
                                </div>
                            </div>
                            <h4 class="card-title"><?= $bookmark['name'] ?></h4>
                            <a href="<?= $bookmark['url'] ?>" target="_blank"
                                rel="noopener noreferrer" class="btn btn-success">Visit</a>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div><!--  end col -->
    </div>
</div>

<?php

$pageContent = ob_get_clean();
