<?php
global $status;
require_once 'controllers/BookController.php';

$pageTitle = "Books";

$bookController = new BookController();
$list = $bookController->listBooks();

ob_start();
?>

<?php
include_once DIR . '/components/alert.php';
?>

<div class="card" id="tasksList">
    <div class="card-header border-0">
        <div class="d-flex align-items-center">
            <h5 class="card-title mb-0 flex-grow-1">All Books</h5>
            <div class="flex-shrink-0">
                <div class="d-flex flex-wrap gap-2">
                    <a class="btn btn-soft-success add-btn"
                        href="<?= App\Helpers\NetworkHelper::home_url('app/book/new') ?>"><i
                            class="ri-add-line align-bottom me-1"></i> Create Book</a>
                </div>
            </div>
        </div>
    </div>
    <div class="card-body border border-dashed border-end-0 border-start-0">
        <form method="get" action="<?= App\Helpers\NetworkHelper::home_url('app/book') ?>">
            <div class="row g-3">
                <div class="col-xxl-5 col-sm-12">
                    <div class="search-box">
                        <input type="text" name="s" class="form-control search bg-light border-light"
                            placeholder="Search for books or something..." value="<?= $_GET['s'] ?? '' ?>">
                        <i class="ri-search-line search-icon"></i>
                    </div>
                </div>
                <!--end col-->

                <div class="col-xxl-3 col-sm-4">
                    <div class="input-light">
                        <select class="form-control" data-choices data-choices-search-false data-choices-sorting-false
                            name="status">
                            <?php
                            echo '<option value="" ' . (!empty($_GET['status']) ? 'selected' : "") . '>Select Status</option>';
                            foreach ($status as $value => $label) {
                                $selected = (!empty($_GET['status']) && $value === $_GET['status']) ? 'selected' : '';
                                echo "<option value=\"$value\" $selected>$label</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <!--end col-->

                <!--end col-->
                <div class="col-xxl-2 col-sm-4 d-flex">
                    <button type="submit" class="btn btn-primary w-100"><i
                            class="ri-equalizer-fill me-1 align-bottom"></i>
                        Filters
                    </button>
                    <a href="<?= App\Helpers\NetworkHelper::home_url("app/book") ?>" class="btn btn-danger ms-1"><i
                            class="ri-delete-bin-2-fill me-1 align-bottom"></i>Reset</a>
                </div>
                <!--end col-->
            </div>
            <!--end row-->
        </form>
    </div>
    <!--end card-body-->
    <div class="card-body">
        <div class="table-responsive table-card mb-4">
            <table class="table align-middle table-nowrap mb-0" id="booksTable">
                <thead class="table-light text-muted">
                    <tr>
                        <th>Title</th>
                        <th class="text-center">Tags</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">View Page</th>
                        <th class="sort text-end pe-5">Created Date</th>
                        <th class="sort text-end pe-5">Updated Date</th>
                    </tr>
                </thead>
                <tbody class="list form-check-all">
                    <?php if (count($list['list']) > 0) {
                        foreach ($list['list'] as $item) { ?>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-baseline">
                                        <a class="text-black"
                                            href="<?= App\Helpers\NetworkHelper::home_url('app/book/detail?id=' . $item['id']) ?>"><?= truncateString($item['title'], 50) ?></a>
                                        <ul class="list-inline tasks-list-menu mb-0 ms-4">
                                            <li class="list-inline-item m-0"><a class="edit-item-btn btn btn-link btn-sm"
                                                    href="<?= App\Helpers\NetworkHelper::home_url('app/book/view?id=' . $item['id']) ?>"><i
                                                        class="ri-book-read-fill align-bottom text-muted"></i></a>
                                            </li>
                                            <li class="list-inline-item m-0"><a class="edit-item-btn btn btn-link btn-sm"
                                                    href="<?= App\Helpers\NetworkHelper::home_url('app/book/detail?id=' . $item['id']) ?>"><i
                                                        class="ri-eye-fill align-bottom text-muted"></i></a>
                                            </li>
                                            <li class="list-inline-item m-0"><a class="edit-item-btn btn btn-link btn-sm"
                                                    href="<?= App\Helpers\NetworkHelper::home_url('app/book/edit?id=' . $item['id']) ?>"><i
                                                        class="ri-pencil-fill align-bottom text-muted"></i></a>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                                <td class="text-center"><?= $item['tags'] ?></td>
                                <td class="text-center"><?= renderStatusBadge($item['status']) ?></td>
                                <td class="text-center"><?= $item['view_page'] ?></td>
                                <td class="text-end pe-5"><?= $systemController->convertDate($item['created_at']) ?></td>
                                <td class="text-end pe-5"><?= $systemController->convertDate($item['updated_at']) ?></td>
                            </tr>
                        <?php }
                    } ?>
                </tbody>
            </table>
        </div>
        <?php
        includeFileWithVariables('components/pagination.php', array("count" => $list['count']));
        ?>
    </div>
    <!--end card-body-->
</div>

<?php
$pageContent = ob_get_clean();
