<?php
$pageTitle = "Register";

require_once 'controllers/AuthenticationController.php';

$authenticationController = new AuthenticationController();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $authenticationController->registerUser();
}

ob_start();

echo '<div class="row">
        <div class="col-lg-5 d-none d-lg-block bg-register-image"></div>
        <div class="col-lg-7">
            <div class="p-5">
                <div class="text-center">
                    <h1 class="h4 text-gray-900 mb-4">Create an Account!</h1>
                </div>';

if (isset($_SESSION['message'])) {
    $messageType = $_SESSION['message_type'] ?? 'info';
    echo '<div class="alert alert-' . htmlspecialchars($messageType) . ' alert-dismissible fade show mb-4" role="alert">'
        . $_SESSION['message'] .
        '<button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>';

    unset($_SESSION['message']);

    if ($_SESSION['message_type'] === "success") {
        echo '<script type="text/javascript">
            setTimeout(function(){
                window.location = "' . home_url("login") . '";
            }, 1000);
        </script>';
    }

    unset($_SESSION['message_type']);
}

echo '<form class="user" method="POST" action="' . home_url("register") . '">
                    <div class="form-group row">
                        <div class="col-sm-6 mb-3 mb-sm-0">
                            <input type="text" class="form-control form-control-user" name="firstName"
                                placeholder="First Name">
                        </div>
                        <div class="col-sm-6">
                            <input type="text" class="form-control form-control-user" name="lastName"
                                placeholder="Last Name">
                        </div>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control form-control-user" name="username"
                            placeholder="Username">
                    </div>
                    <div class="form-group">
                        <input type="email" class="form-control form-control-user" name="email"
                            placeholder="Email Address">
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-6 mb-3 mb-sm-0">
                            <input type="password" class="form-control form-control-user" name="password" placeholder="Password">
                        </div>
                        <div class="col-sm-6">
                            <input type="password" class="form-control form-control-user" name="confirmPassword" placeholder="Repeat Password">
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary btn-user btn-block">
                        Register Account
                    </button>
                </form>
                <hr>
                <div class="text-center">
                    <a class="small" href="' . home_url("forgot-password") . '">Forgot Password?</a>
                </div>
                <div class="text-center">
                    <a class="small" href="' . home_url("login") . '">Already have an account? Login!</a>
                </div>
            </div>
        </div>
    </div>';

$pageContent = ob_get_clean();

include "layouts/layout-blank.php";
