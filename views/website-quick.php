<?php
$pageTitle = "Website Quick Links";

$tab = $_GET['tab'] ?? DEFAULT_BOOKMARKS[0]['slug'];

$bookmark_arr = array_filter(DEFAULT_BOOKMARKS, function ($item) use ($tab) {
    return $item['slug'] == $tab;
});
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
                    <input type="email" class="form-control w-50" id="search-bookmark"
                           placeholder="Enter your keyword...">
                    <button class="btn btn-primary" id="btn-reset">Reset <i class="ri-delete-bin-line"></i>
                    </button>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-3 col-sm-4">
                <div class="card">
                    <div class="card-body">
                        <div class="nav flex-column nav-pills text-center">
                            <?php foreach (DEFAULT_BOOKMARKS as $item) { ?>
                                <a class="nav-link mb-2 <?= $item['slug'] == $tab ? 'active' : '' ?>"
                                   href="<?= home_url('/app/website/quick?tab=' . $item['slug']) ?>"><?= $item['name'] ?></a>
                            <?php } ?>
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
