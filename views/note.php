<?php
require_once 'controllers/NoteController.php';

$pageTitle = "Note";

$noteController = new NoteController();
$list = $noteController->listNotes();

ob_start();
?>

<?php
include_once DIR . '/components/alert.php';
?>

<div class="card" id="tasksList">
    <div class="card-header border-0">
        <div class="d-flex align-items-center">
            <h5 class="card-title mb-0 flex-grow-1">All Notes</h5>
            <div class="flex-shrink-0">
                <div class="d-flex flex-wrap gap-2">
                    <a class="btn btn-danger add-btn" href="<?= home_url('app/note/new') ?>"><i
                            class="ri-add-line align-bottom me-1"></i> Create Note</a>
                </div>
            </div>
        </div>
    </div>
    <div class="card-body border border-dashed border-end-0 border-start-0">
        <form method="get" action="<?= home_url('app/note') ?>">
            <div class="row g-3">
                <div class="col-xxl-5 col-sm-12">
                    <div class="search-box">
                        <input type="text" name="s" class="form-control search bg-light border-light"
                            placeholder="Search for notes or something...">
                        <i class="ri-search-line search-icon"></i>
                    </div>
                </div>
                <!--end col-->
                <div class="col-xxl-2 col-sm-4 d-flex">
                    <button type="submit" class="btn btn-primary"><i
                            class="ri-equalizer-fill me-1 align-bottom"></i>
                        Filters
                    </button>
                    <a href="<?= home_url("app/note") ?>" class="btn btn-danger ms-1"><i
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
            <table class="table align-middle table-nowrap mb-0" id="notesTable">
                <thead class="table-light text-muted">
                    <tr>
                        <th>Title</th>
                        <th>Tags</th>
                        <th class="sort text-end pe-5">Created At</th>
                        <th class="sort text-end pe-5">Updated At</th>
                    </tr>
                </thead>
                <tbody class="list form-check-all">
                    <?php if (count($list['list']) > 0) {
                        foreach ($list['list'] as $item) { ?>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-baseline">
                                        <a class="text-black"
                                            href="<?= home_url('app/note/detail?id=' . $item['id']) ?>"><?= $item['title'] ?></a>
                                        <ul class="list-inline tasks-list-menu mb-0 ms-3">
                                            <li class="list-inline-item m-0"><a
                                                    class="edit-item-btn btn btn-link btn-sm"
                                                    href="<?= home_url('app/note/detail?id=' . $item['id']) ?>"><i
                                                        class="ri-eye-fill align-bottom text-muted"></i></a>
                                            </li>
                                            <li class="list-inline-item m-0"><a
                                                    class="edit-item-btn btn btn-link btn-sm"
                                                    href="<?= home_url('app/note/edit?id=' . $item['id']) ?>"><i
                                                        class="ri-pencil-fill align-bottom text-muted"></i></a>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                                <td><?= $item['tags'] ?></td>
                                <td class="text-end pe-5"><?= $systemController->convertDateTime($item['created_at']) ?></td>
                                <td class="text-end pe-5"><?= $systemController->convertDateTime($item['updated_at']) ?></td>
                            </tr>
                    <?php }
                    } ?>
                </tbody>
            </table>
            <!--end table-->
            <div class="noresult" style="display: none">
                <div class="text-center">
                    <lord-icon src="https://cdn.lordicon.com/msoeawqm.json" trigger="loop"
                        colors="primary:#121331,secondary:#08a88a"
                        style="width:75px;height:75px"></lord-icon>
                    <h5 class="mt-2">Sorry! No Result Found</h5>
                    <p class="text-muted mb-0">Weve searched more than 200k+ notes We did not find any
                        notes for you search.</p>
                </div>
            </div>
        </div>
        <?php
        includeFileWithVariables('components/pagination.php', array("count" => $list['count']));
        ?>
    </div>
    <!--end card-body-->
</div>

<?php
$pageContent = ob_get_clean();
