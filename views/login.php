<?php
$pageTitle = "Login";

require_once 'controllers/AuthenticationController.php';

$authenticationController = new AuthenticationController();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $authenticationController->login();
}

ob_start();

$alert = '';
$email_err = '';
$password_err = '';

if (isset($_SESSION['message'])) {
    if ($_SESSION['message_type'] === 'validate') {
        $email_err = $_SESSION['message']['email'] ?? "";
        $password_err = $_SESSION['message']['password'] ?? "";
    }
    if ($_SESSION['message_type'] === 'success' || $_SESSION['message_type'] === 'danger') {
        $alert = '<div class="alert alert-' . $_SESSION['message_type'] . ' alert-top-border alert-dismissible fade show" role="alert">
                <i class="ri-' . ($_SESSION['message_type'] === "success" ? "check-double" : "error-warning") . '-line me-3 align-middle fs-16 text-' . $_SESSION['message_type'] . '"></i><strong>' . $_SESSION['message'] . '</strong>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>';
    }

    unset($_SESSION['message']);
    unset($_SESSION['message_type']);
}
if (isset($_SESSION['token'])) {
    echo '<script type="text/javascript">
        localStorage.setItem("authToken", "' . $_SESSION['token'] . '");
        setTimeout(function(){
            window.location.reload();
        }, 1000);
    </script>';
}

echo '<div class="row justify-content-center">
        <div class="col-md-8 col-lg-6 col-xl-5">
            ' . $alert . '
            <div class="card mt-4">
                <div class="card-body p-4">
                    <div class="text-center mt-2">
                        <h5 class="text-primary">Welcome Back !</h5>
                        <p class="text-muted">Sign in to continue to ' . $commonController->getSiteName() . '.</p>
                    </div>
                    <div class="p-2 mt-4">
                        <form action="' . home_url("login") . '" method="POST">

                            <div class="mb-3 ' . ((!empty($email_err)) ? 'has-error' : '') . '">
                                <label for="email" class="form-label">Email</label>
                                <input type="text" class="form-control" name="email" id="email" placeholder="Enter email">
                                <span class="text-danger">' . $email_err . '</span>
                            </div>

                            <div class="mb-3 ' . ((!empty($password_err)) ? 'has-error' : '') . '">
                                <div class="float-end">
                                    <a href="auth-pass-reset-basic.php" class="text-muted">Forgot password?</a>
                                </div>
                                <label class="form-label" for="password-input">Password</label>
                                <div class="position-relative auth-pass-inputgroup mb-3">
                                    <input type="password" class="form-control pe-5 password-input" placeholder="Enter password" id="password-input" name="password">
                                    <span class="text-danger">' . $password_err . '</span>
                                    <button class="btn btn-link position-absolute end-0 top-0 text-decoration-none text-muted password-addon" type="button" id="password-addon"><i class="ri-eye-fill align-middle"></i></button>
                                </div>
                            </div>

                            <!--<div class="form-check">
                                <input class="form-check-input" type="checkbox" value="" id="auth-remember-check">
                                <label class="form-check-label" for="auth-remember-check">Remember me</label>
                            </div>-->

                            <div class="mt-4">
                                <button class="btn btn-success w-100" type="submit">Sign In</button>
                            </div>

                            <div class="mt-4 text-center">
                                <div class="signin-other-title">
                                    <h5 class="fs-13 mb-4 title">Sign In with</h5>
                                </div>
                                <div>
                                    <button type="button" class="btn btn-primary btn-icon waves-effect waves-light"><i class="ri-facebook-fill fs-16"></i></button>
                                    <button type="button" class="btn btn-danger btn-icon waves-effect waves-light"><i class="ri-google-fill fs-16"></i></button>
                                    <button type="button" class="btn btn-dark btn-icon waves-effect waves-light"><i class="ri-github-fill fs-16"></i></button>
                                    <button type="button" class="btn btn-info btn-icon waves-effect waves-light"><i class="ri-twitter-fill fs-16"></i></button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- end card body -->
            </div>
            <!-- end card -->

            <div class="mt-4 text-center">
                <p class="mb-0">Do not have an account ? <a href="' . home_url("register") . '" class="fw-semibold text-primary text-decoration-underline"> Signup </a> </p>
            </div>

        </div>
    </div>';

$pageContent = ob_get_clean();

include "auth-layout.php";
