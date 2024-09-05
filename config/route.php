<?php


// Define the layout file
$layout = DIR . '/layout.php';

// Determine the content file based on the request URI
$url = isset($_GET['url']) ? $_GET['url'] : '';
$pageUrl = '';

$token = $commonController->getToken();
$user_id = '';
if (!empty($token)) {
  $user_id = $authenticationController->getCurrentUserId();
  if (!empty($user_id)) {
    switch ($url) {
      case '':
        $pageUrl = DIR . '/views/dashboard.php';
        break;
      case 'profile':
        $pageUrl = DIR . '/views/profile.php';
        break;
      case 'settings':
        $pageUrl = DIR . '/views/settings.php';
        break;
      case 'devices':
        $pageUrl = DIR . '/views/devices.php';
        break;
      case 'logout':
        $pageUrl = DIR . '/views/logout.php';
        break;
      case 'dashboard':
      case 'login':
      case 'register':
      case 'forgot-password':
        $pageUrl = DIR . '/functions/redirectUser.php';
        break;
      default:
        $pageUrl = DIR . '/views/404.php';
        break;
    }
  } else {
    $commonController->removeToken();
    $pageUrl = DIR . '/functions/redirectUser.php';
  }
  include_once $pageUrl;
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
