<?php
global $priorities, $status;
require_once 'controllers/WebsiteController.php';

$pageTitle = "Websites";

$websiteController = new WebsiteController();
$list = $websiteController->listWebsites();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action_name'])) {
        if ($_POST['action_name'] === 'delete_record' && isset($_POST['post_id'])) {
            $websiteController->deleteWebsite();
        }
    }
}

ob_start();
?>

<?php
include_once DIR . '/components/alert.php';
?>

<div class="card" id="tasksList">
    <div class="card-header border-0">
        <div class="d-flex align-items-center">
            <h5 class="card-title mb-0 flex-grow-1">All Websites</h5>
            <div class="flex-shrink-0">
                <div class="d-flex flex-wrap gap-2">
                    <a href="<?= App\Helpers\NetworkHelper::home_url('app/website/quick') ?>"
                        class="btn btn-soft-primary"><i class="ri-find-replace-fill align-bottom me-1"></i>Quick
                        Links</a>
                    <a class="btn btn-soft-success add-btn"
                        href="<?= App\Helpers\NetworkHelper::home_url('app/website/new') ?>"><i
                            class="ri-add-line align-bottom me-1"></i> Create Website</a>
                </div>
            </div>
        </div>
    </div>
    <div class="card-body border border-dashed border-end-0 border-start-0">
        <form method="get" action="<?= App\Helpers\NetworkHelper::home_url('app/website') ?>">
            <div class="row g-3">
                <div class="col-xxl-4 col-sm-12">
                    <div class="search-box">
                        <input type="text" name="s" class="form-control search bg-light border-light"
                            placeholder="Search for websites or something..." value="<?= $_GET['s'] ?? '' ?>">
                        <i class="ri-search-line search-icon"></i>
                    </div>
                </div>
                <!--end col-->
                <div class="col-xxl-3 col-sm-4 d-flex">
                    <button type="submit" class="btn btn-primary"><i class="ri-equalizer-fill me-1 align-bottom"></i>
                        Filters
                    </button>
                    <a href="<?= App\Helpers\NetworkHelper::home_url("app/website") ?>" class="btn btn-danger ms-1"><i
                            class="ri-delete-bin-2-fill me-1 align-bottom"></i>Reset</a>
                </div>
                <div class="col d-flex justify-content-center">
                    <div class="button-group">
                        <?php
                        $tags = ['search', 'ai chat', 'social'];
                        foreach ($tags as $tag) {
                            $active = isset($_GET['tag']) && $tag === $_GET['tag'] ? 'active' : '';
                            echo '<a href="' . home_url('/app/website?tag=') . $tag . '" class="btn btn-outline-info mx-1 ' . $active . '">' . $tag . '</a>';
                        }
                        ?>
                    </div>
                </div>
                <!--end col-->
            </div>
            <!--end row-->
        </form>
    </div>
    <!--end card-body-->
    <div class="card-body">
        <div class="table-responsive table-card mb-4">
            <table class="table align-middle table-nowrap mb-0" id="websitesTable">
                <thead class="table-light text-muted">
                    <tr>
                        <th>Title</th>
                        <th>Url</th>
                        <th>Content</th>
                        <th class="text-center">Tags</th>
                    </tr>
                </thead>
                <tbody class="list form-check-all">
                    <?php if (count($list['list']) > 0) {
                        foreach ($list['list'] as $item) { ?>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-baseline">
                                        <a class="text-black"
                                            href="<?= App\Helpers\NetworkHelper::home_url('app/website/detail?id=' . $item['id']) ?>"><?= $item['title'] ?></a>
                                        <ul class="list-inline tasks-list-menu mb-0 ms-3">
                                            <li class="list-inline-item m-0"><a class="edit-item-btn btn btn-link btn-sm"
                                                    href="<?= App\Helpers\NetworkHelper::home_url('app/website/detail?id=' . $item['id']) ?>"><i
                                                        class="ri-eye-fill align-bottom text-muted"></i></a>
                                            </li>
                                            <li class="list-inline-item m-0"><a class="edit-item-btn btn btn-link btn-sm"
                                                    href="<?= App\Helpers\NetworkHelper::home_url('app/website/edit?id=' . $item['id']) ?>"><i
                                                        class="ri-pencil-fill align-bottom text-muted"></i></a>
                                            </li>
                                            <li class="list-inline-item m-0">
                                                <form method="POST" action="<?= $_SERVER['REQUEST_URI'] ?>">
                                                    <input type="hidden" name="action_name" value="delete_record">
                                                    <input type="hidden" name="post_id" value="<?= $item['id'] ?>">
                                                    <button type="submit" class="btn btn-link btn-sm btn-delete-record">
                                                        <i class="ri-delete-bin-5-line align-bottom text-muted"></i>
                                                    </button>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                                <td><a href="<?= $item['url'] ?>" target="_blank"
                                        rel="noopener noreferrer"><?= $item['url'] ?></a></td>
                                <td><?= truncateString($item['content'], 100) ?></td>
                                <td class="text-center"><?= $item['tags'] ?></td>
                            </tr>
                        <?php }
                    } ?>
                </tbody>
            </table>
            <!--end table-->
        </div>
        <?php
        includeFileWithVariables('components/pagination.php', array("count" => $list['count']));
        ?>
    </div>
    <!--end card-body-->
</div>


<?php
$pageContent = ob_get_clean();
