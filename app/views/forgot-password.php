<?php
$pageLogin = "Forgot your password";

ob_start();

echo '<div class="row">
        <div class="col-lg-6 d-none d-lg-block bg-password-image"></div>
        <div class="col-lg-6">
            <div class="p-5">
                <div class="text-center">
                    <h1 class="h4 text-gray-900 mb-2">Forgot Your Password?</h1>
                    <p class="mb-4">We get it, stuff happens. Just enter your email address below
                        and we will send you a link to reset your password!</p>
                </div>
                <form class="user">
                    <div class="form-group">
                        <input type="email" class="form-control form-control-user"
                            id="exampleInputEmail" aria-describedby="emailHelp"
                            placeholder="Enter Email Address...">
                    </div>
                    <a href="login.html" class="btn btn-primary btn-user btn-block">
                        Reset Password
                    </a>
                </form>
                <hr>
                <div class="text-center">
                    <a class="small" href="' . home_url("register") . '">Create an Account!</a>
                </div>
                <div class="text-center">
                    <a class="small" href="' . home_url("login") . '">Already have an account? Login!</a>
                </div>
            </div>
        </div>
    </div>';

$pageContent = ob_get_clean();

include "layouts/layout-blank.php";
