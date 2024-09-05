<?php
$pageTitle = "Login";

require_once 'controllers/AuthenticationController.php';

$authenticationController = new AuthenticationController();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $authenticationController->login();
}

ob_start();

echo '<div class="row">
        <div class="col-lg-6 d-none d-lg-block bg-login-image"></div>
        <div class="col-lg-6">
            <div class="p-5">
                <div class="text-center">
                    <h1 class="h4 text-gray-900 mb-4">Welcome Back!</h1>
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

echo '
                <form class="user" method="POST" action="' . home_url("login") . '">
                    <div class="form-group">
                        <input type="email" class="form-control form-control-user" name="email"
                            placeholder="Enter Email Address...">
                    </div>
                    <div class="form-group">
                        <input type="password" class="form-control form-control-user"
                            name="password" placeholder="Password">
                    </div>
                    <!-- <div class="form-group">
                        <div class="custom-control custom-checkbox small">
                            <input type="checkbox" class="custom-control-input" id="customCheck">
                            <label class="custom-control-label" for="customCheck">Remember
                                Me</label>
                        </div>
                    </div> -->
                    <button type="submit" class="btn btn-primary btn-user btn-block">
                        Login
                    </button>
                </form>
                <hr>
                <div class="text-center">
                    <a class="small" href="' . home_url("forgot-password") . '">Forgot Password?</a>
                </div>
                <div class="text-center">
                    <a class="small" href="' . home_url("register") . '">Create an Account!</a>
                </div>
            </div>
        </div>
    </div>';

$pageContent = ob_get_clean();

include "layouts/layout-blank.php";
