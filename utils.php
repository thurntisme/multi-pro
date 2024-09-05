<?php

function getDayWithSuffix($day)
{
  if (!in_array(($day % 100), [11, 12, 13])) {
    switch ($day % 10) {
      case 1:
        return $day . 'st';
      case 2:
        return $day . 'nd';
      case 3:
        return $day . 'rd';
    }
  }
  return $day . 'th';
}

function home_url($path = '')
{
  // Get the scheme (http or https)
  $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';

  // Get the host (e.g., example.com)
  $host = $_SERVER['HTTP_HOST'];

  // Get the base URL
  $base_url = $scheme . '://' . $host;

  if (!empty(HOME_PATH)) {
    $base_url .= '/' . HOME_PATH;
  }

  // Append the path if provided
  if ($path) {
    $base_url .= '/' . ltrim($path, '/');
  }

  return $base_url;
}

function timeAgo($timestamp)
{
  $timeDifference = time() - strtotime($timestamp);

  if ($timeDifference < 60) {
    return $timeDifference . 's ago';
  } elseif ($timeDifference < 3600) {
    return round($timeDifference / 60) . ' minutes ago';
  } elseif ($timeDifference < 86400) {
    return round($timeDifference / 3600) . ' hours ago';
  } elseif ($timeDifference < 604800) {
    return round($timeDifference / 86400) . ' days ago';
  } else {
    return date("Y-m-d", strtotime($timestamp));
  }
}

function getCurrentDaySchedule($schedule)
{
  $currentDay = date('l'); // Get the current day of the week

  foreach ($schedule as $daySchedule) {
    if ($daySchedule['day'] === $currentDay) {
      return $daySchedule['events'];
    }
  }

  return null; // Return null if no schedule is found for the current day
}

function getTimeLeft($timeString)
{
  // Get the current date and time
  $currentDate = new DateTime();

  // Define the target date and time
  $targetDate = new DateTime();
  list($hours, $mins) = explode(':', $timeString);
  $targetDate->setTime($hours, $mins, 0);

  // Calculate the difference between the target date and the current date
  $interval = $currentDate->diff($targetDate);

  // Convert interval to total seconds
  $totalSeconds = ($interval->days * 24 * 60 * 60) + ($interval->h * 3600) + ($interval->i * 60) + $interval->s;

  // Determine the appropriate time unit
  if ($totalSeconds < 60) {
    return $totalSeconds . "s left";
  } elseif ($totalSeconds < 3600) { // Less than 60 minutes
    $minutes = floor($totalSeconds / 60);
    return $minutes . "m left";
  } else { // More than or equal to 60 minutes
    $hours = floor($totalSeconds / 3600);
    $minutes = floor(($totalSeconds % 3600) / 60);
    return $hours . "h, " . $minutes . "m left";
  }
}

function convertToTitleCase($string)
{
  // Replace underscores with spaces
  $string = str_replace('_', ' ', $string);

  // Capitalize the first letter of each word
  $string = ucwords($string);

  return $string;
}

function getLastSegmentFromUrl()
{
  $url = $_SERVER['REQUEST_URI'];

  // Parse the URL to get the path
  $parsedUrl = parse_url($url, PHP_URL_PATH);

  // Remove the leading slash if it exists
  $trimmedPath = ltrim($parsedUrl, '/');

  // Split the path into segments by "/"
  $segments = explode('/', $trimmedPath);

  // Get the last segment
  return end($segments);
}

function generateBreadcrumbs($pageTitle, $separator = ' > ')
{
  // Get the current URL path
  $path = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');

  // Explode the path into an array
  $pathArray = explode('/', $path);

  // Initialize an empty array to store the breadcrumbs
  $breadcrumbs = [];

  if (count($pathArray) > 1) {
    // Loop through each path segment
    foreach ($pathArray as $key => $value) {
      // Build the breadcrumb URL
      $breadcrumbUrl = home_url($key === 0 ? "/" : implode('/', array_slice($pathArray, 1, $key)));

      // Capitalize and replace hyphens with spaces for better readability
      $crumbName = $key === 0 ? 'Dashboard' : ucwords(str_replace('-', ' ', $value));

      // Add each breadcrumb to the array
      $breadcrumbs[] = $key === count($pathArray) - 1 ? "<span>$crumbName</span>" : "<a href='$breadcrumbUrl'>$crumbName</a>";
    }

    // Join the breadcrumbs using the separator and return the result
    return implode($separator, $breadcrumbs);
  } else {
    return $pageTitle;
  }
}

function renderStatusBadge($status)
{
  // Define the badge classes based on the status
  $badgeClasses = [
    'todo'     => 'badge-light',
    'backlog'  => 'badge-dark',
    'complete' => 'badge-success',
    'in-progress' => 'badge-primary',
    'blocked'  => 'badge-danger',
    'on-hold'  => 'badge-warning',
  ];

  // Default badge class if status is not found
  $badgeClass = isset($badgeClasses[$status]) ? $badgeClasses[$status] : 'badge-secondary';

  // Return the badge HTML
  return "<span class='badge $badgeClass'>" . ucfirst($status) . "</span>";
}

function activeClassName($url)
{
  $currentUrl = isset($_GET['url']) ? $_GET['url'] : '';
  $urlParts = explode('/', trim($currentUrl, '/'));
  $parentPath = count($urlParts) > 0 ? $urlParts[0] : '';
  return $parentPath === $url ? 'active' : '';
}
