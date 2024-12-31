<?php
require_once "controllers/SystemController.php";

$token = $commonController->getToken();

include_once 'app-route.php';
include_once DIR . '/functions/system.php';

$url = extractPathFromCurrentUrl();
if ($url === '') {
    include_once DIR . '/views/landing.php';
    exit;
}

if (!empty($url) && str_starts_with($url, 'api')) {
    include_once 'api/index.php';
}

$pageUrl = '';

$user_id = '';
if (!empty($token)) {
    try {
        $curr_data = $authenticationController->getCurrentUser();
        if (!empty($curr_data)) {
            if ($curr_data['isActive'] === 1) {
                $user_id = $curr_data['id'];
                $systemController = new SystemController($user_id);
                $cur_lang = $systemController->getLanguage();
                include_once DIR . "/assets/lang-php/" . $cur_lang . ".php";
                $user_role = $curr_data['role'];
                $app_url = str_replace('app/', '', $url);
                include_once  getPageData($app_url, $user_role)['url'];
                include DIR . '/app-layout.php';
            } else {
                include_once DIR . '/views/user-block.php';
                include  DIR . '/auth-layout.php';
            }
        } else {
            $commonController->removeToken();
            $pageUrl = DIR . '/functions/redirectUser.php';
        }
    } catch (Throwable $th) {
        $error_msg = $th->getMessage();
        include_once DIR . '/views/500.php';
        include  DIR . '/auth-layout.php';
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
    include  DIR . '/auth-layout.php';
}
