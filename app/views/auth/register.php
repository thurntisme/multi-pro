<?php
ob_start();
?>

<div class="row justify-content-center">
    <div class="col-md-8 col-lg-6 col-xl-5">
        <div class="card mt-4">
            <div class="card-body p-4">
                <div class="text-center mt-2">
                    <h5 class="text-primary">Create New Account</h5>
                    <p class="text-muted">Get your free Mercufee account now</p>
                </div>
                <div class="p-2 mt-4">
                    <form class="needs-validation" novalidate action="<?= App\Helpers\Network::home_url('register') ?>"
                        method="POST">

                        <div class="mb-3">
                            <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" name="email" id="email"
                                placeholder="Enter email address" value="" required>
                            <div class="invalid-feedback">
                                Please enter email
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="username" class="form-label">Username <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="username" id="username"
                                placeholder="Enter username" value="" required>
                            <div class="invalid-feedback">
                                Please enter username
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-6">
                                <div class="mb-3">
                                    <label for="first_name" class="form-label">First Name</label>
                                    <input type="text" class="form-control" name="first_name" id="first_name"
                                        placeholder="Enter first name" required>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mb-3">
                                    <label for="last_name" class="form-label">Last Name</label>
                                    <input type="text" class="form-control" name="last_name" id="last_name"
                                        placeholder="Enter last name" required>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="password-input">Password</label>
                            <div class="position-relative auth-pass-inputgroup">
                                <input type="password" class="form-control pe-5 password-input" name="password"
                                    onpaste="return false" placeholder="Enter password" value="" id="password-input"
                                    aria-describedby="passwordInput" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}"
                                    required>
                                <button
                                    class="btn btn-link position-absolute end-0 top-0 text-decoration-none text-muted password-addon"
                                    type="button" id="password-addon"><i class="ri-eye-fill align-middle"></i></button>
                                <div class="invalid-feedback">
                                    Please enter password
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="password-input">Confirm Password</label>
                            <div class="position-relative auth-pass-inputgroup">
                                <input type="password" class="form-control pe-5 password-input" name="confirmPassword"
                                    onpaste="return false" placeholder="Enter Confirm password" value=""
                                    id="confirm-password-input" required>
                                <div class="invalid-feedback">
                                    Please enter confirm password
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <p class="mb-0 fs-12 text-muted fst-italic">By registering you agree to the
                                <?= APP_NAME ?> <a href="#"
                                    class="text-primary text-decoration-underline fst-normal fw-medium">Terms
                                    of Use</a>
                            </p>
                        </div>

                        <div id="password-contain" class="p-3 bg-light mb-2 rounded">
                            <h5 class="fs-13">Password must contain:</h5>
                            <p id="pass-length" class="invalid fs-12 mb-2">Minimum <b>8 characters</b></p>
                            <p id="pass-lower" class="invalid fs-12 mb-2">At <b>lowercase</b> letter (a-z)</p>
                            <p id="pass-upper" class="invalid fs-12 mb-2">At least <b>uppercase</b> letter (A-Z)</p>
                            <p id="pass-number" class="invalid fs-12 mb-0">A least <b>number</b> (0-9)</p>
                        </div>

                        <div class="mt-4">
                            <button class="btn btn-success w-100" type="submit">Sign Up</button>
                        </div>

                        <div class="mt-4 text-center">
                            <div class="signin-other-title">
                                <h5 class="fs-13 mb-4 title text-muted">Create account with</h5>
                            </div>

                            <div>
                                <button type="button" class="btn btn-primary btn-icon waves-effect waves-light"><i
                                        class="ri-facebook-fill fs-16"></i></button>
                                <button type="button" class="btn btn-danger btn-icon waves-effect waves-light"><i
                                        class="ri-google-fill fs-16"></i></button>
                                <button type="button" class="btn btn-dark btn-icon waves-effect waves-light"><i
                                        class="ri-github-fill fs-16"></i></button>
                                <button type="button" class="btn btn-info btn-icon waves-effect waves-light"><i
                                        class="ri-twitter-fill fs-16"></i></button>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
            <!-- end card body -->
        </div>
        <!-- end card -->

        <div class="mt-4 text-center">
            <p class="mb-0">Already have an account ? <a href="<?= App\Helpers\Network::home_url('login') ?>"
                    class="fw-semibold text-primary text-decoration-underline"> Login </a> </p>
        </div>

    </div>
</div>

<?php
$pageContent = ob_get_clean();

ob_start(); ?>
<script src="<?= App\Helpers\Network::home_url('assets/js/pages/password-create.init.js') ?>">
</script>

<?php
$additionJs = ob_get_clean();

include LAYOUTS_DIR . '/auth.php';