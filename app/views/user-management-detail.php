<?php
$pageTitle = "User Detail";

require_once 'controllers/UserController.php';
$userController = new UserController();

$modify_type = getLastSegmentFromUrl();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['id'])) {
        $post_id = $_GET['id'];
        $postData = $userController->viewUser($post_id);
        $tags = !empty($postData['tags']) ? explode(',', $postData['tags']) : [];
    }
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action_name'])) {
        if ($_POST['action_name'] === 'change_user_status') {
            $userController->changeUserStatus($_GET['id']);
        }
    }
}

ob_start();
?>
<div class="row">
    <div class="col-xl-8 col-md-10 offset-xl-2 offset-md-1">
        <?php
        ob_start();
        ?>
        <form method="POST" action="<?= $_SERVER['REQUEST_URI'] ?>" class="me-3">
            <input type="hidden" name="action_name" value="change_user_status">
            <input type="hidden" name="post_id" value="<?= $post_id ?>">
            <button type="submit" class="btn btn-<?= $postData['isActive'] === 0 ? 'success' : 'danger' ?> w-sm"><i
                    class="ri-<?= $postData['isActive'] === 0 ? 'user-follow' : 'user-unfollow' ?>-line align-bottom me-1"></i>
                <?= $postData['isActive'] === 0 ? 'Active' : 'Inactivate' ?>
            </button>
        </form>
        <?php
        $additionBtn = ob_get_clean();
        includeFileWithVariables('components/single-button-group.php', array("slug" => "user-management", "post_id" => $postData['id'], 'modify_type' => $modify_type, 'additionBtn' => $additionBtn));
        ?>
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">User Detail</h4>
            </div>
            <div class="card-body text-center">
                <div class="position-relative d-inline-block">
                    <img src="<?= App\Helpers\NetworkHelper::home_url('assets/images/users/avatar-1.jpg') ?>" alt=""
                        class="avatar-lg rounded-circle img-thumbnail">
                    <span class="contact-active position-absolute rounded-circle bg-success"><span
                            class="visually-hidden"></span>
                </div>
                <h5 class="mt-4"><?= $postData['username'] ?></h5>

                <ul class="list-inline mb-0">
                    <li class="list-inline-item avatar-xs">
                        <a href="javascript:void(0);" class="avatar-title bg-success-subtle text-success fs-15 rounded">
                            <i class="ri-phone-line"></i>
                        </a>
                    </li>
                    <li class="list-inline-item avatar-xs">
                        <a href="javascript:void(0);" class="avatar-title bg-danger-subtle text-danger fs-15 rounded">
                            <i class="ri-mail-line"></i>
                        </a>
                    </li>
                    <li class="list-inline-item avatar-xs">
                        <a href="javascript:void(0);" class="avatar-title bg-warning-subtle text-warning fs-15 rounded">
                            <i class="ri-question-answer-line"></i>
                        </a>
                    </li>
                </ul>
            </div>
            <div class="card-body">
                <h6 class="text-muted text-uppercase fw-semibold mb-3">Personal Information</h6>
                <p class="text-muted mb-4">Hello, I'm Tonya Noble, The most effective objective is one that is tailored
                    to the job you are applying for. It states what kind of career you are seeking, and what skills and
                    experiences.</p>
                <div class="table-responsive table-card">
                    <table class="table table-borderless mb-0">
                        <tbody>
                            <tr>
                                <td class="fw-medium" scope="row">First Name</td>
                                <td><?= $postData['first_name'] ?></td>
                            </tr>
                            <tr>
                                <td class="fw-medium" scope="row">Last Name</td>
                                <td><?= $postData['last_name'] ?></td>
                            </tr>
                            <tr>
                                <td class="fw-medium" scope="row">Email</td>
                                <td><?= $postData['email'] ?></td>
                            </tr>
                            <tr>
                                <td class="fw-medium" scope="row">Role</td>
                                <td><?= $user_roles[$postData['role']] ?></td>
                            </tr>
                            <tr>
                                <td class="fw-medium" scope="row">Email Verified</td>
                                <td>
                                    <span
                                        class='badge bg-<?= $postData['isEmailVerify'] === 0 ? 'light text-dark' : 'primary' ?>'>
                                        <?= $postData['isEmailVerify'] === 0 ? 'Waiting' : 'Verified' ?>
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td class="fw-medium" scope="row">Status</td>
                                <td>
                                    <span class='badge bg-<?= $postData['isActive'] === 0 ? 'danger' : 'success' ?>'>
                                        <?= $postData['isActive'] === 0 ? 'Inactivate' : 'Active' ?>
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td class="fw-medium" scope="row">Joined Date</td>
                                <td><?= $systemController->convertDateTime($postData['created_at']) ?></td>
                            </tr>
                            <tr>
                                <td class="fw-medium" scope="row">Last Login</td>
                                <td><?= $systemController->convertDateTime($postData['last_login']) ?></td>
                            </tr>
                            <tr>
                                <td class="fw-medium" scope="row">Updated Date</td>
                                <td><?= $systemController->convertDateTime($postData['updated_at']) ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- end card body -->
        </div>
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">Devices</h4>
            </div>
            <div class="card-body">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Device Name</th>
                            <th scope="col">Device Type</th>
                            <th scope="col" class="text-center">IP Address</th>
                            <th scope="col" class="text-end">Last time login</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($postData['devices'] as $index => $device) { ?>
                            <tr>
                                <th scope="row"><?= $index + 1 ?></th>
                                <td><?= $device['device_name'] ?></td>
                                <td><?= $device['device_type'] ?></td>
                                <td class="text-center"><?= $device['ip_address'] ?></td>
                                <td class="text-end"><?= $systemController->convertDateTime($device['last_time_login']) ?>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php
$pageContent = ob_get_clean();
