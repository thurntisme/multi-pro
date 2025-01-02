<?php
global $priorities, $status;
require_once 'controllers/UserController.php';

$pageTitle = "User Management";

$userController = new UserController();
$list = $userController->listUsers();

ob_start();
?>

<?php
include_once DIR . '/components/alert.php';
?>

<div class="card" id="tasksList">
    <div class="card-header border-0">
        <div class="d-flex align-items-center">
            <h5 class="card-title mb-0 flex-grow-1">All Users</h5>
        </div>
    </div>
    <div class="card-body border border-dashed border-end-0 border-start-0">
        <form method="get" action="<?= home_url('app/user-management') ?>">
            <div class="row g-3">
                <div class="col-xxl-4 col-sm-12">
                    <div class="search-box">
                        <input type="text" name="s" class="form-control search bg-light border-light"
                            placeholder="Search for user or something..." value="<?= $_GET['s'] ?? '' ?>">
                        <i class="ri-search-line search-icon"></i>
                    </div>
                </div>
                <!--end col-->
                <div class="col-xxl-2 col-sm-4">
                    <input type="text" class="form-control bg-light border-light" name="joined_date"
                        data-provider="flatpickr" data-date-format="Y-m-d" data-range-date="true"
                        placeholder="Select date range" value="<?= $_GET['joined_date'] ?? '' ?>">
                </div>
                <div class="col-xxl-2 col-sm-4">
                    <div class="input-light">
                        <select class="form-control" data-choices data-choices-search-false
                            name="user_role">
                            <?php
                            echo '<option value="" ' . (!empty($_GET['user_role']) ? 'selected' : "") . '>Select Role</option>';
                            foreach ($user_roles as $value => $label) {
                                $selected = (!empty($_GET['user_role']) && $value === $_GET['user_role']) ? 'selected' : '';
                                echo "<option value=\"$value\" $selected>$label</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <!--end col-->
                <div class="col-xxl-2 col-sm-4 d-flex">
                    <button type="submit" class="btn btn-primary"><i
                            class="ri-equalizer-fill me-1 align-bottom"></i>
                        Filters
                    </button>
                    <a href="<?= home_url("app/user-management") ?>" class="btn btn-danger ms-1"><i
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
            <table class="table align-middle table-nowrap mb-0" id="todosTable">
                <thead class="table-light text-muted">
                    <tr>
                        <th>Username</th>
                        <th>Full Name</th>
                        <th>Email</th>
                        <th class="text-center">Role</th>
                        <th class="text-center">Email Verified</th>
                        <th class="text-center">Status</th>
                        <th class="text-end">Joined Date</th>
                        <th class="text-end">Last Login At</th>
                        <th class="text-end">Updated At</th>
                    </tr>
                </thead>
                <tbody class="list form-check-all">
                    <?php if (count($list['list']) > 0) {
                        foreach ($list['list'] as $item) { ?>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-baseline">
                                        <a class="text-black position-relative pe-3"
                                            href="<?= home_url('app/user-management/detail?id=' . $item['id']) ?>"><?= $item['username'] ?>
                                            <?php if ($systemController->checkUserOnline($item['last_time_login'])) { ?>
                                                <span class="user-online ms-1 position-absolute"></span><?php } ?>
                                        </a>
                                        <ul class="list-inline tasks-list-menu mb-0 ms-3">
                                            <li class="list-inline-item m-0"><a
                                                    class="edit-item-btn btn btn-link btn-sm"
                                                    href="<?= home_url('app/user-management/detail?id=' . $item['id']) ?>"><i
                                                        class="ri-eye-fill align-bottom text-muted"></i></a>
                                            </li>
                                            <li class="list-inline-item m-0"><a
                                                    class="edit-item-btn btn btn-link btn-sm"
                                                    href="<?= home_url('app/user-management/edit?id=' . $item['id']) ?>"><i
                                                        class="ri-pencil-fill align-bottom text-muted"></i></a>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                                <td><?= $item['first_name'] . ' ' . $item['last_name'] ?></td>
                                <td><?= $item['email'] ?></td>
                                <td class="text-center"><?= $user_roles[$item['role']] ?></td>
                                <td class="text-center">
                                    <span class='badge bg-<?= $item['isEmailVerify'] === 0 ? 'light text-dark' : 'primary' ?>'>
                                        <?= $item['isEmailVerify'] === 0 ? 'Waiting' : 'Verified' ?>
                                    </span>
                                </td>
                                <td class="text-center">
                                    <span class='badge bg-<?= $item['isActive'] === 0 ? 'danger' : 'success' ?>'>
                                        <?= $item['isActive'] === 0 ? 'Deactivate' : 'Active' ?>
                                    </span>
                                </td>
                                <td class="text-end"><?= $systemController->convertDateTime($item['created_at']) ?></td>
                                <td class="text-end"><?= $systemController->convertDateTime($item['last_login']) ?></td>
                                <td class="text-end"><?= $systemController->convertDateTime($item['updated_at']) ?></td>
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
