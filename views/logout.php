<?php
$pageTitle = "Logout";

require_once 'controllers/AuthenticationController.php';

$authenticationController = new AuthenticationController();
$authenticationController->logout();

ob_start();
echo '<script type="text/javascript">
        removeToken();
        window.location.href = "' . home_url("login") . '";
    </script>';
$additionJs = ob_get_clean();

include "layouts/layout-blank.php";
