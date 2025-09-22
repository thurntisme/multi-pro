<?php
require APP_ROOT . "/controllers/SystemController.php";

$token = $commonController->getToken();

include_once 'app-route.php';
include_once APP_ROOT . '/functions/system.php';

$url = extractPathFromCurrentUrl();
if ($url === '') {
    include_once APP_ROOT . '/views/landing.php';
    include APP_ROOT . '/landing-layout.php';
    exit;
}
if ($url === 'resources') {
    include_once APP_ROOT . '/views/resources.php';
    include APP_ROOT . '/landing-layout.php';
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
                include_once APP_ROOT . "/assets/lang-php/" . $cur_lang . ".php";
                $user_role = $curr_data['role'];
                $app_url = str_replace('app/', '', $url);
                include_once getPageData($app_url, $user_role)['url'];
                include APP_ROOT . '/app-layout.php';
            } else {
                include_once APP_ROOT . '/views/user-block.php';
                include APP_ROOT . '/auth-layout.php';
            }
        } else {
            $commonController->removeToken();
            $pageUrl = APP_ROOT . '/functions/redirectUser.php';
        }
    } catch (Throwable $th) {
        $error_msg = $th->getMessage();
        include_once APP_ROOT . '/views/500.php';
        include APP_ROOT . '/auth-layout.php';
    }
} else {
    switch ($url) {
        case 'login':
            $pageUrl = APP_ROOT . '/views/login.php';
            break;
        case 'register':
            $pageUrl = APP_ROOT . '/views/register.php';
            break;
        case 'forgot-password':
            $pageUrl = APP_ROOT . '/views/forgot-password.php';
            break;
        case 'logout':
            $pageUrl = APP_ROOT . '/views/logout.php';
            break;
        default:
            $pageUrl = APP_ROOT . '/functions/redirectUser.php';
            break;
    }
    include_once $pageUrl;
    include APP_ROOT . '/auth-layout.php';
}
