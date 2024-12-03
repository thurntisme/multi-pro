<?php


// Define the layout file
$layout = DIR . '/layout.php';

include_once 'auth-route.php';
include_once DIR . '/functions/system.php';

$url = extractPathFromCurrentUrl();

$pageUrl = '';

$token = $commonController->getToken();
$user_id = '';
if (!empty($token)) {
    $user_id = $authenticationController->getCurrentUserId();
    if (!empty($user_id)) {
        $pageUrl = getPageData($url)['url'];
    } else {
        $commonController->removeToken();
        $pageUrl = DIR . '/functions/redirectUser.php';
    }
    try {
        include_once $pageUrl;
    } catch (Throwable $th) {
        $error_msg = $th->getMessage();
        include_once DIR . '/views/500.php';
    }
} else {
    switch ($url) {
        case 'login':
            $pageUrl = DIR . '/views/login.php';
            break;
        case 'register':
            $pageUrl = DIR . '/views/register.php';
            break;
        case 'forgot-password':
            $pageUrl = DIR . '/views/forgot-password.php';
            break;
        case 'logout':
            $pageUrl = DIR . '/views/logout.php';
            break;
        default:
            $pageUrl = DIR . '/functions/redirectUser.php';
            break;
    }
    include_once $pageUrl;
}