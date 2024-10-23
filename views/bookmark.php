<?php
$pageTitle = "Bookmark";

$bookmark_arr = array();
foreach (DEFAULT_BOOKMARKS as $key => $bookmarks) {
    if ($key !== "Featured") {
        foreach ($bookmarks as $bookmark) {
            $bookmark_arr[] = array(
                'key' => $key,
                'logo' => $bookmark['logo'],
                'name' => $bookmark['name'],
                'tags' => $bookmark['tags'],
                'url' => $bookmark['url'],
            );
        }
    }
}

ob_start();
?>

<div class="auth-page-content overflow-hidden p-0">
    <div class="card">
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-10 offset-2">
                    <div class="row">
                        <div class="col-4 offset-3">
                            <input type="email" class="form-control" id="search-bookmark" placeholder="Enter your keyword...">
                        </div>
                        <div class="col ps-0">
                            <button class="btn btn-primary" id="btn-reset">Reset <i class="ri-delete-bin-line"></i></button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row" id="bookmark-content">
                <div class="col-md-2">
                    <div class="nav flex-column nav-pills text-center" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                        <?php foreach (DEFAULT_BOOKMARKS as $key => $item) { ?>
                            <a class="nav-link mb-2 <?= strtolower(str_replace(' ', '-', $key)) == 'featured' ? 'active' : '' ?>" id="v-pills-<?= strtolower(str_replace(' ', '-', $key)) ?>-tab" data-bs-toggle="pill" href="#v-<?= strtolower(str_replace(' ', '-', $key)) ?>" role="tab" aria-controls="v-<?= strtolower(str_replace(' ', '-', $key)) ?>" aria-selected="true"><?= $key ?></a>
                        <?php } ?>
                    </div>
                </div><!-- end col -->
                <div class="col-md-10">
                    <div class="tab-content text-muted mt-4 mt-md-0" id="v-pills-tabContent">
                        <?php foreach (DEFAULT_BOOKMARKS as $key => $item) { ?>
                            <div class="tab-pane fade <?= strtolower(str_replace(' ', '-', $key)) == 'featured' ? 'active show' : '' ?>" id="v-<?= strtolower(str_replace(' ', '-', $key)) ?>" role="tabpanel" aria-labelledby="v-<?= strtolower(str_replace(' ', '-', $key)) ?>">
                                <div class="row">
                                    <?php foreach ($item as $bookmark) { ?>
                                        <div class="col-xl-4" data-key="<?= $key ?>">
                                            <div class="card card-body text-center">
                                                <div class="avatar-sm mx-auto mb-3">
                                                    <div class="avatar-title text-success bg-transparent rounded">
                                                        <img class="img-thumbnail rounded-circle avatar-xl w-100 h-100 object-fit-contain" alt="200x200" src="<?= $bookmark['logo'] ?>">
                                                    </div>
                                                </div>
                                                <h4 class="card-title"><?= $bookmark['name'] ?></h4>
                                                <p class="card-text text-muted"><?= implode(", ", $bookmark['tags']) ?></p>
                                                <a href="<?= $bookmark['url'] ?>" target="_blank" rel="noopener noreferrer" class="btn btn-success">Visit</a>
                                            </div>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div><!--  end col -->
            </div>
            <div class="row d-none" id="search-results"></div>
            <!--end row-->
        </div><!-- end card-body -->
    </div>
</div>

<?php

ob_start();
echo "
<script>
    const bookmarkList = '" . json_encode($bookmark_arr) . "';
</script>
<script src='" . home_url("/assets/js/pages/bookmark.js") . "'></script>
";
$additionJs = ob_get_clean();

$pageContent = ob_get_clean();

include 'layout.php';
