<?php
$pageTitle = "User Block";

ob_start();
?>

<div class="row justify-content-center">
    <div class="col-md-8 col-lg-6 col-xl-5">
        <div class="card mt-4">

            <div class="card-body p-4">
                <div class="user-thumb text-center">
                    <img src="<?= App\Helpers\Network::home_url('assets/images/users/avatar-1.jpg') ?>"
                        class="rounded-circle img-thumbnail avatar-lg" alt="thumbnail">
                    <h5 class="font-size-15 mt-3"><?= $curr_data['full_name'] ?></h5>
                    <div class="text-center mt-4">
                        <h5 class="text-danger mb-3">Account Blocked</h5>
                        <p class="text-muted mb-4">Your account has been blocked. Please contact the administrator for
                            further assistance.</p>
                        <button class="btn btn-primary w-100">Contact Admin</button>
                    </div>
                </div>
            </div>
            <!-- end card body -->
        </div>
        <!-- end card -->

        <div class="mt-4 text-center">
            <p class="mb-0">Back to <a href="<?= App\Helpers\Network::home_url('') ?>"
                    class="fw-semibold text-primary text-decoration-underline"> Mercufee </a> </p>
        </div>

    </div>
</div>

<?php
$pageContent = ob_get_clean();
